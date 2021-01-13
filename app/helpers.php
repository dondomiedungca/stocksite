<?php

use App\Models\Company;
use App\Models\Currency;
use App\Models\PurchasingTypes;
use App\Models\ProductTypes;

use Illuminate\Support\Facades\Log;

function getCurrency() {
    $company = Company::find(1);

    return $company->currency;
}

function isValidToReceive($purchasing_type_id) {
    $purchasing_type = PurchasingTypes::find($purchasing_type_id);

    if($purchasing_type->total_items_to_receive) {
        return true;
    } else {
        return false;
    }
}

function isNotEmpty($product_type_id, $data) {

    $product_type = ProductTypes::with('product_attributes')->whereId($product_type_id)->first();
    $required_fields = [];
    $fields_no_data = [];
    foreach ($product_type->product_attributes as $column) {
        if((int) $column->product_column_is_required == 1) {
            if($data[$column->product_column_name] == '' || $data[$column->product_column_name] == NULL) {
                $fields_no_data[] = $column->product_column_name;
            }
        }
    }

    if(count($fields_no_data) > 0) {
        $data['isValid'] = false;
        $data['no_data'] = $fields_no_data;
    } else {
        $data['isValid'] = true;
        $data['no_data'] = $fields_no_data;
    }

    return $data;
}

?>