<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddressTypes extends Model
{
    protected $table = 'address_types';

    protected $fillable = [
        'address_type_name'
    ];

    public function address() {
        return $this->hasMany('App\Models\Addresses', 'address_type_id');
    }
}
