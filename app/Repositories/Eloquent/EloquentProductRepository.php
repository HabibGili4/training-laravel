<?php

namespace App\Repositories\Eloquent;

use App\Contracts\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EloquentProductRepository implements ProductRepositoryInterface
{
    /**
     * Get all products.
     *
     * @return Collection<int, Product>
     */
    public function getAll(): Collection
    {
        return Product::all();
    }

    /**
     * Get product by ID.
     *
     * @param  int  $id
     * @return Product|null
     */
    public function findById(int $id): ?Model
    {
        return Product::find($id);
    }

    /**
     * Create a new product.
     *
     * @param  array<string, mixed>  $data
     * @return Product
     */
    public function create(array $data): Model
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
     * @throws ModelNotFoundException
     */
    public function update(int $id, array $data): Model
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
     * @throws ModelNotFoundException
     */
    public function delete(int $id): bool
    {
        $product = Product::findOrFail($id);

        return $product->delete();
    }
}
