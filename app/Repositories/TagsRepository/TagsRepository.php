<?php

namespace App\Repositories\TagsRepository;

use App\Models\Collections\TagsCollection;

interface TagsRepository
{
    public function getAll(): TagsCollection;
    public function getProductTags(string $id): ?TagsCollection;
    public function getByName(array $tags): TagsCollection;
    public function deleteTags(string $productId, TagsCollection $tags): void;
    public function insertTags(string $productId, TagsCollection $tags): void;
    public function toArray(string $id): ?array;
}