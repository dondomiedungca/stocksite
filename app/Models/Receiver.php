<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receiver extends Model
{
    protected $table = 'receiver';

    protected $fillable = [
        'receiver_first_name',
        'receiver_middle_name',
        'receiver_last_name',
        'receiver_phone',
        'receiver_email',
        'address_id'
    ];

    public function address() {
        return $this->belongsTo('App\Models\Addresses', 'address_id');
    }
}
