<?php

namespace App\Http\helpers;

use App\Models\BatchJobs;
use App\Models\BatchMessage;
use App\Models\CurrentRunning;
use App\Events\QueueProcessing;

use Carbon\Carbon;

use Illuminate\Support\Facades\DB;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Throwable;

class BatchHelpers {

    public static function getBatch($uuid) {
        $batch = BatchJobs::with('message')->where('id', $uuid)
                            ->get();

        $batch->transform(function ($value) {
            $value->failed_jobs = $value->failed_jobs();
            $value->created_date = $value->created_at->format('M d, Y - h:i A');
            return $value;
        });

        return $batch[0];
    }

    public static function willBroadcastProcess($uuid) {
        $current = CurrentRunning::where('uuid', $uuid)->get();

        if(!$current->count()) {
            broadcast(new QueueProcessing("processing", self::getBatch($uuid)));

            CurrentRunning::create(
                ['uuid' => $uuid]
            );

            $batch = BatchJobs::find($uuid);
            $batch->date_processed = Carbon::now();
            $batch->timestamps = false;
            $batch->save();

            return true;
        }
    }

    public static function removeFromProcessing($uuid) {
        CurrentRunning::where('uuid', $uuid)->delete();

        return true;
    }

    public static function removeCancelledAt($uuid) {
        $batch = BatchJobs::find($uuid);
        $batch->cancelled_at = NULL;
        $batch->retry = 1;
        $batch->timestamps = false;
        $batch->save();

        return true;
    }

    public static function generateDuration($uuid) {
        $batch = BatchJobs::where('id', $uuid)->first();

        $process_time = Carbon::createFromFormat('Y-m-d H:i:s', $batch->date_processed);
        $finished_at = Carbon::parse(date("Y-m-d H:i:s", $batch->finished_at));

        $totalDuration =  $process_time->diff($finished_at)->format('%H:%I:%S');

        $batch->duration = $totalDuration;
        $batch->timestamps = false;
        $batch->save();
    }

    public static function importMessage($uuid = NULL, $message = NULL) {
        $batch = BatchJobs::find($uuid);

        $mess = new BatchMessage();
        $mess->message = $message;
        $mess->batch()->associate($batch);
        $mess->save();

        return true;
    }

    public static function processJobs($jobs, $filename, $queue_no, $title) {
        $batch = Bus::batch($jobs)->then(function (Batch $batch){
            // All jobs completed successfully...
        })->catch(function (Batch $batch, Throwable $e) {
            // Only First batch job failure detected...
            $batch->cancel();
        })->finally(function (Batch $batch) {
            // The batch has finished executing...
            // I putted here the event calling as failed because the error message will only get if the batch was cancelled,
            // the batch will continue to finish other jobs even the previous jobs are failed
            if($batch->cancelled()) {
                broadcast(new QueueProcessing("failed", self::getBatch($batch->id)));
            }
            // This only run at fresh batch, not when retry
            if((int) $batch->progress() == 100) {
                self::generateDuration($batch->id);
                self::importMessage($batch->id, "File content was successfully inserted to database.");
                broadcast(new QueueProcessing("complete", self::getBatch($batch->id)));
            }
            self::removeFromProcessing($batch->id);
        })->name($filename.' - '.$title.'*_*'.$queue_no)->onQueue('product_imports')->dispatch();

        broadcast(new QueueProcessing("create", self::getBatch($batch->id)));

        return $batch;
    }

}

?>