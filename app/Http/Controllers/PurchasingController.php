<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use App\Models\Transactions;
use App\Models\PurchaseOrders;
use App\Models\PurchasingTypes;
use App\Models\Counter;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;

class PurchasingController extends Controller
{
    public function index() {
        return view('admin.purchasing.index');
    }

    public function purchaseOrder() {
        return view('admin.purchasing.purchase_order');
    }

    public function getPurchaseOrders() {
        return view('admin.purchasing.purchase_order_list');
    }

    public function store(Request $request) {
        $counter = Counter::find(1);
		$counter->increment('counter');
        $transaction_code = $counter->prefix . str_pad($counter->counter, 6,'0',STR_PAD_LEFT);
        
        $transaction = new Transactions();
        $transaction->transaction_type_id = 1; // Purchase Order
        $transaction->transaction_status_id = 7; // Pending
        $transaction->payment_status_id = 1; // Unpaid
        $transaction->supplier_id = $request['supplier']['id'];
        $transaction->transaction_code = $transaction_code;
        $transaction->additional_cost = $request['additional_cost'];
        $transaction->total_price = $request['total'];
        $transaction->save();

        $purchase_order = new PurchaseOrders();
        $purchase_order->user_id = Auth::user()->id;
        $purchase_order->receiver_id = $request['receiver']['id'];
        $purchase_order->transaction()->associate($transaction);
        $purchase_order->save();

        foreach ($request['inventories'] as $key => $inventory) {
            $purchasing_types = new PurchasingTypes();
            $purchasing_types->product_type_id = (int) $inventory['inventory_type'];
            $purchasing_types->quantity = (int) $inventory['inventory_quantity'];
            $purchasing_types->inventory_total_price = (int) $inventory['inventory_total_price'];
            $purchasing_types->purchasing_description = $inventory['inventory_description'];
            $purchasing_types->total_items_to_receive = $inventory['inventory_quantity'];
            $purchasing_types->purchase_orders()->associate($purchase_order);
            $purchasing_types->save();
        }
        $counter->save();

        $data['success'] = true;
        $data['heading'] = "Purchase Order Created";
        $data['message'] = $transaction_code." Transaction - Purchase Order has been created";   
        $data['transaction_id'] = $transaction->id;
        
        return response()->json($data);
    }

    public function getAllPurchaseOrders($perPage = 10) {
        $purchaseOrders = Transactions::where('transaction_type_id', 1)
                                        ->with(
                                            'transaction_status',
                                            'delivery_status',
                                            'item_status',
                                            'supplier',
                                            'payment_status',
                                            'purchase_orders', 
                                            'purchase_orders.receiver', 
                                            'purchase_orders.user', 
                                            'purchase_orders.purchase_order_types',
                                            'purchase_orders.purchase_order_types.product_types'
                                        )
                                        ->latest()
                                        ->paginate($perPage);

        return response()->json($purchaseOrders);
    }

    public function getPurchaseOrder($id) {
        $purchaseOrder = Transactions::where('transaction_type_id', 1)
                                        ->whereId($id)
                                        ->with(
                                            'transaction_status',
                                            'delivery_status',
                                            'item_status',
                                            'supplier',
                                            'supplier.address.address_type',
                                            'payment_status',
                                            'purchase_orders', 
                                            'purchase_orders.receiver.address.address_type', 
                                            'purchase_orders.user', 
                                            'purchase_orders.purchase_order_types.photo',
                                            'purchase_orders.purchase_order_types.product_types.product_attributes.column_selections'
                                        )->first();

        return view('admin.purchasing.purchase_order_detail', ['purchase_order' => $purchaseOrder]);
    }

    public function getPurchaseOrderData($id) {
        $purchaseOrder = Transactions::where('transaction_type_id', 1)
                                        ->whereId($id)
                                        ->with(
                                            'transaction_status',
                                            'delivery_status',
                                            'item_status',
                                            'supplier',
                                            'supplier.address.address_type',
                                            'payment_status',
                                            'purchase_orders', 
                                            'purchase_orders.receiver.address.address_type', 
                                            'purchase_orders.user', 
                                            'purchase_orders.purchase_order_types.photo',
                                            'purchase_orders.purchase_order_types.inventories',
                                            'purchase_orders.purchase_order_types.product_types.product_attributes.column_selections'
                                        )->first();
        $data['purchase_order'] = $purchaseOrder;

        return response()->json($data);
    }

    public function removePurchasing($id = NULL) {
        $transaction = Transactions::with('purchase_orders.purchase_order_types.inventories')->whereId($id)->first();

        foreach ($transaction->purchase_orders->purchase_order_types as $key => $purchase_order_type) {
            $purchase_order_type->inventories()->delete();
            Storage::deleteDirectory($purchase_order_type->photo->path);
            $purchase_order_type->inventories()->detach();
            $purchase_order_type->photo()->delete();
        }

        $transaction->delete();

        $data['isSuccess'] = true;
        $data['heading'] = 'Purchase Order Deleted';
        $data['message'] = 'All item(s) in this purchasing order are now removed';

        return response()->json($data);
    }

    public function getPurchasingType($id) {
        $purchasing_type = PurchasingTypes::with('photo','product_types.product_attributes.column_selections')->whereId($id)->first();

        $data['purchasing'] = $purchasing_type;

        return response()->json($data);
    }
}
