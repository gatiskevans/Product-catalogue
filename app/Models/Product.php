<?php

namespace App\Models;

class Product
{
    private string $id;
    private string $title;
    private string $category;
    private int $quantity;
    private string $createdAt;
    private ?string $editedAt;

    public function __construct(
        string $id,
        string $title,
        string $category,
        int $quantity,
        string $createdAt,
        ?string $editedAt = null
    )
    {

        $this->id = $id;
        $this->title = $title;
        $this->category = $category;
        $this->quantity = $quantity;
        $this->createdAt = $createdAt;
        $this->editedAt = $editedAt;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getEditedAt(): ?string
    {
        return $this->editedAt;
    }
}