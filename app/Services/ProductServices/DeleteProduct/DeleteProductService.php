<?php

namespace App\Services\ProductServices\DeleteProduct;

use App\Repositories\ProductsRepository\ProductsRepository;

class DeleteProductService
{
    private ProductsRepository $productsRepository;

    public function __construct(ProductsRepository $productsRepository)
    {
        $this->productsRepository = $productsRepository;
    }

    public function execute(DeleteProductRequest $request): void
    {
        $this->productsRepository->delete($request->getProductId());
    }
}