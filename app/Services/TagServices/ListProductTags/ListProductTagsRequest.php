<?php

namespace App\Services\TagServices\ListProductTags;

class ListProductTagsRequest
{
    private string $productId;

    public function __construct(string $productId)
    {
        $this->productId = $productId;
    }

    public function getProductId(): string
    {
        return $this->productId;
    }
}