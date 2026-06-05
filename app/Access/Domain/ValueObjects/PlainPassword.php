<?php

namespace App\Access\Domain\ValueObjects;

use InvalidArgumentException;

final readonly class PlainPassword
{
    private string $value;

    public function __construct(string $value)
    {
        if (strlen($value) < 8) {
            throw new InvalidArgumentException('La contraseña debe tener al menos 8 caracteres.');
        }

        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }
}
