<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrders extends Model
{
    protected $table = 'purchase_orders';

    protected $fillable = [
        'transaction_id',
        'user_id',
        'receiver_id'
    ];

    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function transaction() {
        return $this->belongsTo('App\Models\Transactions', 'transaction_id');
    }

    public function receiver() {
        return $this->belongsTo('App\Models\Receiver', 'receiver_id');
    }

    public function purchase_order_types() {
        return $this->hasMany('App\Models\PurchasingTypes', 'product_type_id');
    }
}
