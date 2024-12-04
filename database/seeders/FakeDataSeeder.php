<?php

namespace Database\Seeders;

use App\Models\Certificate;
use App\Models\Gateway;
use Illuminate\Database\Seeder;

class FakeDataSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 3; $i++){
            Gateway::create([
                'name' => 'Gateway ' . $i,
                'host' => 'https://fakegateway' . $i . '.example.com',
                'identity_provider' => "0000000000000000fffffffffffffffe",
                'admin_user' => "fake_admin_user",
                'admin_password' => "fake_password",
            ]);
        }
        Certificate::factory(20)->create();
        Certificate::factory(5)->expired()->create();
    }
}
