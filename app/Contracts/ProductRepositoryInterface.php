<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface ProductRepositoryInterface
{
    /**
     * Get all products.
     *
     * @return Collection<int, Model>
     */
    public function getAll(): Collection;

    /**
     * Get product by ID.
     *
     * @param  int  $id
     * @return Model|null
     */
    public function findById(int $id): ?Model;

    /**
     * Create a new product.
     *
     * @param  array<string, mixed>  $data
     * @return Model
     */
    public function create(array $data): Model;

    /**
     * Update an existing product.
     *
     * @param  int  $id
     * @param  array<string, mixed>  $data
     * @return Model
     */
    public function update(int $id, array $data): Model;

    /**
     * Delete a product.
     *
     * @param  int  $id
     * @return bool
     */
    public function delete(int $id): bool;
}
