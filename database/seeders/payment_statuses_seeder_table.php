<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\PaymentStatuses;

class payment_statuses_seeder_table extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaymentStatuses::create([
            'payment_status_name' => 'Unpaid'
        ]);
        PaymentStatuses::create([
            'payment_status_name' => 'Partial Payment'
        ]);
        PaymentStatuses::create([
            'payment_status_name' => 'Paid'
        ]);
    }
}
