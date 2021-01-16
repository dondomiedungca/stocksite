<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrentRunning extends Model
{
    protected $table = 'current_job_running';

    protected $fillable = [
        'id',
        'uuid'
    ];

    protected $casts = [
        'id' => 'string'
    ];
}
