<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Suppliers;

class supplier_seeder_table extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $suppliers = Suppliers::factory(10)->create();
    }
}
