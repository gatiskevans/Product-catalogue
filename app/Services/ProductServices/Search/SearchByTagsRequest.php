<?php

namespace App\Services\ProductServices\Search;

class SearchByTagsRequest
{
    private array $formData;

    public function __construct(array $formData)
    {
        $this->formData = $formData;
    }

    public function getTags(): array
    {
        return $this->formData['tags'];
    }
}