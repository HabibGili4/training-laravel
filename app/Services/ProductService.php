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
    public function getProducts(): Collection
    {
        return Product::all();
    }

    /**
     * Get product by ID.
     *
     * @param  int  $id
     * @return Product|null
     */
    public function getProduct(int $id): ?Product
    {
        return Product::find($id);
    }

    /**
     * Create a new product.
     *
     * @param  array<string, mixed>  $data
     * @return Product
     */
    public function createProduct(array $data): Product
    {
        return Product::create($data);
    }

    /**
     * Update an existing product.
     *
     * @param  int  $id
     * @param  array<string, mixed>  $data
     * @return Product
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function updateProduct(int $id, array $data): Product
    {
        $product = Product::findOrFail($id);
        $product->update($data);

        return $product;
    }

    /**
     * Delete a product.
     *
     * @param  int  $id
     * @return bool
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function deleteProduct(int $id): bool
    {
        $product = Product::findOrFail($id);

        return $product->delete();
    }
}
