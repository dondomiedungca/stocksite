<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suppliers extends Model
{
    use HasFactory;
    
    protected $table = 'suppliers';

    protected $fillable = [
        'address_id',
        'supplier_name',
        'manufacturer_id',
        'supplier_email',
        'supplier_phone_number'
    ];

    public function address() {
        return $this->belongsTo('App\Models\Addresses', 'address_id');
    }

    public function manufacturer() {
        return $this->belongsTo('App\Models\Manufacturer', 'manufacturer_id');
    }
}
