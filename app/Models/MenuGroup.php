<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenuGroup extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'sequence',
        'icon',
        'platform',
        'status'
    ];

    protected $primaryKey = 'menu_group_id';

    public function scopeOfSelect($query)
    {
        return $query->select('menu_group_id', 'name', 'sequence', 'icon', 'platform', 'status', 'created_at', 'updated_at');
    }

    public function scopeFilter($query, $filter)
    {
        foreach ($filter as $key => $value) {
            if (!empty($value)) {
                switch ($key) {
                    case 'keyword':
                        $query->where(function ($query2) use ($value) {
                            $query2->where('menu_group_name', 'like', '%' . $value . '%');
                        });
                        break;
                    case 'menu_group_id':
                        if (is_array($value)) {
                            $query->whereIn('menu_group_id', $value);
                        } else {
                            $query->where('menu_group_id', $value);
                        }
                        break;
                }
            }
        }
        return $query;
    }

    public function Menus()
    {
        return $this->hasMany(MenuItem::class, 'menu_group_id', 'menu_group_id');
    }
}
