<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    // 1. Point to your legacy data table
    protected $table = 'patientlist';

    // 2. Define the custom primary key
    protected $primaryKey = 'no';

    // 3. Disable Laravel's default timestamps 
    // (Your database handles 'patient_stamp' automatically)
    public $timestamps = false;

    // 4. Define which columns can be safely mass-assigned
    protected $fillable = [
        'date',
        'time',
        'ward',
        'patient_name',
        'mrn',
        'total_item',
        'total_item2',
        'supply',
        'status',
        'remarks',
        'takenby',
        'statusready',
        'statuscollected'
    ];
}