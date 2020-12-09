<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function getCurrency() {
        return response()->json(getCurrency());
    }
}
