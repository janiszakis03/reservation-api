<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SportsHall extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'opening_time', 'closing_time'];
}
