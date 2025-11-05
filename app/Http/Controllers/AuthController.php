<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidCredentialsException;
use App\Http\Requests\LoginRequest;
use App\UseCases\LoginUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * @operationId Login
     * @unauthenticated
     * @throws ValidationException|InvalidCredentialsException
     */
    public function login(LoginRequest $request, LoginUseCase $loginUseCase): JsonResponse
    {
        $token = $loginUseCase->execute(
            $request->get('email'),
            $request->get('password')
        );

        return response()->json([
            'token' => $token->plainTextToken,
            'token_type' => 'Bearer',
            'expires_at' => $token->accessToken->expires_at->toIso8601String(),
        ]);
    }
}

