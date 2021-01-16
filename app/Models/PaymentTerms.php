<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentTerms extends Model
{
    protected $table = 'payment_terms';

    protected $fillable = [
        'payment_term_name',
        'no_of_days'
    ];
}
