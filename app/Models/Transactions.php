<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    protected $table = 'transactions';

    protected $fillable = [
        'transaction_type_id',
        'previous_transaction_status_id',
        'transaction_status_id',
        'payment_status_id',
        'delivery_status_id',
        'item_status_id',
        'customer_id',
        'supplier_id',
        'tax_id',
        'shipping_id',
        'payment_term_id',
        'transaction_code',
        'additional_cost',
        'discounted_price',
        'total_price',
        'cash',
        'credit',
        'cheque'
    ];

    public function purchase_orders() {
        return $this->hasOne('App\Models\PurchaseOrders', 'transaction_id');
    }

    public function supplier() {
        return $this->belongsTo('App\Models\Suppliers', 'supplier_id');
    }

    public function transaction_status() {
        return $this->belongsTo('App\Models\TransactionStatuses', 'transaction_status_id');
    }

    public function payment_status() {
        return $this->belongsTo('App\Models\PaymentStatuses', 'payment_status_id');
    }

    public function item_status() {
        return $this->belongsTo('App\Models\ItemStatus', 'item_status_id');
    }

    public function delivery_status() {
        return $this->belongsTo('App\Models\DeliveryStatus', 'delivery_status_id');
    }
}
