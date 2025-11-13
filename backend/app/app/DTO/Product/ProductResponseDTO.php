<?php

namespace App\DTO\Product;

use App\Models\Product;

class ProductResponseDTO
{
    public function __construct(
        public readonly Product $product,
        public readonly ?string $message = null
    ) {}

    public function toArray(): array
    {
        $response = [
            'product' => $this->product,
        ];

        if ($this->message !== null) {
            $response['message'] = $this->message;
        }

        return $response;
    }
}
