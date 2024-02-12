<?php

namespace Tests\Feature;

use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReservationCancellationTest extends TestCase
{
    /** @test */
    public function user_can_cancel_reservation()
    {
        // Izveidojam rezervāciju
        $reservation = Reservation::factory()->create();

        // Nosūtam DELETE pieprasījumu, lai atceltu rezervāciju
        $response = $this->deleteJson("/api/reservations/{$reservation->id}");

        // Pārbaudam, ka rezervācija ir atcelta un atgriezta JSON atbildē
        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Reservation cancelled successfully.',
                 ]);

        // Pārbaudam, ka rezervācija vairs nepastāv datubāzē
        $this->assertDatabaseMissing('reservations', [
            'id' => $reservation->id,
        ]);
    }

    /** @test */
    public function cancelling_non_existent_reservation_returns_error()
    {
        // Nosūtam DELETE pieprasījumu ar nepastāvošas rezervācijas ID
        $response = $this->deleteJson("/api/reservations/999");

        // Pārbaudam, ka tiek atgriezta kļūdas ziņa
        $response->assertStatus(404)
                 ->assertJson([
                     'error' => 'Reservation not found.',
                 ]);
    }
}
