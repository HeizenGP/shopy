<?php

namespace App\Modules\Reviews\Domain\Entities;

class Review
{
    public function __construct(
        public readonly int $id,
        public readonly int $productId,
        public readonly ?int $userId,
        public readonly ?string $name,
        public readonly int $rating, // 1-5
        public readonly ?string $comment,
        public readonly ?string $createdAt = null
    ) {}
}
