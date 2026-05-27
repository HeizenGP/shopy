<?php

namespace App\Modules\Inventory\Domain\Repositories;

use App\Modules\Inventory\Domain\Entities\Inventory;
use App\Modules\Inventory\Domain\Entities\StockAlert;

interface InventoryRepositoryInterface
{
    public function findByProduct(int $productId, ?int $productVariantId = null): ?Inventory;

    public function save(Inventory $inventory): Inventory;

    public function createStockAlert(int $productId, ?int $productVariantId, string $message): StockAlert;

    /**
     * @return StockAlert[]
     */
    public function getActiveAlerts(): array;

    public function resolveAlertsForProduct(int $productId, ?int $productVariantId = null): void;
}
