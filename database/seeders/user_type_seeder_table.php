<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserType;

class user_type_seeder_table extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserType::create([
            'name' => 'Administrator'
        ]);
        UserType::create([
            'name' => 'Full Access'
        ]);
        UserType::create([
            'name' => 'Limited Access'
        ]);
    }
}
