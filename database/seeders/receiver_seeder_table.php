<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Receiver;

class receiver_seeder_table extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $suppliers = Receiver::factory(10)->create();
    }
}
