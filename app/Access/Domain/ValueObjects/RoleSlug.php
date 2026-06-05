<?php

namespace App\Access\Domain\ValueObjects;

use InvalidArgumentException;

final readonly class RoleSlug
{
    private string $value;

    public function __construct(string $value)
    {
        $normalized = trim($value);

        if ($normalized === '' || strlen($normalized) > 150 || ! preg_match('/^[a-z0-9]+(?:_[a-z0-9]+)*$/', $normalized)) {
            throw new InvalidArgumentException('El slug de rol debe usar minúsculas, números y guiones bajos.');
        }

        $this->value = $normalized;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
