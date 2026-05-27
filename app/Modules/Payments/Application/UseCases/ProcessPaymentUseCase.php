<?php

namespace App\Modules\Payments\Application\UseCases;

use App\Modules\Orders\Domain\Repositories\OrderRepository;
use App\Modules\Payments\Domain\Entities\Payment;
use App\Modules\Payments\Domain\Repositories\PaymentRepository;
use App\Modules\Payments\Domain\Services\PaymentGateway;
use DomainException;

class ProcessPaymentUseCase
{
    public function __construct(
        private OrderRepository $orderRepository,
        private PaymentRepository $paymentRepository,
        private PaymentGateway $paymentGateway
    ) {}

    public function execute(int $orderId, string $gateway, string $paymentToken): array
    {
        $order = $this->orderRepository->findById($orderId);

        if (!$order) {
            throw new DomainException("Pedido no encontrado.");
        }

        if ($order->getStatus() === 'paid') {
            throw new DomainException("El pedido ya está pagado.");
        }

        // Charge
        $result = $this->paymentGateway->charge($order->getTotalAmount(), $paymentToken);

        $status = $result['success'] ? 'completed' : 'failed';
        $transactionId = $result['transaction_id'] ?? null;

        // Save payment
        $payment = new Payment(
            id: null,
            orderId: $orderId,
            gateway: $gateway,
            transactionId: $transactionId,
            amount: $order->getTotalAmount(),
            status: $status
        );
        $this->paymentRepository->save($payment);

        if ($result['success']) {
            $order->updateStatus('paid');
            $this->orderRepository->save($order);
        }

        return [
            'success' => $result['success'],
            'transaction_id' => $transactionId,
            'message' => $result['message'] ?? 'Proceso de pago completado.',
        ];
    }
}
