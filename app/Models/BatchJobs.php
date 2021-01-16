<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\FailedJobs;

use Illuminate\Support\Facades\DB;

class BatchJobs extends Model
{
    protected $table = 'job_batches';

    protected $fillable = [
        'name',
        'total_jobs',
        'pending_jobs',
        'failed_jobs',
        'failed_job_ids',
        'options',
        'cancelled_at',
        'created_at',
        'date_processed',
        'finished_at'
    ];

    protected $casts = [
        'id' => 'string'
    ];

    public function failed_jobs() {
        $ids = json_decode($this->failed_job_ids);
        
        $failed = FailedJobs::whereIn('uuid', $ids)->get();

        return $failed;
    }

    public function message() {
        return $this->hasOne('App\Models\BatchMessage', 'batch_id');
    }
}
