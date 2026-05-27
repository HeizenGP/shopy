<?php

namespace App\Modules\Cart\Application\UseCases;

use App\Modules\Cart\Domain\Entities\Cart;
use App\Modules\Cart\Domain\Repositories\CartRepositoryInterface;

class GetCartUseCase
{
    public function __construct(
        private readonly CartRepositoryInterface $cartRepository
    ) {}

    public function execute(?int $userId, ?string $sessionId): Cart
    {
        $cart = $this->cartRepository->findBySessionOrUser($userId, $sessionId);

        if (!$cart) {
            return new Cart(id: 0, userId: $userId, sessionId: $sessionId, items: []);
        }

        return $cart;
    }
}
