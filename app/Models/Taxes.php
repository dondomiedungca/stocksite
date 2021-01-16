<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Taxes extends Model
{
    protected $table = 'taxes';

    protected $fillable = [
        'tax_name',
        'tax_percentage',
        'tax_value'
    ];
}
