<?php

namespace App\Services\ProductServices\AddProduct;

class AddProductRequest
{
    private array $postData;
    private array $sessionData;

    public function __construct(array $postData, array $sessionData)
    {
        $this->postData = $postData;
        $this->sessionData = $sessionData;
    }

    public function getPostData(): array
    {
        return $this->postData;
    }

    public function getId(): string
    {
        return $this->sessionData['id'];
    }
}