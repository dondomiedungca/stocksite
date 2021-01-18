<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\PurchasingTypes;

class Inventory extends Model
{
    protected $table = 'inventories';

    protected $fillable = [
        'stock_number',
        'product_type_id',
        'inventory_status_id',
        'inventory_cosmetic_id',
        'item_cosmetic_description',
        'item_description',
        'origin_price',
        'selling_price',
        'discount_percentage',
        'details',
    ];

    protected $casts = [
         'details' => 'json',
    ];

    public function product_type() {
        return $this->belongsTo('App\Models\ProductTypes', 'product_type_id');
    }

    public function status() {
        return $this->belongsTo('App\Models\InventoryStatus', 'inventory_status_id');
    }
    
    public function cosmetic() {
        return $this->belongsTo('App\Models\InventoryCosmetic', 'inventory_cosmetic_id');
    }

    public function purchasing_type()
    {
        return $this->morphedByMany(PurchasingTypes::class, 'transaction_item');
    }

    public function photo()
    {
        return $this->morphOne(Photable::class, 'photable');
    }
}
