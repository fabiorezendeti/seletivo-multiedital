<?php

namespace Database\Factories\Organization;

use App\Models\Address\City;
use App\Models\Organization\Campus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;

class CampusFactory extends Factory
{

    use WithFaker;
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Campus::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */


    public function definition()
    {
        return [
            'name'  => $this->faker->unique()->company,
            'email' => $this->faker->email,
            'site'  => $this->faker->url,
            'street' => $this->faker->streetName,
            'number' => random_int(3,5),
            'district' => $this->faker->region,
            'zip_code' => $this->faker->postcode,
            'phone_number' => $this->faker->phoneNumber,
            'city_id'  => City::inRandomOrder()->first()->id
        ];
    }
}
