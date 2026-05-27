<?php

namespace App\Modules\Auth\Domain\ValueObjects;

use InvalidArgumentException;

class Password
{
    private string $value;

    public function __construct(string $value)
    {
        if (empty(trim($value))) {
            throw new InvalidArgumentException("La contraseña no puede estar vacía.");
        }
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
