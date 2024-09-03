<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FuelLog extends Model
{
   
    use HasFactory;

    protected static function boot()
    {
        parent::boot();
   // Event for setting the approved_by field
        static::creating(function ($model) {
           if (Auth::check()) {
                $model->approved_by = Auth::id(); // Set the current user's ID
            }
        });

       // Event for calculating the total amount before saving
        static::saving(function ($model) {
            $model->total_amount = $model->actual_purchase_liters * $model->price_per_liter;
       });
    }

    protected $fillable = [
        'date', 'driver_id', 'vehicle_id', 'products', 'liters_requested',
        'actual_purchase_liters', 'price_per_liter', 'total_amount', 'trip',
        'purpose', 'approved_by', 'remarks', 'receipt'
    ];

    // Relationships
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
