<?php

namespace Database\Seeders;

use App\Models\SportsHall;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SportsHallSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SportsHall::create([
            'name' => 'X',
            'opening_time' => '10:00:00',
            'closing_time' => '21:00:00',
        ]);

        SportsHall::create([
            'name' => 'Y',
            'opening_time' => '12:00:00',
            'closing_time' => '22:00:00',
        ]);
    }
}
