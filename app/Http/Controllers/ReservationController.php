<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReservationController extends Controller
{
    public function store(Request $request)
    {
        // Validējam ienākošos datus
        $validator = Validator::make($request->all(), [
            'sports_hall_id' => 'required|exists:sports_halls,id',
            'start_time' => 'required|date_format:H:i:s',
            'end_time' => 'required|date_format:H:i:s|after:start_time',
        ]);

        // Ja validācija neizdodas, atgriežam kļūdu
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // Pārbaudam, vai laika slots ir brīvs
        $isSlotAvailable = Reservation::where('sports_hall_id', $request->sports_hall_id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                      ->orWhereBetween('end_time', [$request->start_time, $request->end_time]);
            })
            ->doesntExist();

        // Ja laika slots nav brīvs, atgriežam kļūdu
        if (!$isSlotAvailable) {
            return response()->json(['error' => 'The selected time slot is not available.'], 422);
        }

        // Izveidojam jaunu rezervāciju
        $reservation = Reservation::create([
            'sports_hall_id' => $request->sports_hall_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        // Atgriežam veiksmīgu rezervācijas izveidošanu
        return response()->json(['message' => 'Reservation created successfully', 'reservation' => $reservation], 201);
    }

    public function cancel(Request $request, $id)
    {
        $reservation = Reservation::find($id);

        // Pārbaudam, vai rezervācija eksistē
        if (!$reservation) {
            return response()->json(['error' => 'Reservation not found.'], 404);
        }

        // Atcelšana rezervācijas
        $reservation->delete();

        return response()->json(['message' => 'Reservation cancelled successfully.'], 200);
    }

    public function update(Request $request, $id)
    {
        // Atrodam rezervāciju pēc ID
        $reservation = Reservation::find($id);

        // Pārbaudam, vai rezervācija eksistē
        if (!$reservation) {
            return response()->json(['error' => 'Reservation not found.'], 404);
        }

        // Validācija ievades datiem
        $validator = Validator::make($request->all(), [
            'start_time' => 'required|date_format:H:i:s',
            'end_time' => 'required|date_format:H:i:s|after:start_time',
        ]);

        // Ja validācija neizdodas, atgriežam kļūdas ziņojumus
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // Jauni laika dati no pieprasījuma
        $newStartTime = $request->input('start_time');
        $newEndTime = $request->input('end_time');

        // Pārbaudam, vai jaunais laika slots ir brīvs
        $isSlotAvailable = Reservation::where('sports_hall_id', $reservation->sports_hall_id)
        ->where('id', '!=', $id)
        ->where(function ($query) use ($newStartTime, $newEndTime) {
            $query->whereBetween('start_time', [$newStartTime, $newEndTime])
                ->orWhereBetween('end_time', [$newStartTime, $newEndTime]);
        })->doesntExist();

        // Ja jaunais laika slots nav brīvs, atgriežam kļūdas ziņojumu
        if (!$isSlotAvailable) {
            return response()->json(['error' => 'The new time slot is not available.'], 422);
        }

        // Atjaunojam rezervācijas laika slotu ar jauniem datiem
        $reservation->update([
            'start_time' => $newStartTime,
            'end_time' => $newEndTime,
        ]);

        return response()->json(['message' => 'Reservation time slot updated successfully.'], 200);
    }
}
