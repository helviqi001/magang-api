<?php

namespace Database\Seeders;

use App\Models\MenuGroup;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuItemSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'Manage Admin',
                'url' => '/admin',
                'menu_group_id' => MenuGroup::where('name' , '=', 'Admin')->first()->menu_group_id,
                'sequence' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Role',
                'url' => '/role',
                'menu_group_id' => MenuGroup::where('name' , '=', 'Admin')->first()->menu_group_id,
                'sequence' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        MenuItem::insert($data);
    }
}
