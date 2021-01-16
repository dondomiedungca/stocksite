<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\CustomerTypes;

class customer_types_seeder_table extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CustomerTypes::create([
            'customer_type_name' => 'Walk-in'
        ]);
        CustomerTypes::create([
            'customer_type_name' => 'Company'
        ]);
        CustomerTypes::create([
            'customer_type_name' => 'Partnership'
        ]);
    }
}
