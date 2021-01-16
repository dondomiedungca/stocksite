<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ColumnSelection extends Model
{
    protected $table = 'column_selections';

    protected $fillable = [
        'column_id',
        'selection_name'
    ];

    public function column_name() {
        return $this->belongsTo('App\Models\ProductAttributes', 'column_id');
    }
}
