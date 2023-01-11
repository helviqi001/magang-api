<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class Customer extends Authenticatable 
{
    use HasFactory, SoftDeletes, Notifiable;

    protected $primaryKey = 'customer_id';

    protected $fillable = [
        'name',
        'email',
        'no_telp',
        'password',
        'otp',
        'otp_verify',
    ];

    protected $hidden=['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

}
