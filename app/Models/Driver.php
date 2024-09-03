<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
   
    use HasFactory;

    protected $fillable = ['full_name', 'address','contact'];

    public function fuelLogs()
    {
        return $this->hasMany(FuelLog::class);
    }
}
