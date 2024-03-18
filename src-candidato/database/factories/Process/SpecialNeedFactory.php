<?php

namespace Database\Factories\Process;

use App\Models\Process\SpecialNeed;
use Illuminate\Database\Eloquent\Factories\Factory;

class SpecialNeedFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SpecialNeed::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return  [
            'description' => $this->faker->text(100),
            'activated' => random_int(0,1),
            'require_details' => random_int(0,1)
        ];
    }
}
