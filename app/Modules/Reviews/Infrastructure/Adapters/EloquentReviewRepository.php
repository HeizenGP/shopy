<?php

namespace App\Modules\Reviews\Infrastructure\Adapters;

use App\Modules\Reviews\Domain\Entities\Review;
use App\Modules\Reviews\Domain\Repositories\ReviewRepositoryInterface;
use App\Modules\Reviews\Infrastructure\Database\Models\ReviewEloquent;

class EloquentReviewRepository implements ReviewRepositoryInterface
{
    public function findByProduct(int $productId): array
    {
        return ReviewEloquent::where('product_id', $productId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn ($el) => $this->toDomain($el))
            ->toArray();
    }

    public function save(Review $review): Review
    {
        $eloquent = ReviewEloquent::create([
            'product_id' => $review->productId,
            'user_id' => $review->userId,
            'name' => $review->name,
            'rating' => $review->rating,
            'comment' => $review->comment,
        ]);

        return $this->toDomain($eloquent);
    }

    private function toDomain(ReviewEloquent $eloquent): Review
    {
        return new Review(
            id: $eloquent->id,
            productId: $eloquent->product_id,
            userId: $eloquent->user_id,
            name: $eloquent->name ?: ($eloquent->user?->name ?: 'Anónimo'),
            rating: $eloquent->rating,
            comment: $eloquent->comment,
            createdAt: $eloquent->created_at?->toIso8601String()
        );
    }
}
