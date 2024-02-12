<?php

namespace Database\Factories;

use App\Models\SportsHall;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SportsHall>
 */
class SportsHallFactory extends Factory
{
    protected $model = SportsHall::class;

    public function definition()
    {
        $openingTime = Carbon::now()->startOfHour()->addHours($this->faker->numberBetween(1, 24))->toTimeString();
        $closingTime = Carbon::createFromFormat('H:i:s', $openingTime)->addHours(8)->format('H:i:s');

        return [
            'name' => $this->faker->company,
            'opening_time' => $openingTime,
            'closing_time' => $closingTime,
        ];
    }
}
