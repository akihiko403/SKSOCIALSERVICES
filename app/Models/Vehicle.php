<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    
    use HasFactory;

    protected $fillable = ['unit', 'plate_no'];

    public function fuelLogs()
    {
        return $this->hasMany(FuelLog::class);
    }
}
