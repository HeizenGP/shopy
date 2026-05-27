<?php

namespace App\Modules\Payments\Infrastructure\Adapters;

use App\Modules\Payments\Domain\Services\PaymentGateway;
use Illuminate\Support\Str;

class MockPaymentGateway implements PaymentGateway
{
    public function charge(float $amount, string $token): array
    {
        if ($token === 'invalid-token') {
            return [
                'success' => false,
                'message' => 'Tarjeta rechazada o token inválido.',
            ];
        }

        return [
            'success' => true,
            'transaction_id' => 'tx_' . Str::random(12),
            'message' => 'Pago procesado exitosamente.',
        ];
    }
}
