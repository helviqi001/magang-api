<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        Role::create([
            'name' => 'Administrator',
            'description' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
