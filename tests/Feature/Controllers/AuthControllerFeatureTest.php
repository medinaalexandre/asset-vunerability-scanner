<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

it('should return 401 when credentials are incorrect', function () {
    $this->post(route('login'), [
        'email' => 'invalid-email@foobar.com',
        'password' => 'password',
    ])->assertUnauthorized()
        ->assertJson(['message' => 'The provided credentials are incorrect.']);
});

it('receive correct user access token with valid credentials', function () {
    $user = User::factory()->create([
        'password' => Hash::make('password'),
    ]);

    $res = $this->post(route('login'), [
        'email' => $user->email,
        'password' => 'password',
    ]);
    $res->assertOk()
        ->assertJsonStructure([
            'token',
            'token_type',
            'expires_at',
        ]);

    $tokenId = explode('|', $res->json('token'))[0];

    expect($user->tokens()->where('id', $tokenId)->exists())->toBeTrue();
});
