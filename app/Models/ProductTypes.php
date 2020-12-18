<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTypes extends Model
{
    protected $table = 'product_types';

    protected $fillable = [
        'product_name',
        'user_id'
    ];

    public function product_attributes() {
        return $this->hasMany('App\Models\ProductAttributes', 'product_type_id');
    }

    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function purchasing_types() {
        return $this->hasMany('App\Models\PurchasingTypes', 'product_type_id');
    }
}
