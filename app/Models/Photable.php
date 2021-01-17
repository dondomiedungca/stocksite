<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photable extends Model
{
    use HasFactory;

    protected $table = 'photables';

    protected $fillable = [
        'path',
        'photo_name'
    ];

    public function photable()
    {
        return $this->morphTo();
    }
}
