<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryStatus extends Model
{
    protected $table = 'delivery_statuses';

    protected $fillable = [
        'name'
    ];

    public function transaction() {
        return $this->hasMany('App\Models\Transactions', 'delivery_status_id');
    }
}
