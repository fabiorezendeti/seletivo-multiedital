<?php

namespace Database\Factories\Course;

use App\Models\Course\Course;
use App\Models\Course\Modality;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CourseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Course::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'  => $this->faker->sentence($numberWords = 4),
            'modality_id' => Modality::inRandomOrder()->first()->id
        ];
    }
}
