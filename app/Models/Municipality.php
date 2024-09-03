<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Municipality extends Model
{
    use HasFactory;
    protected $table = 'table_municipality';
    protected $fillable = [
        'municipality_name',
        'province_id',
    ];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }
}
