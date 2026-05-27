<?php

namespace App\Modules\Cart\Domain\Repositories;

use App\Modules\Cart\Domain\Entities\Cart;

interface CartRepository
{
    public function findByUserId(int $userId): ?Cart;
    public function findBySessionId(string $sessionId): ?Cart;
    public function save(Cart $cart): Cart;
    public function createForUser(int $userId): Cart;
    public function createForSession(string $sessionId): Cart;
}
