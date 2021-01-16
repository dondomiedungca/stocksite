<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Receivers;

class receiver_seeder_table extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $suppliers = Receivers::factory(10)->create();
    }
}
