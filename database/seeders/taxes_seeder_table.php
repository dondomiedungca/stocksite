<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Taxes;

class taxes_seeder_table extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Taxes::create([
            'tax_name' => 'No Tax',
            'tax_percentage' => '0%',
            'tax_value' => 0.00
        ]);
        Taxes::create([
            'tax_name' => 'Regular Tax',
            'tax_percentage' => '10%',
            'tax_value' => 0.10
        ]);
        Taxes::create([
            'tax_name' => 'Premium Tax',
            'tax_percentage' => '20%',
            'tax_value' => 0.20
        ]);
    }
}
