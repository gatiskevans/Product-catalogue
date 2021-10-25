<?php

namespace App\Services\TagServices\ListAllTags;

use App\Models\Collections\TagsCollection;
use App\Repositories\TagsRepository\TagsRepository;

class ListAllTagsService
{
    private TagsRepository $tagsRepository;

    public function __construct(TagsRepository $tagsRepository)
    {
        $this->tagsRepository = $tagsRepository;
    }

    public function execute(): TagsCollection
    {
        return $this->tagsRepository->getAll();
    }
}