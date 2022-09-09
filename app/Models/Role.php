<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'created_by',
        'updated_by'
    ];

    protected $primaryKey = 'role_id';
    public function privileges()
    {
        return $this->hasMany(Privilege::class, 'role_id', 'role_id');
    }
    public function scopeOfSelect($query)
    {
        return $query->select('role_id', 'name', 'description', 'created_at', 'updated_at');
    }

    public function scopeFilter($query, $filter)
    {
        foreach ($filter as $key => $value) {
            if (!empty($value)) {
                switch ($key) {
                    case 'keyword':
                        $query->where(function ($query2) use ($value) {
                            $query2->where('name', 'like', '%' . $value . '%');
                        });
                        break;
                    case 'role_id':
                        if (is_array($value)) {
                            $query->whereIn('role_id', $value);
                        } else {
                            $query->where('role_id', $value);
                        }
                        break;
                }
            }
        }
        return $query;
    }
}
