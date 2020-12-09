<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $table = 'currencies';

    protected $fillable = [
        'countryname',
        'name',
        'symbol'
    ];

    public function company() {
        return $this->hasOne('App\Models\Company', 'currency_id');
    }
}
