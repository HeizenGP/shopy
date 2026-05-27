<?php

namespace App\Modules\Payments\Domain\Repositories;

use App\Modules\Payments\Domain\Entities\Payment;

interface PaymentRepository
{
    public function save(Payment $payment): Payment;
    public function findById(int $id): ?Payment;
}
