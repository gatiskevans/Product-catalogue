<?php

namespace App\Services\ProductServices\ListAllProducts;

class ListAllProductsRequest
{
    private array $sessionData;

    public function __construct(array $sessionData)
    {
        $this->sessionData = $sessionData;
    }

    public function getUserId(): string
    {
        return $this->sessionData['id'];
    }
}