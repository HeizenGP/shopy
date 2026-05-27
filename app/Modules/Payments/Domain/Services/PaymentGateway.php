<?php

namespace App\Modules\Payments\Domain\Services;

interface PaymentGateway
{
    public function charge(float $amount, string $token): array;
}
