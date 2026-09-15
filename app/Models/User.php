<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    // 1. Tell Laravel the correct table name
    protected $table = 'userlist';

    // 2. Tell Laravel the primary key is login_id, not id
    protected $primaryKey = 'login_id';

    // 3. Disable standard created_at/updated_at timestamps
    public $timestamps = false; 

    // 4. Allow these columns to be filled during SSO login/creation
    protected $fillable = [
        'login_username',
        'name',
        'login_pwd',
        'role',
        'login_stamp'
    ];

    // Tell Laravel which column is used for the password
    public function getAuthPassword()
    {
        return $this->login_pwd;
    }
}