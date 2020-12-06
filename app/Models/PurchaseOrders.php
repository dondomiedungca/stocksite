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
        'receiver_first_name',
        'receiver_middle_name',
        'receiver_last_name',
        'address_id',
        'receiver_phone_number',
        'receiver_email'
    ];
}
