<?php

namespace App\Services\ProductServices\EditProduct;

class EditProductRequest
{
    private array $postData;
    private string $productId;

    public function __construct(array $postData, string $productId)
    {
        $this->postData = $postData;
        $this->productId = $productId;
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function getPostData(): array
    {
        return $this->postData;
    }
}