<?php

namespace App\Repositories;

use App\Models\Collections\TagsCollection;

interface TagsRepository
{
    public function getAll(): TagsCollection;
    public function getTags(string $id): ?TagsCollection;
}