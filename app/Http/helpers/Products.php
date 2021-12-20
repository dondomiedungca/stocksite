<?php

namespace App\Http\helpers;

use App\Models\Inventory;
use App\Models\PurchasingTypes;
use App\Models\Counter;

use Illuminate\Support\Facades\Log;

class Products {

    public static function createAndUpdateIfExist($collections, $product_type_id, $docs) {
        $new = [];
        $exist = [];

        foreach ($collections as $key => $item) {
            $wheres = [];

            foreach ($docs->headers as $key => $value) {
                $wheres["details->$value"] = $item[$value];
            }

            $inventory = Inventory::where($wheres)->first();

            if($inventory) {
                $inventory->file_upload_id = $docs->file_eloquent->id;
                $inventory->save();
                $exist[] = $inventory;
            } else {
                $inv = [
                    'stock_number' => getCounterNumber(3),
                    'product_type_id' => $product_type_id,
                    'inventory_status_id' => 1,
                    'file_upload_id' => $docs->file_eloquent->id,
                    'inventory_cosmetic_id' => 1,
                    'item_cosmetic_description' => '',
                    'item_description' => '',
                    'origin_price' => 0.00,
                    'selling_price' => 0.00,
                    'discount_percentage' => 0.00,
                    'details' => json_encode($item),
                ];

                $new[] = $inv;
            }
        }

        if(count($new)) {
            Inventory::insert($new);
        }

        return [
            'new' => $new,
            'exist' => $exist
        ];
    }

}

?>