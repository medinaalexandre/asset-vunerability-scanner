<?php

use App\Exceptions\InvalidCredentialsException;
use App\Http\Controllers\AuthController;
use App\Http\Requests\LoginRequest;
use App\UseCases\LoginUseCase;
use Carbon\Carbon;
use Laravel\Sanctum\NewAccessToken;
use Laravel\Sanctum\PersonalAccessToken;

beforeEach(function () {
    $this->controller = new AuthController();
    $this->loginUseCaseMock = Mockery::mock(LoginUseCase::class);
});

it('should return 401 when credentials are incorrect', function () {
    $this->loginUseCaseMock->shouldReceive('execute')
        ->andThrow(InvalidCredentialsException::class);

    $response = $this->controller->login(new LoginRequest([
        'email' => 'foo@bar.com',
        'password' => 'password'
    ]), $this->loginUseCaseMock);

    expect($response->getStatusCode())->toBe(401)
        ->and($response->getContent())
        ->json()
        ->message->toBe('The provided credentials are incorrect.');
})->throws(InvalidCredentialsException::class);

it('should return 200 with credentials when credentials are correct', function () {
    $expiresAt = Carbon::now()->addDay();
    $accessToken = new NewAccessToken(new PersonalAccessToken(
        ['expires_at' => $expiresAt]
    ), '1|fooBarToken');
    $this->loginUseCaseMock->shouldReceive('execute')
        ->andReturn($accessToken);

    $response = $this->controller->login(new LoginRequest([
        'email' => 'foo@bar.com',
        'password' => 'password'
    ]), $this->loginUseCaseMock);

    expect($response->getStatusCode())->toBe(200)
        ->and($response->getContent())
        ->json()
        ->token->toBe('1|fooBarToken')
        ->token_type->toBe('Bearer')
        ->expires_at->toBe($expiresAt->toIso8601String());

});
