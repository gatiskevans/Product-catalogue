<?php

namespace App\Services\ProductServices\Search;

class SearchServiceRequest
{
    private array $formData;
    private array $sessionData;

    public function __construct(array $formData, array $sessionData)
    {
        $this->formData = $formData;
        $this->sessionData = $sessionData;
    }

    public function getId(): string
    {
        return $this->sessionData['id'];
    }

    public function getCategory(): string
    {
        return $this->formData['category'];
    }

    public function getSort(): string
    {
        return $this->formData['sort'];
    }
}