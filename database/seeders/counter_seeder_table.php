<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Counter;

class counter_seeder_table extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Counter::create([
            'prefix' => "PO - ",
            'counter' => 0
        ]);
        Counter::create([
            'prefix' => "SI - ",
            'counter' => 0
        ]);
        Counter::create([
            'prefix' => "IN - ",
            'counter' => 0
        ]);
        Counter::create([
            'prefix' => "DR - ",
            'counter' => 0
        ]);
        Counter::create([
            'prefix' => "WARRANTY - ",
            'counter' => 0
        ]);
        Counter::create([
            'prefix' => "RMA - ",
            'counter' => 0
        ]);
    }
}
