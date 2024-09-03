<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Crmcmedical extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'CRMCMEDICAL';

    protected $fillable = [
        'status',
        'date',
        'referral',
        'lastname',
        'firstname',
        'middlename',
        'ext',
        'diagnosis',
        'age',
        'birthdate',
        'municipality',
        'province',
        'patient_status',
        'transaction_status',
        'amount',
        'encodedby'
    ];
    protected static function boot()
    {
        parent::boot();
   // Event for setting the approved_by field

       // Event for calculating the total amount before saving
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
            parent::boot();
   // Event for setting the approved_by field
        static::creating(function ($model) {
           if (Auth::check()) {
                $model->encodedby = Auth::id(); // Set the current user's ID
            }
        });
        }
    });
    
    }
    public function municipalities()
    {
        return $this->hasMany(Municipality::class);
    }
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'encodedby');
    }
    
}
