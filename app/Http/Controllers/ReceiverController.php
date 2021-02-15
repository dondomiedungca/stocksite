<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Receivers;
use App\Models\AddressTypes;
use App\Models\Addresses;

class ReceiverController extends Controller
{
    public function addReceiver(Request $request) {
        $address_type = AddressTypes::find($request['address']['address_type']);

        $address = new Addresses();
        $address->address_no_or_house_building_no = $request['address']['no'];
        $address->address_st = $request['address']['street'];
        $address->address_city = $request['address']['city'];
        $address->address_state = $request['address']['state'];
        $address->address_country = $request['address']['country'];
        $address->address_post_code = $request['address']['post_code'];
        $address->address_type()->associate($address_type);
        $address->save();

        $receiver = new Receivers();
        $receiver->receiver_first_name = $request['receiver_first_name'];
        $receiver->receiver_middle_name = $request['receiver_middle_name'];
        $receiver->receiver_last_name = $request['receiver_last_name'];
        $receiver->receiver_phone = $request['receiver_phone'];
        $receiver->receiver_email = $request['receiver_email'];
        $receiver->address()->associate($address);
        $receiver->save();

        $data['heading'] = "New Receiver";
        $data['message'] = 'Receiver has been added successfuly';
        $data['success'] = true;

        return response()->json($data);
    }

    public function getReceivers() {
        $receivers = Receivers::with('address', 'address.address_type')->orderBy('receiver_last_name', 'ASC')->get();

        $data['receivers'] = $receivers;

        return response()->json($data);
    }

    public function getReceiverPaginate(Request $request) {
        $receivers = Receivers::with('address', 'address.address_type')->orderBy('receiver_last_name', 'ASC')->paginate(10);

        $data['receivers'] = $receivers;

        return response()->json($data);
    }

    public function updateReceiver(Request $request) {
        $receiver = Receivers::find($request['receiver']['id']);
        $address = Addresses::find($request['receiver']['address']['id']);

        $receiver->receiver_first_name = $request['receiver']['receiver_first_name'];
        $receiver->receiver_middle_name = $request['receiver']['receiver_middle_name'];
        $receiver->receiver_last_name = $request['receiver']['receiver_last_name'];
        $receiver->receiver_email = $request['receiver']['receiver_email'];
        $receiver->receiver_phone = $request['receiver']['receiver_phone'];
        $receiver->save();

        $address->address_type_id = $request['receiver']['address']['address_type_id'];
        $address->address_no_or_house_building_no = $request['receiver']['address']['address_no_or_house_building_no'];
        $address->address_st = $request['receiver']['address']['address_st'];
        $address->address_city = $request['receiver']['address']['address_city'];
        $address->address_state = $request['receiver']['address']['address_state'];
        $address->address_country = $request['receiver']['address']['address_country'];
        $address->address_post_code = $request['receiver']['address']['address_post_code'];
        $address->save();

        $data['heading'] = 'Receiver Details Updated';
        $data['isSuccess'] = true;
        $data['message'] = " Receiver details was successfully updated";

        return response()->json($data);
    }

    public function removeReceiver(Request $request) {
        $receiver = Receivers::find($request['receiver']['id']);

        $count = $receiver->purchase_order()->count();

        if($count) {
            $data['heading'] = 'Receiver Restricted';
            $data['isSuccess'] = false;
            $data['message'] = " Receiver cannot be remove because it has purchase order transactions";
        } else {
            $receiver->address()->delete();
            $receiver->delete();

            $data['heading'] = 'Receiver Removed';
            $data['isSuccess'] = true;
            $data['message'] = " Receiver was successfully removed";
        }

        return response()->json($data);
    }
}
