<?php

namespace App\Validation;

use App\Messages\Messages;
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
           $this->errors['title'] = Messages::TITLE_EXISTS;
        }

        if(empty($data['title']))
        {
            $this->errors['title'] = Messages::TITLE_REQUIRED;
        }

        if(empty($data['category']))
        {
            $this->errors['category'] = Messages::CATEGORY_REQUIRED;
        }

        if(!is_numeric($data['quantity']))
        {
            $this->errors['quantity'] = Messages::QUANTITY_NUMERIC;
        }

        if(empty($data['quantity']))
        {
            $this->errors['quantity'] = Messages::QUANTITY_REQUIRED;
        }

        if(!in_array(strtolower($data['category']), Product::CATEGORIES))
        {
            $this->errors['category'] = Messages::INVALID_CATEGORY;
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
            $this->errors['category'] = Messages::INVALID_CATEGORY;
        }

        [$sortBy, $order] = explode("@", $data['sort']);
        $values = ['created_at', 'title', 'category', 'quantity', 'edited_at'];
        $orderOptions = ['ASC', 'DESC'];

        if(!in_array($sortBy, $values))
        {
            $this->errors['sort'] = Messages::INVALID_SORT;
        }

        if(!in_array($order, $orderOptions))
        {
            $this->errors['order'] = Messages::INVALID_ORDER;
        }

        if(count($this->errors) > 0)
        {
            throw new FormValidationException();
        }
    }
}