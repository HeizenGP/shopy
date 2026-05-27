<?php

namespace App\Modules\Inventory\Infrastructure\Adapters;

use App\Modules\Inventory\Domain\Entities\Inventory;
use App\Modules\Inventory\Domain\Entities\StockAlert;
use App\Modules\Inventory\Domain\Repositories\InventoryRepositoryInterface;
use App\Modules\Inventory\Infrastructure\Database\Models\InventoryEloquent;
use App\Modules\Inventory\Infrastructure\Database\Models\StockAlertEloquent;

class EloquentInventoryRepository implements InventoryRepositoryInterface
{
    public function findByProduct(int $productId, ?int $productVariantId = null): ?Inventory
    {
        $eloquent = InventoryEloquent::where('product_id', $productId)
            ->where('product_variant_id', $productVariantId)
            ->first();

        return $eloquent ? $this->toDomain($eloquent) : null;
    }

    public function save(Inventory $inventory): Inventory
    {
        $eloquent = InventoryEloquent::updateOrCreate(
            [
                'product_id' => $inventory->productId,
                'product_variant_id' => $inventory->productVariantId,
            ],
            [
                'stock' => $inventory->stock,
                'low_stock_threshold' => $inventory->lowStockThreshold,
            ]
        );

        return $this->toDomain($eloquent);
    }

    public function createStockAlert(int $productId, ?int $productVariantId, string $message): StockAlert
    {
        // Don't duplicate active alerts
        $eloquent = StockAlertEloquent::firstOrCreate(
            [
                'product_id' => $productId,
                'product_variant_id' => $productVariantId,
                'is_resolved' => false,
            ],
            [
                'message' => $message,
            ]
        );

        return $this->toAlertDomain($eloquent);
    }

    public function getActiveAlerts(): array
    {
        return StockAlertEloquent::where('is_resolved', false)
            ->get()
            ->map(fn ($el) => $this->toAlertDomain($el))
            ->toArray();
    }

    public function resolveAlertsForProduct(int $productId, ?int $productVariantId = null): void
    {
        StockAlertEloquent::where('product_id', $productId)
            ->where('product_variant_id', $productVariantId)
            ->update(['is_resolved' => true]);
    }

    private function toDomain(InventoryEloquent $eloquent): Inventory
    {
        return new Inventory(
            id: $eloquent->id,
            productId: $eloquent->product_id,
            productVariantId: $eloquent->product_variant_id,
            stock: $eloquent->stock,
            lowStockThreshold: $eloquent->low_stock_threshold
        );
    }

    private function toAlertDomain(StockAlertEloquent $eloquent): StockAlert
    {
        return new StockAlert(
            id: $eloquent->id,
            productId: $eloquent->product_id,
            productVariantId: $eloquent->product_variant_id,
            message: $eloquent->message,
            isResolved: $eloquent->is_resolved
        );
    }
}
