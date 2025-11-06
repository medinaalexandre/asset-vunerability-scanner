<?php

use App\Exceptions\InvalidCredentialsException;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\UseCases\LoginUseCase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\NewAccessToken;

beforeEach(function () {
    $this->userRepositoryMock = Mockery::mock(UserRepositoryInterface::class);
    $this->loginUseCase = new LoginUseCase($this->userRepositoryMock);
});

afterEach(function () {
    Mockery::close();
});

test('deve retornar token quando credenciais são válidas', function () {
    $email = 'foo@bar.com';
    $password = 'pass';
    $hashedPassword = '$2y$12$.0/AghFOKD9Q1QvjchHeEe6YLh3uMHxz9o85.P83uBuDVCoANaZhC';
    $expectedToken = '1|mocked_token';

    $user = Mockery::mock(User::class)->makePartial();
    $user->password = $hashedPassword;

    $accessToken = Mockery::mock();
    $accessToken->shouldReceive('save')
        ->once()
        ->andReturnTrue();
    $accessToken->expires_at = now()->addDay();

    $tokenResult = Mockery::mock(NewAccessToken::class);
    $tokenResult->plainTextToken = $expectedToken;
    $tokenResult->accessToken = $accessToken;

    $user->shouldReceive('createToken')
        ->once()
        ->andReturn($tokenResult);

    $this->userRepositoryMock
        ->shouldReceive('findByEmail')
        ->with($email)
        ->once()
        ->andReturn($user);

    Hash::shouldReceive('check')
        ->with($password, $hashedPassword)
        ->once()
        ->andReturn(true);

    $result = $this->loginUseCase->execute($email, $password);

    expect($result)->toBeInstanceOf(NewAccessToken::class)
        ->and($result->plainTextToken)->toBe($expectedToken);
});

test('deve lançar exceção quando usuário não é encontrado', function () {
    $email = 'missing@foo.com';
    $this->userRepositoryMock->shouldReceive('findByEmail')
        ->with($email)
        ->once()
        ->andReturn(null);

    expect(fn () => $this->loginUseCase->execute($email, 'password'))
        ->toThrow(InvalidCredentialsException::class);
});

test('deve lançar exceção quando senha está incorreta', function () {
    $email = 'teste@example.com';
    $password = 'senha_incorreta';
    $hashedPassword = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';

    $user = Mockery::mock(User::class)->makePartial();
    $user->password = $hashedPassword;

    $this->userRepositoryMock->shouldReceive('findByEmail')
        ->with($email)
        ->once()
        ->andReturn($user);

    Hash::shouldReceive('check')
        ->with($password, $hashedPassword)
        ->once()
        ->andReturn(false);

    expect(fn () => $this->loginUseCase->execute($email, $password))
        ->toThrow(InvalidCredentialsException::class);
});
