<?php

namespace App\UseCases;

use App\Exceptions\InvalidCredentialsException;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\NewAccessToken;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class LoginUseCase
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {
    }

    /**
     * @throws InvalidCredentialsException
     */
    public function execute(string $email, string $password): NewAccessToken
    {
        $user = $this->userRepository->findByEmail($email);

        if (!$user) {
            throw new InvalidCredentialsException();
        }

        if (!Hash::check($password, $user->password)) {
            throw new InvalidCredentialsException();
        }

        $expiresAt = now()->addDay();
        $tokenResult = $user->createToken('auth-token');
        $tokenResult->accessToken->expires_at = $expiresAt;
        $tokenResult->accessToken->save();

        return $tokenResult;
    }
}
