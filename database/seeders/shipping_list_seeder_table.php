<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\ShippingList;

class shipping_list_seeder_table extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ShippingList::create([
            'shipping_type' => 'Land',
            'shipping_dealer_name' => 'LBC',
            'shipping_amount' => 100
        ]);
        ShippingList::create([
            'shipping_type' => 'Air',
            'shipping_dealer_name' => 'LBC',
            'shipping_amount' => 500
        ]);
        ShippingList::create([
            'shipping_type' => 'Sea',
            'shipping_dealer_name' => 'LBC',
            'shipping_amount' => 300
        ]);
    }
}
