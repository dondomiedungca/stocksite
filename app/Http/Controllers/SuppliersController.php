<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Suppliers;
use App\Models\Addresses;
use App\Models\AddressTypes;
use App\Models\Manufacturer;

use Illuminate\Support\Facades\DB;

class SuppliersController extends Controller
{
    public function supplierReceiverLists() {
        return view('admin.suppliers.list');
    }

    public function addSupplier(Request $request) {
        $address_type = AddressTypes::find($request['address']['address_type']);
        $manufacturer = Manufacturer::find($request['manufacturer']);

        $address = new Addresses();
        $address->address_no_or_house_building_no = $request['address']['no'];
        $address->address_st = $request['address']['street'];
        $address->address_city = $request['address']['city'];
        $address->address_state = $request['address']['state'];
        $address->address_country = $request['address']['country'];
        $address->address_post_code = $request['address']['post_code'];
        $address->address_type()->associate($address_type);
        $address->save();

        $supplier = new Suppliers();
        $supplier->supplier_name = $request['supplier_name'];
        $supplier->supplier_email = $request['supplier_email'];
        $supplier->supplier_phone_number = $request['supplier_phone'];
        $supplier->address()->associate($address);
        $supplier->manufacturer()->associate($manufacturer);
        $supplier->save();

        $data['heading'] = "New Supplier";
        $data['message'] = 'Supplier has been added successfuly';
        $data['success'] = true;

        return response()->json($data);
    }

    public function getSuppliers() {
        $suppliers = Suppliers::with('address', 'address.address_type', 'manufacturer')->get();

        $data['suppliers'] = $suppliers;

        return response()->json($data);
    }

    public function getPaginateSuppliers(Request $request) {
        $suppliers = Suppliers::with('address', 'address.address_type', 'manufacturer')->paginate(10);

        $data['suppliers'] = $suppliers;

        return response()->json($data);
    }

    public function getCountries() {
        $countries = DB::table('countries')->get();

        $data['countries'] = $countries;

        return response()->json($data);
    }

    public function updateSupplier(Request $request) {
        $supplier = Suppliers::find($request['supplier']['id']);
        $address = Addresses::find($request['supplier']['address_id']);

        $supplier->supplier_name = $request['supplier']['supplier_name'];
        $supplier->supplier_email = $request['supplier']['supplier_email'];
        $supplier->manufacturer_id = $request['supplier']['manufacturer_id'];
        $supplier->supplier_phone_number = $request['supplier']['supplier_phone_number'];
        $supplier->save();

        $address->address_type_id = $request['supplier']['address']['address_type_id'];
        $address->address_no_or_house_building_no = $request['supplier']['address']['address_no_or_house_building_no'];
        $address->address_st = $request['supplier']['address']['address_st'];
        $address->address_city = $request['supplier']['address']['address_city'];
        $address->address_state = $request['supplier']['address']['address_state'];
        $address->address_country = $request['supplier']['address']['address_country'];
        $address->address_post_code = $request['supplier']['address']['address_post_code'];
        $address->save();

        $data['heading'] = 'Supplier Details Updated';
        $data['isSuccess'] = true;
        $data['message'] = " Supplier details was successfully updated";

        return response()->json($data);
    }

    public function removeSupplier(Request $request) {
        $supplier = Suppliers::find($request['supplier']['id']);

        $count = $supplier->transactions()->count();

        if($count) {
            $data['heading'] = 'Supplier Restricted';
            $data['isSuccess'] = false;
            $data['message'] = " Supplier cannot be remove because it has a transactions";
        } else {
            $supplier->address()->delete();
            $supplier->delete();

            $data['heading'] = 'Supplier Removed';
            $data['isSuccess'] = true;
            $data['message'] = " Supplier was successfully removed";
        }

        return response()->json($data);
    }
}
