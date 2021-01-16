<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionTypes extends Model
{
    protected $table = 'transaction_types';

    protected $fillable = [
        'transaction_type_name'
    ];
}
