<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(user_seeder_table::class);
        $this->call(user_type_seeder_table::class);
        $this->call(counter_seeder_table::class);
        $this->call(customer_types_seeder_table::class);
        $this->call(manufacturer_seeder_table::class);
        $this->call(payment_statuses_seeder_table::class);
        $this->call(payment_terms_seeder_table::class);
        $this->call(shipping_list_seeder_table::class);
        $this->call(taxes_seeder_table::class);
        $this->call(transaction_statuses_seeder_table::class);
        $this->call(transaction_types_seeder_table::class);
        $this->call(address_types_seeder_table::class);
    }
}
