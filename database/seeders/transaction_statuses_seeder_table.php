<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\TransactionStatuses;

class transaction_statuses_seeder_table extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            ['transaction_status_name' => 'Unfulfilled'],
            ['transaction_status_name' => 'Fulfilled'],
            ['transaction_status_name' => 'RMA'],
            ['transaction_status_name' => 'Warranty Validation'],
            ['transaction_status_name' => 'Open RMA'],
            ['transaction_status_name' => 'Closed RMA'],
            ['transaction_status_name' => 'Pending'],
            ['transaction_status_name' => 'Cancelled']
        ];

        TransactionStatuses::insert($statuses);
    }
}
