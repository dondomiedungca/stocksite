<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileUploaded extends Model
{
    protected $table = 'file_uploaded';

    protected $fillable = [
        'file_name',
        'total_content',
        'total_uploaded',
        'total_exist',
        'user_id',
    ];
}
