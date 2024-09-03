<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AddressCityMun extends Model
{
    // Specify the table if it does not follow Laravel's naming conventions
    protected $table = 'table_municipality';

    // Specify the primary key if it's not 'id'
    protected $primaryKey = 'id';
    
    // If your table doesn't have timestamps columns, disable timestamps
    public $timestamps = false;
}
