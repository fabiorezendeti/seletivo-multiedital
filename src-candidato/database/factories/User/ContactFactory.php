<?php

namespace Database\Factories\User;

use App\Models\Address\City;
use App\Models\User\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ContactFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Contact::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'street'                => $this->faker->streetName,
            'number'               => $this->faker->randomNumber(5),
            'district'             => Str::random(8),
            'zip_code'             => '89000-000',
            'city_id'               => City::inRandomOrder()->first()->id,
            'phone_number'          => '(49)99999-0000',
            'alternative_phone_number'  => null,
            'has_whatsapp' => true,
            'has_telegram' => false
        ];
    }
}
