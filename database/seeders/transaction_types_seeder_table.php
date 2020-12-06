<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\TransactionTypes;

class transaction_types_seeder_table extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            ['transaction_type_name' => 'Purchase Order'],
            ['transaction_type_name' => 'Sales Order'],
            ['transaction_type_name' => 'Warranty Validation'],
            ['transaction_type_name' => 'RMA']
        ];

        TransactionTypes::insert($types);
    }
}
