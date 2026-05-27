<?php

use App\Modules\Auth\Application\DTOs\LoginInputDTO;
use App\Modules\Auth\Application\Ports\AuthSessionManager;
use App\Modules\Auth\Application\UseCases\LoginUseCase;
use App\Modules\Auth\Domain\Entities\AuthUser;
use App\Modules\Auth\Domain\Exceptions\InvalidCredentialsException;
use App\Modules\Auth\Domain\Repositories\AuthUserRepository;
use App\Modules\Auth\Domain\Services\PasswordHasher;
use App\Modules\Auth\Domain\ValueObjects\Email;

test('login is successful with valid credentials', function () {
    $emailStr = 'test@example.com';
    $passwordStr = 'password123';
    $hashedPassword = 'hashedpassword';

    $email = new Email($emailStr);
    $authUser = new AuthUser(1, 'Test User', $email, $hashedPassword);

    $repository = Mockery::mock(AuthUserRepository::class);
    $repository->shouldReceive('findByEmail')
        ->with(Mockery::on(fn (Email $arg) => $arg->getValue() === $emailStr))
        ->once()
        ->andReturn($authUser);

    $hasher = Mockery::mock(PasswordHasher::class);
    $hasher->shouldReceive('verify')
        ->with($passwordStr, $hashedPassword)
        ->once()
        ->andReturn(true);

    $sessionManager = Mockery::mock(AuthSessionManager::class);
    $sessionManager->shouldReceive('login')
        ->with($authUser)
        ->once();

    $useCase = new LoginUseCase($repository, $hasher, $sessionManager);
    $inputDto = new LoginInputDTO($emailStr, $passwordStr);

    $outputDto = $useCase->execute($inputDto);

    expect($outputDto->id)->toBe(1);
    expect($outputDto->name)->toBe('Test User');
    expect($outputDto->email)->toBe($emailStr);
});

test('login fails when user is not found', function () {
    $emailStr = 'notfound@example.com';

    $repository = Mockery::mock(AuthUserRepository::class);
    $repository->shouldReceive('findByEmail')
        ->once()
        ->andReturn(null);

    $hasher = Mockery::mock(PasswordHasher::class);
    $sessionManager = Mockery::mock(AuthSessionManager::class);

    $useCase = new LoginUseCase($repository, $hasher, $sessionManager);
    $inputDto = new LoginInputDTO($emailStr, 'password');

    expect(fn () => $useCase->execute($inputDto))->toThrow(InvalidCredentialsException::class);
});

test('login fails when password verify fails', function () {
    $emailStr = 'test@example.com';
    $passwordStr = 'wrongpassword';
    $hashedPassword = 'hashedpassword';

    $email = new Email($emailStr);
    $authUser = new AuthUser(1, 'Test User', $email, $hashedPassword);

    $repository = Mockery::mock(AuthUserRepository::class);
    $repository->shouldReceive('findByEmail')
        ->once()
        ->andReturn($authUser);

    $hasher = Mockery::mock(PasswordHasher::class);
    $hasher->shouldReceive('verify')
        ->with($passwordStr, $hashedPassword)
        ->once()
        ->andReturn(false);

    $sessionManager = Mockery::mock(AuthSessionManager::class);

    $useCase = new LoginUseCase($repository, $hasher, $sessionManager);
    $inputDto = new LoginInputDTO($emailStr, $passwordStr);

    expect(fn () => $useCase->execute($inputDto))->toThrow(InvalidCredentialsException::class);
});
