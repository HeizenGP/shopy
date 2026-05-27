<?php

namespace App\Modules\Auth\Domain\Services;

interface PasswordHasher
{
    public function hash(string $plainPassword): string;
    public function verify(string $plainPassword, string $hashedPassword): bool;
}
