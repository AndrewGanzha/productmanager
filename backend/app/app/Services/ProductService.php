<?php

namespace App\Services;

use App\DTO\Product\CreateProductDTO;
use App\DTO\Product\ProductCollectionDTO;
use App\DTO\Product\ProductResponseDTO;
use App\DTO\Product\UpdateProductDTO;
use App\Models\Product;

class ProductService
{
    public function getAllProducts(): ProductCollectionDTO
    {
        return new ProductCollectionDTO(Product::all());
    }

    public function getAvailableProducts(): ProductCollectionDTO
    {
        return new ProductCollectionDTO(Product::available()->get());
    }

    public function getProductById(int $id): ProductResponseDTO
    {
        $product = Product::findOrFail($id);
        return new ProductResponseDTO($product);
    }

    public function createProduct(CreateProductDTO $dto): ProductResponseDTO
    {
        $product = Product::create($dto->toArray());
        return new ProductResponseDTO($product, 'Продукт успешно создан.');
    }

    public function updateProduct(Product $product, UpdateProductDTO $dto): ProductResponseDTO
    {
        $product->update($dto->toArray());
        return new ProductResponseDTO($product->fresh(), 'Продукт успешно обновлен.');
    }

    public function deleteProduct(Product $product): void
    {
        $product->delete();
    }
}
