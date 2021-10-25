<?php

namespace App\Services\ProductServices\GetProduct;

use App\Models\Product;
use App\Repositories\ProductsRepository\ProductsRepository;

class GetProductService
{
    private ProductsRepository $productsRepository;

    public function __construct(ProductsRepository $productsRepository)
    {
        $this->productsRepository = $productsRepository;
    }

    public function execute(GetProductRequest $request): Product
    {
        return $this->productsRepository->getOne($request->getProductId(), $request->getUserId());
    }
}