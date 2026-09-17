<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consulting extends Model
{
    // Point to the legacy table
    protected $table = 'consulting';
    
    // Disable Laravel's default timestamps if your legacy table doesn't have created_at/updated_at
    public $timestamps = false; 

    protected $fillable = [
        'date', 'time', 'ward', 'bed', 'patient_name', 'mrn', 
        'consult_info', 'medstatus', 'status', 'doc', 'special_request'
    ];
}