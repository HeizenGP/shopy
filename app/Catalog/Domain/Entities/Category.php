<?php

namespace App\Catalog\Domain\Entities;

final readonly class Category
{
    public function __construct(
        public ?int $id,
        public string $name,
        public string $slug,
        public ?int $parentId = null,
        public ?string $description = null,
        public bool $isActive = true,
        public int $sortOrder = 0,
    ) {}
}
