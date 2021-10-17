<?php

namespace App\Validation;

use App\Models\Product;

abstract class ProductsValidator
{
    private array $errors = [];

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function validateProduct(array $data, ?bool $productExists = null): void
    {
        if($productExists)
        {
           $this->errors['title'] = "Product already exists.";
        }

        if(empty($data['title']))
        {
            $this->errors['title'] = "Title is required";
        }

        if(empty($data['category']))
        {
            $this->errors['category'] = "Category is required";
        }

        if(!is_numeric($data['quantity']))
        {
            $this->errors['quantity'] = "Quantity must be a number";
        }

        if(empty($data['quantity']))
        {
            $this->errors['quantity'] = "Quantity is required";
        }

        if(!in_array(strtolower($data['category']), Product::CATEGORIES))
        {
            $this->errors['category'] = "Invalid category";
        }

        if(count($this->errors) > 0)
        {
            throw new FormValidationException();
        }
    }

    public function validateSearch(array $data): void
    {
        if(!in_array(strtolower($data['category']), Product::CATEGORIES) && $data['category'] !== 'all')
        {
            $this->errors['category'] = "Invalid category";
        }

        if(count($this->errors) > 0)
        {
            throw new FormValidationException();
        }
    }
}