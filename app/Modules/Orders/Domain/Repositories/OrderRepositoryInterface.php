<?php

namespace App\Modules\Orders\Domain\Repositories;

use App\Modules\Orders\Domain\Entities\Order;
use App\Modules\Orders\Domain\Enums\OrderStatus;

interface OrderRepositoryInterface
{
    public function findById(int $id): ?Order;
    /**
     * @return Order[]
     */
    public function findByUserId(int $userId): array;
    /**
     * @return Order[]
     */
    public function findAll(): array;
    public function save(Order $order): Order;
    public function updateStatus(int $orderId, OrderStatus $status): void;
}
