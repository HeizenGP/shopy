<?php

namespace App\Modules\Reviews\Application\UseCases;

use App\Modules\Reviews\Domain\Entities\Review;
use App\Modules\Reviews\Domain\Repositories\ReviewRepositoryInterface;
use Exception;

class AddReviewUseCase
{
    public function __construct(
        private readonly ReviewRepositoryInterface $reviewRepository
    ) {}

    public function execute(int $productId, ?int $userId, ?string $name, int $rating, ?string $comment): Review
    {
        if ($rating < 1 || $rating > 5) {
            throw new Exception("La calificación debe estar entre 1 y 5 estrellas.");
        }

        // Verify product exists in Catalog
        $product = \App\Modules\Catalog\Infrastructure\Database\Models\ProductEloquent::find($productId);
        if (!$product) {
            throw new Exception("El producto no existe.");
        }

        $review = new Review(
            id: 0,
            productId: $productId,
            userId: $userId,
            name: $name,
            rating: $rating,
            comment: $comment
        );

        return $this->reviewRepository->save($review);
    }
}
