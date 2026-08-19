<?php

namespace App\Services;

use App\Contracts\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ProductService
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository,
    ) {}

    /**
     * Get all products.
     *
     * @return Collection<int, Model>
     */
    public function getProducts(): Collection
    {
        return $this->productRepository->getAll();
    }

    /**
     * Get product by ID.
     *
     * @param  int  $id
     * @return Model|null
     */
    public function getProduct(int $id): ?Model
    {
        return $this->productRepository->findById($id);
    }

    /**
     * Create a new product.
     *
     * @param  array<string, mixed>  $data
     * @return Model
     */
    public function createProduct(array $data): Model
    {
        return $this->productRepository->create($data);
    }

    /**
     * Update an existing product.
     *
     * @param  int  $id
     * @param  array<string, mixed>  $data
     * @return Model
     */
    public function updateProduct(int $id, array $data): Model
    {
        return $this->productRepository->update($id, $data);
    }

    /**
     * Delete a product.
     *
     * @param  int  $id
     * @return bool
     */
    public function deleteProduct(int $id): bool
    {
        return $this->productRepository->delete($id);
    }
}
