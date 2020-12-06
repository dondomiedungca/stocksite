<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\PaymentTerms;

class payment_terms_seeder_table extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaymentTerms::create([
            'payment_term_name' => '10 Days',
            'no_of_days' => 10
        ]);
        PaymentTerms::create([
            'payment_term_name' => '30 Days',
            'no_of_days' => 30
        ]);
        PaymentTerms::create([
            'payment_term_name' => '60 Days',
            'no_of_days' => 60
        ]);
    }
}
