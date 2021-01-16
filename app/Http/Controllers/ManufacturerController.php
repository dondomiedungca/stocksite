<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Manufacturer;

class ManufacturerController extends Controller
{
    public function getManufacturerTypes() {
        $manufacturers = Manufacturer::all();

        $data['manufacturer_types'] = $manufacturers;

        return response()->json($data);
    }
}
