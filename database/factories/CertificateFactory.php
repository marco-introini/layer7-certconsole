<?php

namespace Database\Factories;

use App\Enumerations\CertificateType;
use App\Models\Certificate;
use App\Models\Gateway;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CertificateFactory extends Factory
{
    protected $model = Certificate::class;

    public function definition(): array
    {
        return [
            'gateway_id' => Gateway::inRandomOrder()->first()->id ?? Gateway::factory()->create()->id,
            'type' => CertificateType::TRUSTED_CERT,
            'common_name' => $this->faker->word(),
            'valid_from' => Carbon::now(),
            'valid_to' => Carbon::now(),
            'created_at' => fake()->dateTime(),
            'updated_at' => fake()->dateTime(),
        ];
    }
}
