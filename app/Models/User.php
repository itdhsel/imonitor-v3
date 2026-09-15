<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // 1. Point to your legacy database table
    protected $table = 'userlist';

    // 2. Define your custom primary key
    protected $primaryKey = 'login_id';

    // 3. Disable timestamps since created_at/updated_at do not exist
    public $timestamps = false;

    // 4. Map the columns
    protected $fillable = [
        'login_username',
        'name',
        'login_pwd',
        'current_session_id', 
    ];

    // 5. Tell Laravel which column stores the password
    public function getAuthPasswordName()
    {
        return 'login_pwd';
    }
}