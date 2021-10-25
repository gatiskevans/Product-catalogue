<?php

namespace App\Services\ProductServices\ListAllProducts;

use App\Models\Collections\ProductsCollection;
use App\Repositories\ProductsRepository\ProductsRepository;
use App\Services\ProductServices\ListAllProducts\ListAllProductsRequest;

class ListAllProductsService
{
    private ProductsRepository $productsRepository;

    public function __construct(ProductsRepository $productsRepository)
    {
        $this->productsRepository = $productsRepository;
    }

    public function execute(ListAllProductsRequest $request): ProductsCollection
    {
        return $this->productsRepository->getAll($request->getUserId());
    }
}