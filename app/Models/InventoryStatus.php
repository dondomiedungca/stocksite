<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryStatus extends Model
{
    protected $table = 'inventory_statuses';

    protected $fillable = [
        'name'
    ];

    public function inventory() {
        return $this->hasMany('App\Models\Inventory', 'inventory_status_id');
    }
}
