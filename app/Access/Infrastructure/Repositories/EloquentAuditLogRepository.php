<?php

namespace App\Access\Infrastructure\Repositories;

use App\Access\Domain\Repositories\AuditLogRepositoryInterface;
use App\Access\Infrastructure\Models\AccessAuditLogModel;
use Illuminate\Support\Collection;

final class EloquentAuditLogRepository implements AuditLogRepositoryInterface
{
    public function record(
        ?int $userId,
        string $event,
        ?string $ipAddress = null,
        ?string $userAgent = null,
        ?array $metadata = null,
    ): void {
        AccessAuditLogModel::query()->create([
            'user_id' => $userId,
            'event' => $event,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'metadata' => $metadata,
        ]);
    }

    public function recent(int $limit = 10): Collection
    {
        return AccessAuditLogModel::query()
            ->with('user')
            ->latest('created_at')
            ->limit($limit)
            ->get();
    }
}
