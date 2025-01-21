<?php

use function Pest\Laravel\get;



it('returns a successful response', function (): void {
    $response = get('/');

    $response->assertStatus(200);
});

it('returns a certificate', function (): void {
    $certificate = \App\Models\Certificate::factory()->create();
    $response = get('/');

    $response->assertSee($certificate->common_name);
});
