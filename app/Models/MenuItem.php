<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenuItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'url',
        'sequence',
        'menu_group_id'
    ];

    protected $primaryKey = 'menu_item_id';

    public function scopeOfSelect($query)
    {
        return $query->select('menu_item_id', 'name', 'url', 'sequence', 'menu_group_id', 'created_at', 'updated_at');
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
                    case 'menu_item_id':
                        if (is_array($value)) {
                            $query->whereIn('menu_item_id', $value);
                        } else {
                            $query->where('menu_item_id', $value);
                        }
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
}
