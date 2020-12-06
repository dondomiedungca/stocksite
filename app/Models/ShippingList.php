<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingList extends Model
{
    protected $table = 'shipping_list';

    protected $fillable = [
        'shipping_type',
        'shipping_dealer_name',
        'shipping_amount'
    ];
}
