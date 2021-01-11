<?php

namespace App\Http\helpers;

use App\Models\BatchJobs;
use App\Models\CurrentRunning;
use App\Events\QueueProcessing;

use Illuminate\Support\Facades\DB;

class BatchHelpers {

    public static function getBatch($uuid) {
        $batch = BatchJobs::where('id', $uuid)
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

            return true;
        }
    }

    public static function removeFromProcessing($uuid) {
        CurrentRunning::where('uuid', $uuid)->delete();

        return true;
    }

    public static function removeCancelledAt($uuid) {
        BatchJobs::where('id', $uuid)->update([
            'cancelled_at' => NULL
        ]);

        return true;
    }

}

?>