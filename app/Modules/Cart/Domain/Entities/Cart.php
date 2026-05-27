<?php

namespace App\Modules\Cart\Domain\Entities;

class Cart
{
    /**
     * @param CartItem[] $items
     */
    public function __construct(
        public readonly int $id,
        public readonly ?int $userId,
        public readonly ?string $sessionId,
        public readonly array $items = []
    ) {}
}
