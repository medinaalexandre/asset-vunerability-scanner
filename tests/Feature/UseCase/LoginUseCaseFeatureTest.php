<?php

use App\Exceptions\InvalidCredentialsException;
use App\Models\User;
use App\Repositories\EloquentUserRepository;
use App\UseCases\LoginUseCase;
use Laravel\Sanctum\NewAccessToken;

beforeEach(function () {
    $this->useCase = new LoginUseCase(new EloquentUserRepository(new User()));
});

it('should return the token when given a valid email/password', function () {
    $user = User::factory()->create();

    $result = $this->useCase->execute($user->email, 'password');

    expect($result)->toBeInstanceOf(NewAccessToken::class)
        ->and($result->plainTextToken)->toBeString()
        ->and($result->accessToken->expires_at)->not->toBeNull();
});

it('should throw an exception when the email is not found', function () {
    expect(fn () => $this->useCase->execute('invalid@email.com', 'password'))
        ->toThrow(InvalidCredentialsException::class);
});

it('should throw an exception when the password is incorrect', function () {
    $user = User::factory()->create();
    expect(fn () => $this->useCase->execute($user->email, 'wrong_password'))
    ->toThrow(InvalidCredentialsException::class);
});
