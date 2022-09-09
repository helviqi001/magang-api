<?php

namespace Database\Seeders;

use App\Models\MenuGroup;
use Illuminate\Database\Seeder;

class MenuGroupSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'Admin',
                'sequence' => 1,
                'icon' => '',
                'platform' => 'Backoffice',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        MenuGroup::insert($data);
    }
}
