<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_product_success(): void
    {
        $payload = [
            'name' => 'Kopi Arabica',
            'price' => 25000,
            'stock' => 10,
        ];

        $response = $this->postJson('/api/products', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Product created successfully',
                'data' => [
                    'name' => 'Kopi Arabica',
                    'price' => '25000.00',
                    'stock' => 10,
                ],
            ]);

        $this->assertDatabaseHas('products', [
            'name' => 'Kopi Arabica',
            'price' => 25000,
            'stock' => 10,
        ]);
    }

    public function test_create_product_validation_error(): void
    {
        $payload = [
            'name' => '',
            'price' => -100,
            'stock' => -5,
        ];

        $response = $this->postJson('/api/products', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'price', 'stock']);
    }

    public function test_get_products_success(): void
    {
        Product::factory()->count(3)->create();

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Products retrieved successfully',
            ])
            ->assertJsonCount(3, 'data');
    }

    public function test_get_product_by_id_success(): void
    {
        $product = Product::factory()->create([
            'name' => 'Kopi Robusta',
            'price' => 30000,
            'stock' => 20,
        ]);

        $response = $this->getJson("/api/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Product retrieved successfully',
                'data' => [
                    'id' => $product->id,
                    'name' => 'Kopi Robusta',
                    'price' => '30000.00',
                    'stock' => 20,
                ],
            ]);
    }

    public function test_get_product_by_id_not_found(): void
    {
        $response = $this->getJson('/api/products/9999');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Product not found',
            ]);
    }

    public function test_update_product_success(): void
    {
        $product = Product::factory()->create([
            'name' => 'Kopi Lama',
            'price' => 20000,
            'stock' => 5,
        ]);

        $payload = [
            'name' => 'Kopi Baru',
            'price' => 35000,
            'stock' => 15,
        ];

        $response = $this->putJson("/api/products/{$product->id}", $payload);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Product updated successfully',
                'data' => [
                    'id' => $product->id,
                    'name' => 'Kopi Baru',
                    'price' => '35000.00',
                    'stock' => 15,
                ],
            ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Kopi Baru',
            'price' => 35000,
            'stock' => 15,
        ]);
    }

    public function test_update_product_validation_error(): void
    {
        $product = Product::factory()->create();

        $payload = [
            'price' => -100,
        ];

        $response = $this->putJson("/api/products/{$product->id}", $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['price']);
    }

    public function test_delete_product_success(): void
    {
        $product = Product::factory()->create();

        $response = $this->deleteJson("/api/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Product deleted successfully',
            ]);

        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);
    }
}
