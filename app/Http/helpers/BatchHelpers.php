<?php

namespace App\Http\helpers;

use App\Models\BatchJobs;

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

}

?>