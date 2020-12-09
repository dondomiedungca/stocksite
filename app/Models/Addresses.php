<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Addresses extends Model
{
    use HasFactory;
    
    protected $table = 'addresses';

    protected $fillable = [
        'address_type_id',
        'address_no_or_house_building_no',
        'address_st',
        'address_city',
        'address_state',
        'address_country',
        'address_post_code',
    ];

    public function address_type() {
        return $this->belongsTo('App\Models\AddressTypes', 'address_type_id');
    }

    public function supplier() {
        return $this->hasOne('App\Models\Suppliers', 'address_id');
    }

    public function receiver() {
        return $this->hasOne('App\Models\Receiver', 'address_id');
    }

    public function company() {
        return $this->hasOne('App\Models\Receiver', 'address_id');
    }
}
