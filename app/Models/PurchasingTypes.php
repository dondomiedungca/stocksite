<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Inventory;
use App\Models\Photable;

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

    public function purchase_orders() {
        return $this->belongsTo('App\Models\PurchaseOrders', 'purchase_order_id');
    }

    public function product_types() {
        return $this->belongsTo('App\Models\ProductTypes', 'product_type_id');
    }

    public function inventories()
    {
        return $this->morphToMany(Inventory::class, 'transaction_item');
    }

    public function photo()
    {
        return $this->morphOne(Photable::class, 'photable');
    }
}
