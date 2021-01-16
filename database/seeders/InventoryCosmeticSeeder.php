<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\InventoryCosmetic;

class InventoryCosmeticSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cosmetics = [
            [
                'name' => 'Good Item',
            ],
            [
                'name' => 'With Dents',
            ],
            [
                'name' => 'Parts Problem',
            ],
            [
                'name' => 'Expired',
            ],
            [
                'name' => 'Parts Incomplete'
            ],
            [
                'name' => 'Parts Incompatible'
            ]
        ];

        InventoryCosmetic::insert($cosmetics);
    }
}
