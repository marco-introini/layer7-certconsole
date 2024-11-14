<?php

namespace Database\Seeders;

use App\Models\Certificate;
use App\Models\Gateway;
use Illuminate\Database\Seeder;

class FakeDataSeeder extends Seeder
{
    public function run(): void
    {
        Gateway::factory(3)->create();
        Certificate::factory(30)->create();
    }
}
