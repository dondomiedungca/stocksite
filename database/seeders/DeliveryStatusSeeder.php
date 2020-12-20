<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\DeliveryStatus;

class DeliveryStatusSeeder extends Seeder
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
                'name' => 'For Packaging',
            ],
            [
                'name' => 'In-Transit',
            ],
            [
                'name' => 'Partial Delivery',
            ],
            [
                'name' => 'Delivered',
            ]
        ];

        DeliveryStatus::insert($statuses);
    }
}
