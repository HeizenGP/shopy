<?php

use App\Modules\Auth\Domain\ValueObjects\Email;

test('it accepts valid email formats', function () {
    $email = new Email('test@example.com');
    expect($email->getValue())->toBe('test@example.com');
});

test('it throws exception for invalid email formats', function () {
    expect(fn () => new Email('invalid-email'))->toThrow(InvalidArgumentException::class);
});

test('it compares two emails correctly', function () {
    $email1 = new Email('test@example.com');
    $email2 = new Email('TEST@example.com');
    $email3 = new Email('other@example.com');

    expect($email1->equals($email2))->toBeTrue();
    expect($email1->equals($email3))->toBeFalse();
});
