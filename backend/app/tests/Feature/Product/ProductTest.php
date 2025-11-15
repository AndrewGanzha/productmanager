<?php

namespace Tests\Feature\Product;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /**
     * Test unauthenticated user cannot access products.
     */
    public function test_unauthenticated_user_cannot_access_products(): void
    {
        $response = $this->getJson('/api/products/');

        $response->assertStatus(401);
    }

    /**
     * Test authenticated user can get all products.
     */
    public function test_authenticated_user_can_get_all_products(): void
    {
        Sanctum::actingAs($this->user);

        Product::factory()->count(3)->create();

        $response = $this->getJson('/api/products/');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'article',
                    'name',
                    'status',
                    'data',
                ],
            ])
            ->assertJsonCount(3);
    }

    /**
     * Test authenticated user can get available products only.
     */
    public function test_authenticated_user_can_get_available_products(): void
    {
        Sanctum::actingAs($this->user);

        Product::factory()->count(2)->create(['status' => 'available']);
        Product::factory()->count(3)->create(['status' => 'unavailable']);

        $response = $this->getJson('/api/products/available');

        $response->assertStatus(200)
            ->assertJsonCount(2);
    }

    /**
     * Test authenticated user can view a single product.
     */
    public function test_authenticated_user_can_view_single_product(): void
    {
        Sanctum::actingAs($this->user);

        $product = Product::factory()->create([
            'article' => 'TEST001',
            'name' => 'Test Product Name',
            'status' => 'available',
        ]);

        $response = $this->getJson('/api/products/' . $product->id);

        $response->assertStatus(200)
            ->assertJson([
                'id' => $product->id,
                'article' => 'TEST001',
                'name' => 'Test Product Name',
                'status' => 'available',
            ]);
    }

    /**
     * Test 404 when viewing non-existent product.
     */
    public function test_viewing_non_existent_product_returns_404(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->getJson('/api/products/99999');

        $response->assertStatus(404);
    }

    /**
     * Test authenticated user can create a product.
     */
    public function test_authenticated_user_can_create_product(): void
    {
        Queue::fake();
        Sanctum::actingAs($this->user);

        $productData = [
            'article' => 'TEST001',
            'name' => 'Test Product Name',
            'status' => 'available',
            'data' => ['key' => 'value'],
        ];

        $response = $this->postJson('/api/products/', $productData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'product' => [
                    'id',
                    'article',
                    'name',
                    'status',
                    'data',
                ],
                'message',
            ])
            ->assertJson([
                'product' => [
                    'article' => 'TEST001',
                    'name' => 'Test Product Name',
                    'status' => 'available',
                ],
            ]);

        $this->assertDatabaseHas('products', [
            'article' => 'TEST001',
            'name' => 'Test Product Name',
            'status' => 'available',
        ]);
    }

    /**
     * Test product creation fails without required fields.
     */
    public function test_product_creation_fails_without_required_fields(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson('/api/products/', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['article', 'name']);
    }

    /**
     * Test product creation fails with duplicate article.
     */
    public function test_product_creation_fails_with_duplicate_article(): void
    {
        Sanctum::actingAs($this->user);

        Product::factory()->create(['article' => 'TEST001']);

        $productData = [
            'article' => 'TEST001',
            'name' => 'Test Product Name',
            'status' => 'available',
        ];

        $response = $this->postJson('/api/products/', $productData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['article']);
    }

    /**
     * Test authenticated user can update a product.
     */
    public function test_authenticated_user_can_update_product(): void
    {
        Sanctum::actingAs($this->user);

        $product = Product::factory()->create([
            'article' => 'TEST001',
            'name' => 'Old Product Name',
            'status' => 'available',
        ]);

        $updateData = [
            'name' => 'Updated Product Name',
            'status' => 'unavailable',
        ];

        $response = $this->putJson('/api/products/' . $product->id, $updateData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'product' => [
                    'id',
                    'article',
                    'name',
                    'status',
                ],
                'message',
            ])
            ->assertJson([
                'product' => [
                    'name' => 'Updated Product Name',
                    'status' => 'unavailable',
                ],
            ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Product Name',
            'status' => 'unavailable',
        ]);
    }

    /**
     * Test authenticated user can delete a product (soft delete).
     */
    public function test_authenticated_user_can_delete_product(): void
    {
        Sanctum::actingAs($this->user);

        $product = Product::factory()->create();

        $response = $this->deleteJson('/api/products/' . $product->id);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Продукт успешно удален.']);

        $this->assertSoftDeleted('products', [
            'id' => $product->id,
        ]);
    }

    /**
     * Test updating non-existent product returns 404.
     */
    public function test_updating_non_existent_product_returns_404(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->putJson('/api/products/99999', [
            'name' => 'Updated Product Name',
        ]);

        $response->assertStatus(404);
    }

    /**
     * Test deleting non-existent product returns 404.
     */
    public function test_deleting_non_existent_product_returns_404(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->deleteJson('/api/products/99999');

        $response->assertStatus(404);
    }
}
