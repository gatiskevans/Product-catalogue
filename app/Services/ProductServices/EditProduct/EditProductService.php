<?php

namespace App\Services\ProductServices\EditProduct;

use App\Repositories\ProductsRepository\ProductsRepository;

class EditProductService
{
    private ProductsRepository $productsRepository;

    public function __construct(ProductsRepository $productsRepository)
    {
        $this->productsRepository = $productsRepository;
    }

    public function execute(EditProductRequest $request): void
    {
        $this->productsRepository->edit($request->getPostData(), $request->getProductId());
    }
}