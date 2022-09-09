<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@mailinator.com',
            'username' => 'admin',
            'avatar' => null,
            'email_verified_at' => now(),
            'birth_date' => now()->format('Y-m-d'),
            'password' => Hash::make('1234'),
            'role_id' => Role::whereName('Administrator')->first()->role_id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
