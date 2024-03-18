<?php

namespace Database\Factories\Course;

use App\Models\Course\Modality;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ModalityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Modality::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'description'   => $this->faker->unique()->word
        ];
    }
}
