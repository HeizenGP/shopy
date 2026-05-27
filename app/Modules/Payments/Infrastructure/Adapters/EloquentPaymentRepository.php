<?php

namespace App\Modules\Payments\Infrastructure\Adapters;

use App\Modules\Payments\Domain\Entities\Payment;
use App\Modules\Payments\Domain\Repositories\PaymentRepository;
use App\Modules\Payments\Infrastructure\Database\PaymentModel;

class EloquentPaymentRepository implements PaymentRepository
{
    public function save(Payment $payment): Payment
    {
        $model = PaymentModel::updateOrCreate(
            ['id' => $payment->getId()],
            [
                'order_id' => $payment->getOrderId(),
                'gateway' => $payment->getGateway(),
                'transaction_id' => $payment->getTransactionId(),
                'amount' => $payment->getAmount(),
                'status' => $payment->getStatus(),
            ]
        );

        return $this->toDomain($model);
    }

    public function findById(int $id): ?Payment
    {
        $model = PaymentModel::find($id);

        if (!$model) {
            return null;
        }

        return $this->toDomain($model);
    }

    private function toDomain(PaymentModel $model): Payment
    {
        return new Payment(
            id: $model->id,
            orderId: $model->order_id,
            gateway: $model->gateway,
            transactionId: $model->transaction_id,
            amount: (float) $model->amount,
            status: $model->status
        );
    }
}
