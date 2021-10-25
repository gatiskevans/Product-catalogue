<?php

namespace App\Services\ProductServices\Search;

use App\Models\Collections\ProductsCollection;
use App\Repositories\ProductsRepository\ProductsRepository;

class SearchService
{
    private ProductsRepository $productsRepository;

    public function __construct(ProductsRepository $productsRepository)
    {
        $this->productsRepository = $productsRepository;
    }

    public function execute(SearchServiceRequest $request): ProductsCollection
    {
        return $this->productsRepository->search($request->getCategory(), $request->getId(), $request->getSort());
    }
}