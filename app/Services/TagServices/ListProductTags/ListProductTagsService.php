<?php

namespace App\Services\TagServices\ListProductTags;

use App\Models\Collections\TagsCollection;
use App\Repositories\TagsRepository\TagsRepository;

class ListProductTagsService
{
    private TagsRepository $tagsRepository;

    public function __construct(TagsRepository $tagsRepository)
    {
        $this->tagsRepository = $tagsRepository;
    }

    public function execute(ListProductTagsRequest $request): ?TagsCollection
    {
        return $this->tagsRepository->getProductTags($request->getProductId());
    }
}