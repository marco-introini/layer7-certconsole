<?php

namespace Database\Factories;

use App\Enumerations\CertificateType;
use App\Models\Certificate;
use App\Models\Gateway;
use App\Services\CertificateUtilityService;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CertificateFactory extends Factory
{
    protected $model = Certificate::class;

    public function definition(): array
    {
        $commonName = $this->faker->company();
        return [
            'gateway_id' => Gateway::inRandomOrder()->first()->id ?? Gateway::factory()->create()->id,
            'type' => fake()->randomElement(CertificateType::cases())->value,
            'common_name' => $commonName,
            'valid_from' => Carbon::now(),
            'valid_to' => Carbon::now(),
            'certificate' => CertificateUtilityService::generateCertificate($commonName, Carbon::now()->addYears(2)),
            'created_at' => fake()->dateTime(),
            'updated_at' => fake()->dateTime(),
        ];
    }
}
