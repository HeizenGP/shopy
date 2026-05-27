<?php

namespace App\Modules\Inventory\Application\UseCases;

use App\Modules\Inventory\Domain\Repositories\InventoryRepositoryInterface;

class GetStockAlertsUseCase
{
    public function __construct(
        private readonly InventoryRepositoryInterface $inventoryRepository
    ) {}

    public function execute(): array
    {
        return $this->inventoryRepository->getActiveAlerts();
    }
}
