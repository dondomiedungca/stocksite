<?php

use App\Models\Company;
use App\Models\Currency;
use App\Models\PurchasingTypes;

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

?>