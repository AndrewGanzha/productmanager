<?php

namespace App\DTO\Product;

class CreateProductDTO
{
    public function __construct(
        public readonly string $article,
        public readonly string $name,
        public readonly string $status = 'available',
        public readonly ?array $data = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            article: $data['article'],
            name: $data['name'],
            status: $data['status'] ?? 'available',
            data: $data['data'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'article' => $this->article,
            'name' => $this->name,
            'status' => $this->status,
            'data' => $this->data,
        ];
    }
}
