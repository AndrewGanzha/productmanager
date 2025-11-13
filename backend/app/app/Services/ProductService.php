<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductService
{
    /**
     * Get all products.
     *
     * @return Collection<int, Product>
     */
    public function getAllProducts(): Collection
    {
        return Product::all();
    }

    /**
     * Get only available products.
     *
     * @return Collection<int, Product>
     */
    public function getAvailableProducts(): Collection
    {
        return Product::available()->get();
    }

    /**
     * Get a product by ID.
     *
     * @param int $id
     * @return Product
     */
    public function getProductById(int $id): Product
    {
        return Product::findOrFail($id);
    }

    /**
     * Create a new product.
     *
     * @param array<string, mixed> $data
     * @return Product
     */
    public function createProduct(array $data): Product
    {
        return Product::create($data);
    }

    /**
     * Update an existing product.
     *
     * @param Product $product
     * @param array<string, mixed> $data
     * @return Product
     */
    public function updateProduct(Product $product, array $data): Product
    {
        $product->update($data);
        return $product->fresh();
    }

    /**
     * Delete a product (soft delete).
     *
     * @param Product $product
     * @return void
     */
    public function deleteProduct(Product $product): void
    {
        $product->delete();
    }
}
