<?php

use App\Models\Certificate;

test('a valid certificate is marked valid', function (): void {
    $cert = Certificate::factory()->create();
    expect($cert->is_valid)->toBeTrue();
});

test('an expiring certificate is marked valid', function (): void {
    $cert = Certificate::factory()->create([
        'valid_to' => now()->addDay(),
    ]);
    expect($cert->is_valid)->toBeTrue();
});

test('a valid certificate is not expiring', function (): void {
    $cert = Certificate::factory()->create();
    expect($cert->isAboutToExpire())->toBeFalse();
});
