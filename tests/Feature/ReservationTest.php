<?php

namespace Tests\Feature;

use App\Models\Reservation;
use App\Models\SportsHall;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    /** @test */
    public function user_can_create_reservation()
    {
        // Izveidojam sporta centru
        $sportsHall = SportsHall::factory()->create();

        // Nosakām rezervācijas laika slotu
        $startTime = Carbon::now()->addHours(1)->toTimeString();
        $endTime = Carbon::now()->addHours(2)->toTimeString();

        // Nosūtam POST pieprasījumu, lai izveidotu rezervāciju
        $response = $this->postJson('/api/reservations', [
            'sports_hall_id' => $sportsHall->id,
            'start_time' => $startTime,
            'end_time' => $endTime,
        ]);

        // Pārbaudam, ka rezervācija ir izveidota un atgriezta JSON atbildē
        $response->assertStatus(201)
                 ->assertJson([
                     'message' => 'Reservation created successfully',
                 ]);

        // Pārbaudam, ka rezervācija tiek saglabāta datubāzē
        $this->assertDatabaseHas('reservations', [
            'sports_hall_id' => $sportsHall->id,
            'start_time' => $startTime,
            'end_time' => $endTime,
        ]);
    }

    /** @test */
    public function cannot_create_reservation_for_occupied_slot()
    {
        // Izveidojam sporta centru un rezervāciju
        $reservation = Reservation::factory()->create();

        // Mēģina izveidot rezervāciju ar pārklājošu laika slotu
        $response = $this->postJson('/api/reservations', [
            'sports_hall_id' => $reservation->sports_hall_id,
            'start_time' => $reservation->start_time,
            'end_time' => $reservation->end_time,
        ]);

        // Pārbaudam, ka tiek atgriezta kļūdas ziņa
        $response->assertStatus(422)
                 ->assertJson([
                     'error' => 'The selected time slot is not available.',
                 ]);
    }
}
