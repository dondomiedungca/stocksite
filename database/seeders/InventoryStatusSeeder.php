<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\InventoryStatus;

class InventoryStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $status = [
            [
                'name' => 'Available',
            ],
            [
                'name' => 'Sold',
            ],
            [
                'name' => 'Defective',
            ],
            [
                'name' => 'Returned',
            ],
        ];

        InventoryStatus::insert($status);
    }
}
