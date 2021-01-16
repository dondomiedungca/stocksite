<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyInfo extends Model
{
    protected $table = 'customers_company_info';

    protected $fillable = [
        'company_name',
        'company_phone_number',
        'company_email',
        'address_id',
    ];
}
