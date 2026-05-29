<?php

namespace App\Catalog\Presentation\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'sku' => $this->sku,
            'price' => $this->formattedPrice(),
            'status' => $this->status?->value,
            'brand' => $this->brand?->name,
            'category' => $this->mainCategory?->name,
        ];
    }
}
