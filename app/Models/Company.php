<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $table = 'company';

    protected $fillable = [
        'company_name',
        'company_email',
        'company_phone',
        'employer_id',
        'tin_number',
        'address_id',
        'currency_id'
    ];

    public function currency() {
        return $this->belongsTo('App\Models\Currency', 'currency_id');
    }

    public function address() {
        return $this->belongsTo('App\Models\Address', 'address_id');
    }
}
