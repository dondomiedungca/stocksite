<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suppliers extends Model
{
    protected $table = 'suppliers';

    protected $fillable = [
        'address_id',
        'supplier_name',
        'manufacturer_id',
        'supplier_email',
        'supplier_phone_number'
    ];
}
