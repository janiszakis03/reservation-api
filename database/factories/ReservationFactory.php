<?php

namespace Database\Factories;

use App\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    protected $model = Reservation::class;

    public function definition()
    {
        $start_time = Carbon::now()->addHours($this->faker->numberBetween(1, 24));
        $end_time = $start_time->copy()->addHours(1)->toTimeString();

        $start_time = $start_time->toTimeString();

        return [
            'sports_hall_id' => function () {
                return \App\Models\SportsHall::factory()->create()->id;
            },
            'start_time' => $start_time,
            'end_time' => $end_time,
        ];
    }
}
