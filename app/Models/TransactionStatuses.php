<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionStatuses extends Model
{
    protected $table = 'transaction_statuses';

    protected $fillable = [
        'transaction_status_name'
    ];

    public function transaction() {
        return $this->hasMany('App\Models\Transactions', 'transaction_status_id');
    }
}
