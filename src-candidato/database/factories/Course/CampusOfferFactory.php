<?php

namespace Database\Factories\Course;

use App\Models\Course\CampusOffer;
use App\Models\Course\Course;
use App\Models\Course\Shift;
use App\Models\Organization\Campus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CampusOfferFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CampusOffer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [ 
            'course_id' => Course::factory(),
            'course_shift_id' => Shift::inRandomOrder()->first(),
            'campus_id' => Campus::factory(),
            'website'   => $this->faker->url
        ];
    }
}