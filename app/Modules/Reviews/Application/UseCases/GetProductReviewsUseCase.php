<?php

namespace App\Modules\Reviews\Application\UseCases;

use App\Modules\Reviews\Domain\Repositories\ReviewRepositoryInterface;

class GetProductReviewsUseCase
{
    public function __construct(
        private readonly ReviewRepositoryInterface $reviewRepository
    ) {}

    public function execute(int $productId): array
    {
        return $this->reviewRepository->findByProduct($productId);
    }
}
