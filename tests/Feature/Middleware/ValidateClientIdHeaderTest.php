<?php

use function Pest\Laravel\getJson;
use function Pest\Laravel\withHeaders;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

test('it fails when the x-client-id header is missing', function () {
    getJson('/api/products')
        ->assertStatus(400)
        ->assertJson(['message' => 'Header X-Client-Id is required.']);
});

test('it succeeds when the x-client-id header is present', function () {
    withHeaders([
        'X-Client-Id' => 'my-test-client-id',
    ])->getJson('/api/products')
        ->assertOk();
});
