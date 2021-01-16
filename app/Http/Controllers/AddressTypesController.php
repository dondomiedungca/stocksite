<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\AddressTypes;

class AddressTypesController extends Controller
{
    public function getAddressTypes() {
        $address_types = AddressTypes::all();

        $data['address_types'] = $address_types;

        return response()->json($data);
    }
}
