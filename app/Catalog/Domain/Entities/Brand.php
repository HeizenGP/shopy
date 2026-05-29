<?php

namespace App\Catalog\Domain\Entities;

final readonly class Brand
{
    public function __construct(
        public ?int $id,
        public string $name,
        public string $slug,
        public ?string $description = null,
        public bool $isActive = true,
    ) {}
}
