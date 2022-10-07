<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class User extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'name',
        'email',
        'username',
        'avatar',
        'email_verified_at',
        'birth_date',
        'password',
        'role_id'
    ];

    protected $hidden = [
        'password'
    ];

    public function scopeOfSelect($query)
    {
        return $query->select('user_id', 'name', 'email', 'username', 'avatar', 'birth_date','role_id', 'created_at', 'updated_at');
    }

    public function scopeFilter($query, $filter)
    {
        foreach ($filter as $key => $value) {
            if (!empty($value)) {
                switch ($key) {
                    case 'keyword':
                        $query->where(function ($query2) use ($value) {
                            $query2->where('name', 'like', '%' . $value . '%')
                                ->orWhere('email', 'like', '%' . $value . '%')
                                ->orWhere('username', 'like', '%' . $value . '%')
                                ->orWhere('birth_date', 'like', '%' . $value . '%');
                        });
                        break;
                    case 'user_id':
                        if (is_array($value)) {
                            $query->whereIn('user_id', $value);
                        } else {
                            $query->where('user_id', $value);
                        }
                        break;
                }
            }
        }

        return $query;
    }

    public function role()
    {
        return $this->hasOne(Role::class, 'role_id', 'role_id');
    }
}
