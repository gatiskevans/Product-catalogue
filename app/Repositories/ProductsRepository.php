<?php

namespace App\Repositories;

use App\Models\Collections\ProductsCollection;
use App\Models\Product;

interface ProductsRepository
{
    public function getOne(string $id): Product;
    public function getByTitle(array $product): bool;
    public function getAll(): ProductsCollection;
    public function add(array $product): void;
    public function edit(array $product, string $id): void;
    public function delete(string $id): void;
    public function search(string $query): ProductsCollection;
}