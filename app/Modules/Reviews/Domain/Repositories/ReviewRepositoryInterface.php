<?php

namespace App\Modules\Reviews\Domain\Repositories;

use App\Modules\Reviews\Domain\Entities\Review;

interface ReviewRepositoryInterface
{
    /**
     * @return Review[]
     */
    public function findByProduct(int $productId): array;
    public function save(Review $review): Review;
}
