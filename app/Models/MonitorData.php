<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonitorData extends Model
{
    // Point to your exact legacy data table
    protected $table = 'monitor_records'; 

    // Define your custom primary key
    protected $primaryKey = 'record_id';

    // Disable timestamps if they do not exist in the legacy table
    public $timestamps = false;

    // Allow all columns to be mass-assigned (or list them explicitly)
    protected $guarded = []; 
}