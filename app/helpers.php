<?php

use App\Models\Company;
use App\Models\Currency;

function getCurrency() {
    $company = Company::find(1);

    return $company->currency;
}

?>