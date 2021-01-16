<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerTypes extends Model
{
    protected $table = 'customer_types';

    protected $fillable = [
        'customer_type_name'
    ];
}
