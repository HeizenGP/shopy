<?php

namespace App\Modules\Orders\Domain\Repositories;

use App\Modules\Orders\Domain\Entities\Order;

interface OrderRepository
{
    public function findById(int $id): ?Order;
    public function save(Order $order): Order;
    /**
     * @return Order[]
     */
    public function findByUserId(int $userId): array;
}
