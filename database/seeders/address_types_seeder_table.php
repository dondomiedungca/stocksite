<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\AddressTypes;

class address_types_seeder_table extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            ['address_type_name' => 'Home Address'],
            ['address_type_name' => 'Billing Address'],
            ['address_type_name' => 'Company Office Address'],
            ['address_type_name' => 'Shipping Address']
        ];

        AddressTypes::insert($types);
    }
}
