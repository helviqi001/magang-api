<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Privilege extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'role_id',
        'menu_item_id',
        'view',
        'add',
        'edit',
        'delete',
        'other'
    ];

    protected $primaryKey = 'privilege_id';

    public function scopeOfSelect($query)
    {
        return $query->select('privilege_id', 'role_id', 'menu_item_id', 'view', 'add', 'edit', 'delete', 'other', 'created_at', 'updated_at');
    }

    public function Menus()
    {
        return $this->belongsTo(MenuItem::class, 'menu_item_id', 'menu_item_id');
    }

    public function Roles()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }
}
