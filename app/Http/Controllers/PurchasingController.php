<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PurchasingController extends Controller
{
    public function index() {
        return view('admin.purchasing.index');
    }

    public function purchaseOrder() {
        return view('admin.purchasing.purchase_order');
    }
}
