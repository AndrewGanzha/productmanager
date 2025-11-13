<?php

namespace App\DTO\Product;

class UpdateProductDTO
{
    public function __construct(
        public readonly ?string $article = null,
        public readonly ?string $name = null,
        public readonly ?string $status = null,
        public readonly ?array $data = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            article: $data['article'] ?? null,
            name: $data['name'] ?? null,
            status: $data['status'] ?? null,
            data: $data['data'] ?? null
        );
    }

    public function toArray(): array
    {
        $result = [];

        if ($this->article !== null) {
            $result['article'] = $this->article;
        }

        if ($this->name !== null) {
            $result['name'] = $this->name;
        }

        if ($this->status !== null) {
            $result['status'] = $this->status;
        }

        if ($this->data !== null) {
            $result['data'] = $this->data;
        }

        return $result;
    }
}
