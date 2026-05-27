<?php

use App\Modules\Auth\Domain\Entities\AuthUser;
use App\Modules\Auth\Domain\ValueObjects\Email;

test('it can be instantiated and returns properties', function () {
    $email = new Email('user@example.com');
    $user = new AuthUser(
        id: 1,
        name: 'John Doe',
        email: $email,
        passwordHash: 'hashedpassword'
    );

    expect($user->getId())->toBe(1);
    expect($user->getName())->toBe('John Doe');
    expect($user->getEmail())->toBe($email);
    expect($user->getPasswordHash())->toBe('hashedpassword');
});
