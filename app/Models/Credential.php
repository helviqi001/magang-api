<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Credential extends Model
{
    protected $primaryKey = 'credential_id';

    protected $fillable = [
        'client_key',
        'secret_key',
        'platform',
        'type',
    ];
}
