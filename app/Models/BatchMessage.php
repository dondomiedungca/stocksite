<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatchMessage extends Model
{
    use HasFactory;

    protected $table = 'batch_message';

    protected $fillable = [
        'batch_id',
        'message',
    ];

    protected $casts = [
        'batch_id' => 'string'
    ];

    public function batch() {
        return $this->belongsTo('App\Models\BatchJobs', 'batch_id');
    }
}
