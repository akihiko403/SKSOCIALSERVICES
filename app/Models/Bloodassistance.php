<?php
namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bloodassistance extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'bloodassistance';
    protected static function boot()
    {
        parent::boot();
   // Event for setting the approved_by field
        static::creating(function ($model) {
           if (Auth::check()) {
                $model->encodedby = Auth::id(); // Set the current user's ID
            }
        });

       // Event for calculating the total amount before saving
        static::saving(function ($model) {
            $model->total_amount = $model->qty * $model->unit_price;
       });

       static::saving(function ($model) {
        if ($model->birthdate) {
            // Get current date and time
            $now = new \DateTime();
            
            // Get date of birth from the model
            $dob = new \DateTime($model->birthdate);
            
            // Calculate age
            $age = $now->diff($dob)->y;
            
            // Set age on the model
            $model->age = $age;
        }
    });
    }

    protected $fillable = [
        'status',
        'agency',
        'control_no',
        'date',
        'referral',
        'lastname',
        'firstname',
        'middlename',
        'ext',
        'age',
        'birthdate',
        'municipality',
        'diagnosis',
        'hospital',
        'blood_type',
        'qty',
        'unit_price',
        'total_amount',
        'encodedby',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'encodedby');
    }
}
