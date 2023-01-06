<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    protected $primaryKey = 'customer_id';
    
    protected $fillable = [
        'email', 'password', 'token'
    ];

    protected $hidden=['password'];
}