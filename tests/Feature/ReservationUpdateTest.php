<?php

namespace Tests\Feature;

use App\Models\Reservation;
use App\Models\SportsHall;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ReservationUpdateTest extends TestCase
{
    /** @test */
    public function user_can_update_reservation_time_slot()
    {
        // Izveidojam jaunu rezervāciju
        $sportsHall = SportsHall::factory()->create();
        $reservation = Reservation::factory()->create([
            'sports_hall_id' => $sportsHall->id,
            'start_time' => Carbon::createFromFormat('H:i:s', $sportsHall->opening_time)->addHours(3)->toTimeString(),
            'end_time' => Carbon::createFromFormat('H:i:s', $sportsHall->closing_time)->addHours(4)->toTimeString()
        ]);

        // Jaunie laika dati rezervācijai
        $newStartTime = Carbon::createFromFormat('H:i:s', $sportsHall->opening_time)->addHours(1)->toTimeString();
        $newEndTime = Carbon::createFromFormat('H:i:s', $sportsHall->closing_time)->addHours(2)->toTimeString();

        // Nosūtam PUT pieprasījumu, lai atjaunotu rezervācijas laika slotu
        $response = $this->putJson("/api/reservations/{$reservation->id}", [
            'start_time' => $newStartTime,
            'end_time' => $newEndTime,
        ]);

        // Pārbaudam, ka atjaunošana norisinājās veiksmīgi
        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Reservation time slot updated successfully.',
                 ]);

        // Pārbaudam, vai rezervācijas laika slotu patiešām atjaunoja
        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'start_time' => $newStartTime,
            'end_time' => $newEndTime,
        ]);
    }

    /** @test */
    public function user_cannot_update_reservation_with_unavailable_time_slot()
    {
        // Izveidojam divas rezervācijas, kas pārklājas
        $reservation = Reservation::factory()->create([
            'start_time' => '12:00:00',
            'end_time' => '14:00:00',
        ]);

        Reservation::factory()->create([
            'sports_hall_id' => $reservation->sports_hall_id,
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
        ]);

        // Mēģinam atjaunot rezervācijas laika slotu ar pārklājošiem laika datiem
        $response = $this->putJson("/api/reservations/{$reservation->id}", [
            'start_time' => '11:00:00',
            'end_time' => '13:00:00',
        ]);

        // Pārbaudam, ka atgriežas kļūdas ziņojums
        $response->assertStatus(422)
                 ->assertJson([
                     'error' => 'The new time slot is not available.',
                 ]);
    }
}
