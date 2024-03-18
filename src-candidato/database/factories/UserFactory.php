<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Support\Str;
use App\Models\User\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'              => $this->faker->name,
            'email'             => $this->faker->unique()->freeEmail,
            'cpf'               => $this->faker->unique()->cpf,
            'rg'                => Str::random(5),
            'rg_emmitter'       => Str::random(10),
            'social_name'       => $this->faker->firstName(),
            'mother_name'       => $this->faker->name,
            'email_verified_at' => now(),
            'password'          => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token'    => Str::random(10),
            'birth_date'        => $this->faker->date,      
            'is_foreign'        => 0,
            'nationality'       => null      
        ];
    }
}
