<?php

namespace App\Http\helpers;

use App\Models\BatchJobs;
use App\Models\BatchMessage;
use App\Models\CurrentRunning;
use App\Events\QueueProcessing;

use Carbon\Carbon;

use Illuminate\Support\Facades\DB;

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

}

?>