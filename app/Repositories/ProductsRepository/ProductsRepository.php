<?php

namespace App\Repositories;

use App\Models\Collections\ProductsCollection;
use App\Models\Product;

interface ProductsRepository
{
    public function getOne(string $id, string $userId): Product;
    public function getByTitle(array $product, string $userId): bool;
    public function getAll(string $userId): ProductsCollection;
    public function add(array $product, string $userId): void;
    public function edit(array $product, string $id): void;
    public function delete(string $id): void;
    public function search(string $query, string $userId, string $sortBy): ProductsCollection;
}