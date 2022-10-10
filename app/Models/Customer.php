<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $primaryKey = 'customer_id';

    protected $fillable = [
        'name',
        'email',
        'no_telp',
        'password',
    ];

    protected $hidden=['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

}
