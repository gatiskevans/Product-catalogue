<?php

namespace App\Services\ProductServices\GetProduct;

class GetProductRequest
{
    private string $productId;
    private array $session;

    public function __construct(string $productId, array $session)
    {
        $this->productId = $productId;
        $this->session = $session;
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function getUserId(): string
    {
        return $this->session['id'];
    }
}