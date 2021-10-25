<?php

namespace App\Services\ProductServices\Search;

use App\Models\Collections\ProductsCollection;
use App\Repositories\ProductsRepository\ProductsRepository;

class SearchByTagsService
{
    private ProductsRepository $productsRepository;

    public function __construct(ProductsRepository $productsRepository)
    {
        $this->productsRepository = $productsRepository;
    }

    public function execute(SearchByTagsRequest $request): ProductsCollection
    {
        return $this->productsRepository->searchByTags($request->getTags());
    }
}