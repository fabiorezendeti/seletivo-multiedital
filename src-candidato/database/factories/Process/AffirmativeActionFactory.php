<?php

namespace Database\Factories\Process;

use App\Models\Process\AffirmativeAction;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AffirmativeActionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AffirmativeAction::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'description'   => $this->faker->text(400),
            'slug'          => $this->faker->unique()->word . microtime(),
            'is_wide_competition'  => random_int(0,1),
            'is_ppi'    => random_int(0,1),
            'classification_priority'   => random_int(30,40)
        ];
    }
}