<?php

use App\Models\User;

it('can create an asset', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $res = $this->post(route('assets.create'), [
        'name' => 'ucs-x-series-modular-systems',
        'description' => 'Cisco UCS® X-Series is designed to meet the needs of modern applications...',
        'device_type' => 'server',
        'location' => 'Santa Maria, Brazil'
    ]);

    $res->assertCreated();
});

it('receive unauthorized if not authenticated', function () {
    $res = $this->post(route('assets.create'));
    $res->assertUnauthorized();
});

it('receive bad request if send invalid payload', function (array $payload, array $missingKeys) {
    $this->actingAs(User::factory()->create());
    $res = $this->post(route('assets.create'), $payload);
    $res->assertUnprocessable();

    $res->assertJsonStructure([
        'message',
        'errors'
    ]);
    expect($res->json('errors'))->toBeArray()->toHaveKeys($missingKeys);
})->with([
    'missing name' => [
        'payload' => [
            'description' => 'Basic server',
            'device_type' => 'server',
            'location' => 'São Gabriel, Brazil'
        ],
        'missingKeys' => ['name']
    ],
    'missing device type' => [
        'payload' => [
            'name' => 'Windows Server',
            'description' => 'Windows Server with Office 365',
            'location' => 'London, England',
        ],
        'missingKeys' => ['device_type']
    ],
    'missing location' => [
        'payload' => [
            'name' => 'Linux Server',
            'description' => 'Linux Server with Unix',
            'device_type' => 'server',
        ],
        'missingKeys' => ['location']
    ]
]);

it('can attach a vulnerability to an asset', function () {

});
