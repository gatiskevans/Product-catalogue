<?php

namespace App\Services\ProductServices\AddProduct;

use App\Repositories\ProductsRepository\ProductsRepository;
use App\Services\ProductServices\AddProduct\AddProductRequest;

class AddProductService
{
    private ProductsRepository $productsRepository;

    public function __construct(ProductsRepository $productsRepository)
    {
        $this->productsRepository = $productsRepository;
    }

    public function checkIfAvailable(AddProductRequest $request): bool
    {
        return $this->productsRepository->getByTitle($request->getPostData(), $request->getId());
    }

    public function execute(AddProductRequest $request): void
    {
        $this->productsRepository->add($request->getPostData(), $request->getId());
    }
}