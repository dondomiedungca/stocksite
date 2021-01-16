<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
    use HasFactory;

    public function user_type() {
        return $this->hasMany('App\Models\User', 'user_type');
    }
}
