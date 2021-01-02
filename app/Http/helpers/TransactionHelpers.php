<?php

namespace App\Http\helpers;

use App\Models\Transactions;

class TransactionHelpers{

    public static function manageStatus($transaction_id) {
        $isItemCompleted = true;
        $deliveryStatus = 1; // For Packaging
        $itemStatus = 1; // No Items Receive Yet
        $transaction_status_id = 7; // Pending
        $payment_status_id = 1; // Unpaid

        $transaction = Transactions::whereId($transaction_id)->with('purchase_orders.purchase_order_types')->first();
        $payment_status_id = $transaction->payment_status_id;

        foreach ($transaction->purchase_orders->purchase_order_types as $key => $value) {
            if($value->total_items_to_receive > 0) {
                $isItemCompleted = false;
            }

            if($value->total_received > 0) {
                $itemStatus = 2; // Incomplete, but received some items
                $deliveryStatus = 3; // Partial Delivery
            }
        }

        if($isItemCompleted) {
            $itemStatus = 3; // Completed
            $deliveryStatus = 4; //Delivered
        }

        if($isItemCompleted && $payment_status_id == 3) {
            $transaction_status_id = 2; // Fulfilled
        } else {
            $transaction_status_id = 1; // Unfulfilled
        }

        Transactions::whereId($transaction_id)
                    ->update([
                        'delivery_status_id' => $deliveryStatus,
                        'item_status_id' => $itemStatus,
                        'transaction_status_Id' => $transaction_status_id,
                        'previous_transaction_status_id' => $transaction->transaction_status_id
                    ]);

        // return [
        //     'isItemCompleted' => $isItemCompleted,
        //     'deliveryStatus' => $deliveryStatus,
        //     'itemStatus' => $itemStatus,
        //     'transaction_status_id' => $transaction_status_id,
        //     'payment_status_id' => $payment_status_id
        // ];
    }

}

?>