<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemStatus extends Model
{
    protected $table = 'item_statuses';

    protected $fillable = [
        'name'
    ];

    public function transaction() {
        return $this->hasMany('App\Models\Transactions', 'item_status_id');
    }
}
