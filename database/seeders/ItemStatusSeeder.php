<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\ItemStatus;

class ItemStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            [
                'name' => 'No Items Receive Yet',
            ],
            [
                'name' => 'Incomplete',
            ],
            [
                'name' => 'Complete',
            ]
        ];

        ItemStatus::insert($statuses);
    }
}
