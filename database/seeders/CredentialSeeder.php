<?php

namespace Database\Seeders;

use App\Models\Credential;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CredentialSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'client_key' => 'clientKeyAndroid',
                'secret_key' => Hash::make('secret'),
                'platform' => 'Android',
                'type' => 'Customer',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'client_key' => 'clientKeyBackOffice',
                'secret_key' => Hash::make('secret'),
                'platform' => 'Backoffice',
                'type' => 'Backoffice',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];
        Credential::insert($data);
    }
}
