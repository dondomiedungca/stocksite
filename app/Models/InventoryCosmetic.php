<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryCosmetic extends Model
{
    protected $table = 'inventory_cosmetics';

    protected $fillable = [
        'name'
    ];
}
