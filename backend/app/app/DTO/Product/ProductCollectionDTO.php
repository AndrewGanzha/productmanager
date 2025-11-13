<?php

namespace App\DTO\Product;

use Illuminate\Database\Eloquent\Collection;

class ProductCollectionDTO
{
    public function __construct(
        public readonly Collection $products
    ) {}

    public function toArray(): array
    {
        return $this->products->toArray();
    }
}
