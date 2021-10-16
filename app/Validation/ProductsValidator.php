<?php

namespace App\Validation;

use App\DD;
use App\Models\Collections\ProductsCollection;

abstract class ProductsValidator
{
    private array $errors = [];

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function validateProduct(array $data): void
    {
        if(empty($data['title']))
        {
            $this->errors['title'] = "Title is required";
        }

        if(empty($data['category']))
        {
            $this->errors['category'] = "Category is required";
        }

        if(empty($data['quantity']))
        {
            $this->errors['quantity'] = "Quantity is required";
        }

        if(count($this->errors) > 0)
        {
            throw new FormValidationException();
        }
    }
}