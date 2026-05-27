<?php

namespace App\Modules\Cart\Application\UseCases;

use App\Modules\Cart\Application\DTOs\CartResponseDTO;
use App\Modules\Cart\Domain\Repositories\CartRepository;
use InvalidArgumentException;

class GetCartUseCase
{
    public function __construct(private CartRepository $cartRepository) {}

    public function execute(?int $userId, ?string $sessionId): CartResponseDTO
    {
        $cart = null;

        if ($userId) {
            $cart = $this->cartRepository->findByUserId($userId);
            if (!$cart) {
                $cart = $this->cartRepository->createForUser($userId);
            }
        } elseif ($sessionId) {
            $cart = $this->cartRepository->findBySessionId($sessionId);
            if (!$cart) {
                $cart = $this->cartRepository->createForSession($sessionId);
            }
        } else {
            throw new InvalidArgumentException("Se requiere userId o sessionId para obtener el carrito.");
        }

        return CartResponseDTO::fromEntity($cart);
    }
}
