<?php

namespace App\Http\Controllers;

use App\DTO\Product\CreateProductDTO;
use App\DTO\Product\UpdateProductDTO;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductService $productService
    ) {}

    public function index(): JsonResponse
    {
        $productsDTO = $this->productService->getAllProducts();
        return response()->json($productsDTO->toArray());
    }

    public function available(): JsonResponse
    {
        $productsDTO = $this->productService->getAvailableProducts();
        return response()->json($productsDTO->toArray());
    }

    public function show(Product $product): JsonResponse
    {
        return response()->json($product);
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $createDTO = CreateProductDTO::fromArray($request->validated());
        $responseDTO = $this->productService->createProduct($createDTO);

        return response()->json($responseDTO->toArray(), 201);
    }

    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        $updateDTO = UpdateProductDTO::fromArray($request->validated());
        $responseDTO = $this->productService->updateProduct($product, $updateDTO);

        return response()->json($responseDTO->toArray());
    }

    public function destroy(Product $product): JsonResponse
    {
        $this->productService->deleteProduct($product);

        return response()->json([
            'message' => 'Продукт успешно удален.'
        ]);
    }
}
