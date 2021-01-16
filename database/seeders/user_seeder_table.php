<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Carbon\Carbon;
use Hash;

class user_seeder_table extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $password = Hash::make('password');

        $users = array(
                [
                    'name' => 'Dondomie',
                    'first_name' => 'Dondomie',
                    'middle_name' => '',
                    'last_name' => 'Dungca',
                    'email' => 'dondomiedungca14@gmail.com',
                    'password' => $password,
                    'user_type' => 1,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'email_verified_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ]
        );

        User::insert($users);
    }
}
