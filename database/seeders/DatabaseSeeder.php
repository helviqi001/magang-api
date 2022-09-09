<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CredentialSeeder::class,
            MenuGroupSeeder::class,
            MenuItemSeeder::class,
            RoleSeeder::class,
            PriviledgeSeeder::class,
            UserSeeder::class
        ]);
    }
}
