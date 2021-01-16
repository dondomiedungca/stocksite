<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Manufacturer;

class manufacturer_seeder_table extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $manufacturers = [
            ['manufacturer_type_name' => 'Foods'],
            ['manufacturer_type_name' => 'Mobile Parts'],
            ['manufacturer_type_name' => 'Vehicle Parts'],
            ['manufacturer_type_name' => 'Printer Parts'],
            ['manufacturer_type_name' => 'Appliances'],
            ['manufacturer_type_name' => 'Tools'],
            ['manufacturer_type_name' => 'Construction Equipments'],
            ['manufacturer_type_name' => 'Health Equipments'],
            ['manufacturer_type_name' => 'Electrical'],
            ['manufacturer_type_name' => 'Computer Supplies'],
            ['manufacturer_type_name' => 'School Supplies'],
            ['manufacturer_type_name' => 'Furnitures'],
            ['manufacturer_type_name' => 'Clothes'],
            ['manufacturer_type_name' => 'Plastic Materials'],
            ['manufacturer_type_name' => 'Others']
        ];

        Manufacturer::insert($manufacturers);
    }
}
