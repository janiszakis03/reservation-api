<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;
    protected $fillable = ['sports_hall_id', 'start_time', 'end_time'];

    // Relācija uz sporta centru
    public function sportCenter()
    {
        return $this->belongsTo(SportsHall::class);
    }
}
