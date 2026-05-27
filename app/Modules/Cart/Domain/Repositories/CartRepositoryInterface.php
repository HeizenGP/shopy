<?php

namespace App\Modules\Cart\Domain\Repositories;

use App\Modules\Cart\Domain\Entities\Cart;

interface CartRepositoryInterface
{
    public function findBySessionOrUser(?int $userId, ?string $sessionId): ?Cart;
    public function save(Cart $cart): Cart;
    public function delete(int $id): bool;
}
