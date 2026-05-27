<?php

namespace App\Modules\Cart\Application\UseCases;

use App\Modules\Cart\Application\DTOs\CartResponseDTO;
use App\Modules\Cart\Domain\Repositories\CartRepository;
use InvalidArgumentException;

class AddToCartUseCase
{
    public function __construct(private CartRepository $cartRepository) {}

    public function execute(?int $userId, ?string $sessionId, int $productId, ?int $productVariantId, int $quantity): CartResponseDTO
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
            throw new InvalidArgumentException("Se requiere userId o sessionId.");
        }

        $cart->addItem($productId, $productVariantId, $quantity);
        $savedCart = $this->cartRepository->save($cart);

        return CartResponseDTO::fromEntity($savedCart);
    }
}
