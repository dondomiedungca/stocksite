<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manufacturer extends Model
{
    protected $table = 'manufacturer_types';

    protected $fillable = [
        'manufacturer_type_name'
    ];

    public function manufacturer() {
        return $this->hasMany('App\Models\Suppliers', 'manufacturer_id');
    }
}
