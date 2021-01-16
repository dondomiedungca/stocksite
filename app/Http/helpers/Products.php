<?php

namespace App\Http\helpers;

use App\Models\Inventory;
use App\Models\PurchasingTypes;
use App\Models\Counter;

use Illuminate\Support\Facades\Log;

class Products {

    public static function importItems($product_type_id, $purchasing_type_id, $data = NULL) {
        $counter = Counter::find(3);
        $keys = array_keys($data);
        $wheres = [];

        foreach ($keys as $key => $value) {
            $wheres["details->$value"] = $data[$value];
        }
        $inventory = Inventory::where($wheres)->first();

        if($inventory == NULL) {
            if(isValidToReceive($purchasing_type_id)) {
                $purchase_order_type = PurchasingTypes::find($purchasing_type_id);
                $purchase_order_type->increment('total_received');
                $purchase_order_type->decrement('total_items_to_receive');

                $counter->increment('counter');
                $stock_number = $counter->prefix . str_pad($counter->counter, 6,'0',STR_PAD_LEFT);

                $inv = new Inventory();
                $inv->stock_number = $stock_number;
                $inv->product_type_id = $product_type_id;
                $inv->inventory_status_id = 1;
                $inv->inventory_cosmetic_id = 1;
                $inv->item_cosmetic_description = "";
                $inv->item_description = "";
                $inv->origin_price = 0.00;
                $inv->selling_price = 0.00;
                $inv->discount_percentage = 0.00;
                $inv->details = $data;
                $inv->save();

                $purchase_order_type->inventories()->save($inv);
            }
        }
    }

}

?>