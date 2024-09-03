<?php
namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transpoassistance extends Model
{
    use HasFactory,SoftDeletes;

    // Define the table name (if different from the default)
    protected $table = 'transpoassistance';

    // Define the fields that can be mass assigned
    protected $fillable = [
        'status',
        'referral_date',
        'referral',
        'lastname',
        'firstname',
        'middlename',
        'ext',
        'municipality',
        'age',
        'diagnosis_cause_of_death',
        'pick_up_point',
        'drop_point',
        'unit',
        'name_of_driver',
        'remarks',
        'encodedby',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'encodedby');
    }
    protected static function boot()
    {
        parent::boot();
   // Event for setting the approved_by field
        static::creating(function ($model) {
           if (Auth::check()) {
                $model->encodedby = Auth::id(); // Set the current user's ID
            }
        });
    }
    // Define any relationships if necessary (e.g., belongsTo, hasMany)
}
