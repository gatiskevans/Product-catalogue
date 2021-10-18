<?php

namespace App\Models;

class Tag
{
    private string $tagId;
    private string $tag;

    public function __construct(string $tagId, string $tag)
    {
        $this->tagId = $tagId;
        $this->tag = $tag;
    }

    public function getTagId(): string
    {
        return $this->tagId;
    }

    public function getTag(): string
    {
        return $this->tag;
    }
}