<?php

namespace App\Catalog\Domain\ValueObjects;

final readonly class Sku
{
    public function __construct(public ?string $value)
    {
        if ($value !== null && trim($value) === '') {
            throw new \InvalidArgumentException('SKU cannot be empty.');
        }
    }
}
