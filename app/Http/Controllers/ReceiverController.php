<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Receiver;
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

        $receiver = new Receiver();
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
        $receivers = Receiver::with('address', 'address.address_type')->orderBy('receiver_last_name', 'ASC')->get();

        $data['receivers'] = $receivers;

        return response()->json($data);
    }
}
