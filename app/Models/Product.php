<?php

namespace App\Models;

class Product
{
    private string $id;
    private string $title;
    private ?string $category = '';
    private int $quantity;
    private string $createdAt;
    private ?string $editedAt;

    public const FRUITS = 'fruits';
    public const SWEETS = 'sweets';
    public const MEAT = 'meat';
    public const DAIRY = 'dairy';
    public const ALCOHOL = 'alcohol';
    public const PASTRIES = 'pastries';
    public const TAKEAWAYS = 'takeaways';
    public const SAUCES = 'sauces';
    public const FISH = 'fish';
    public const OTHERS = 'others';

    public const CATEGORIES = [
        self::FRUITS,
        self::SWEETS,
        self::MEAT,
        self::DAIRY,
        self::ALCOHOL,
        self::PASTRIES,
        self::TAKEAWAYS,
        self::SAUCES,
        self::FISH,
        self::OTHERS
    ];

    public function __construct(
        string $id,
        string $title,
        ?string $category,
        int $quantity,
        string $createdAt,
        ?string $editedAt = null
    )
    {
        $this->id = $id;
        $this->title = $title;
        $this->setCategory($category);
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

    public function setCategory(string $category): void
    {
        if(in_array($category, self::CATEGORIES)) return;
        $this->category = self::OTHERS;
    }
}