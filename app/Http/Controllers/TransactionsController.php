<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\TransactionStatuses;
use App\Models\DeliveryStatus;
use App\Models\ItemStatus;
use App\Models\PaymentStatuses;

class TransactionsController extends Controller
{
    public function getTransactionStatuses() {
        return TransactionStatuses::all();
    }

    public function getDeliveries() {
        return DeliveryStatus::all();
    }

    public function getItemStatuses() {
        return ItemStatus::all();
    }

    public function getPayments() {
        return PaymentStatuses::all();
    }
}
