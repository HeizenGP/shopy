<?php

namespace App\Modules\Inventory\Application\UseCases;

use App\Modules\Inventory\Domain\Entities\Inventory;
use App\Modules\Inventory\Domain\Repositories\InventoryRepositoryInterface;
use Exception;

class VerifyAndDeductStockUseCase
{
    public function __construct(
        private readonly InventoryRepositoryInterface $inventoryRepository
    ) {}

    public function execute(int $productId, ?int $productVariantId, int $quantity): void
    {
        $inventory = $this->inventoryRepository->findByProduct($productId, $productVariantId);

        if (!$inventory) {
            throw new Exception("Sin registro de inventario para este producto/variante.");
        }

        if ($inventory->stock < $quantity) {
            throw new Exception("Stock insuficiente. Disponible: {$inventory->stock}, solicitado: {$quantity}.");
        }

        $newStock = $inventory->stock - $quantity;
        $updatedInventory = new Inventory(
            id: $inventory->id,
            productId: $inventory->productId,
            productVariantId: $inventory->productVariantId,
            stock: $newStock,
            lowStockThreshold: $inventory->lowStockThreshold
        );

        $this->inventoryRepository->save($updatedInventory);

        // Check if low stock threshold is reached
        if ($updatedInventory->isLowStock()) {
            $msg = "El producto con ID {$productId}" . 
                   ($productVariantId ? " y variante ID {$productVariantId}" : "") . 
                   " tiene stock bajo ({$newStock} unidades).";
            
            $this->inventoryRepository->createStockAlert($productId, $productVariantId, $msg);
        }
    }
}
