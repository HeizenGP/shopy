<?php

namespace App\Catalog\Domain\ValueObjects;

final readonly class Money
{
    public function __construct(public float $amount)
    {
        if ($amount < 0) {
            throw new \InvalidArgumentException('Money amount cannot be negative.');
        }
    }

    public function formatted(string $currency = 'S/'): string
    {
        return $currency.' '.number_format($this->amount, 2);
    }
}
