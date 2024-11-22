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
        $validFrom = Carbon::now();
        $validTo =  $validFrom->addYears(2);
        return [
            'gateway_id' => Gateway::inRandomOrder()->first()->id ?? Gateway::factory()->create()->id,
            'type' => fake()->randomElement(CertificateType::cases())->value,
            'common_name' => $commonName,
            'valid_from' => $validFrom,
            'valid_to' => $validTo,
            'certificate' =>
                CertificateUtilityService::generateCertificate($commonName, $validTo)
                    ->certificate,
            'created_at' => fake()->dateTime(),
            'updated_at' => fake()->dateTime(),
        ];
    }

    public function expired(): static
    {
        $commonName = $this->faker->company();

        return $this->state(fn (array $attributes) => [
            'valid_from' => Carbon::now()->subYear(),
            'valid_to' => Carbon::yesterday(),
            'certificate' =>
                CertificateUtilityService::generateCertificate($commonName, Carbon::yesterday())
                    ->certificate,
        ]);
    }
}
