<?php

namespace App\Modules\Orders\Application\UseCases;

use App\Modules\Orders\Domain\Enums\OrderStatus;
use App\Modules\Orders\Domain\Repositories\OrderRepositoryInterface;
use Exception;

class UpdateOrderStatusUseCase
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository
    ) {}

    public function execute(int $orderId, string $status): void
    {
        $order = $this->orderRepository->findById($orderId);
        if (!$order) {
            throw new Exception("Pedido no encontrado.");
        }

        try {
            $newStatus = OrderStatus::from($status);
        } catch (\ValueError $e) {
            throw new Exception("Estado de pedido no válido: {$status}");
        }

        $this->orderRepository->updateStatus($orderId, $newStatus);
    }
}
