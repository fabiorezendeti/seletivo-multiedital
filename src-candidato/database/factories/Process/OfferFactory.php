<?php

namespace Database\Factories\Process;

use App\Models\Course\CampusOffer;
use App\Models\Process\Notice;
use App\Models\Process\Offer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OfferFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Offer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'notice_id' => Notice::inRandomOrder()->first()->id,
            'course_campus_offer_id' => CampusOffer::inRandomOrder()->limit(1)->first()->id,
            'total_vacancies'=> random_int(20,70)
        ];
    }
}
