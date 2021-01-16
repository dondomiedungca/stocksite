<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentStatuses extends Model
{
    protected $table = 'payment_statuses';

    protected $fillable = [
        'payment_status_name'
    ];

    public function transaction() {
        return $this->hasMany('App\Models\Transactions', 'payment_status_id');
    }
}
