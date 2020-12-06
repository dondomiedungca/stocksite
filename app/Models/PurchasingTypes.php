<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchasingTypes extends Model
{
    protected $table = 'purchasing_types';

    protected $fillable = [
        'purchase_order_id',
        'product_type_id',
        'quantity',
        'inventory_total_price',
        'total_received',
        'total_items_to_receive',
        'purchasing_name'
    ];
}
