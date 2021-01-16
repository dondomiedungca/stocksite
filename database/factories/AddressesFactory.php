<?php

namespace Database\Factories;

use App\Models\Addresses;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Addresses::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'address_type_id' => 3,
            'address_no_or_house_building_no' => $this->faker->buildingNumber,
            'address_st' => $this->faker->streetName,
            'address_city' => $this->faker->city,
            'address_state' => $this->faker->state,
            'address_country' => $this->faker->country,
            'address_post_code' => $this->faker->postcode
        ];
    }
}
