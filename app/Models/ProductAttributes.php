<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttributes extends Model
{
    protected $table = 'product_attributes';

    protected $fillable = [
        'product_type_id',
        'product_column_name',
        'product_column_is_required',
        'product_column_manual_fillable',
        'product_column_data_type',
        'product_column_input_type'
    ];

    public function product_type() {
        return $this->belongsTo('App\Models\ProductTypes', 'product_type_id');
    }

    public function column_selections() {
        return $this->hasMany('App\Models\ColumnSelection', 'column_id');
    }
}
