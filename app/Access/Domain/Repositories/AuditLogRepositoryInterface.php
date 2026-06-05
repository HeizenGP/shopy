<?php

namespace App\Access\Domain\Repositories;

use Illuminate\Support\Collection;

interface AuditLogRepositoryInterface
{
    public function record(
        ?int $userId,
        string $event,
        ?string $ipAddress = null,
        ?string $userAgent = null,
        ?array $metadata = null,
    ): void;

    public function recent(int $limit = 10): Collection;
}
