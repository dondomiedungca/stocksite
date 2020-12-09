<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Company;
use App\Models\Addresses;

class company_seeder_table extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $address = new Addresses();
        $address->address_type_id = 3;
        $address->address_no_or_house_building_no = '4328 G/F Alpha EOS Bldg.';
        $address->address_st = 'Montojo St.';
        $address->address_city = 'Sta Cruz';
        $address->address_state = 'Makati';
        $address->address_country = 'Philippines';
        $address->address_post_code = '1234';
        $address->save();

        Company::create([
            'company_name' => 'Pro Prints Enterprise',
            'company_email' => 'proprints.enterprise@proprintsenterprise.com',
            'company_phone' => '(02) 8851-8752',
            'employer_id' => '1111-2222-3333',
            'tin_number' => '1111-2222-3333',
            'address_id' => $address->id,
            'currency_id' => 82 // PHP Philippines' currency
        ]);
    }
}
