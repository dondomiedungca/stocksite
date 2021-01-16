<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    protected $table = 'customers';

    protected $fillable = [
        'customer_type_id',
        'customer_company_info_id',
        'customer_address_id',
        'customer_representative_first_name',
        'customer_representative_middle_name',
        'customer_representative_last_name',
        'customer_credit',
        'customer_phone_number',
        'customer_email'
    ];
}
