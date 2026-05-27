<?php

namespace App\Modules\Catalog\Domain\ValueObjects;

use InvalidArgumentException;

class Price
{
    public function __construct(
        private float $amount,
        private string $currency = 'USD'
    ) {
        if ($amount < 0) {
            throw new InvalidArgumentException("El precio no puede ser negativo.");
        }
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function format(): string
    {
        return "{$this->currency} " . number_format($this->amount, 2);
    }
}
