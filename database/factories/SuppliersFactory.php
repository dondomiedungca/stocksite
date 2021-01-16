<?php

namespace Database\Factories;

use App\Models\Suppliers;
use App\Models\Addresses;
use Illuminate\Database\Eloquent\Factories\Factory;

class SuppliersFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Suppliers::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'address_id' => Addresses::factory(),
            'supplier_name' => $this->faker->company,
            'manufacturer_id' => 4,
            'supplier_email' => $this->faker->unique()->companyEmail,
            'supplier_phone_number' => $this->faker->tollFreePhoneNumber,
        ];
    }
}
