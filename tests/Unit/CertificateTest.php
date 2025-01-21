<?php

use App\Models\Certificate;

beforeEach(function (): void {
});

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

test('an expired certificate is marked not valid', function (): void {
    $cert = Certificate::factory()->expired()->create();
    expect($cert->is_valid)->toBeFalse();
});

test('an expired certificate is also expiring', function (): void {
    $cert = Certificate::factory()->expired()->create();
    expect($cert->isAboutToExpire())->toBeTrue();
});
