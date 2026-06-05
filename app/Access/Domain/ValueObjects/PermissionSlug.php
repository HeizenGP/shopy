<?php

namespace App\Access\Domain\ValueObjects;

use InvalidArgumentException;

final readonly class PermissionSlug
{
    private string $value;

    public function __construct(string $value)
    {
        $normalized = trim($value);

        if (! preg_match('/^[a-z0-9]+(?:_[a-z0-9]+)*\.[a-z0-9]+(?:_[a-z0-9]+)*$/', $normalized)) {
            throw new InvalidArgumentException('El permiso debe tener formato modulo.accion.');
        }

        $this->value = $normalized;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function module(): string
    {
        return explode('.', $this->value, 2)[0];
    }

    public function action(): string
    {
        return explode('.', $this->value, 2)[1];
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
