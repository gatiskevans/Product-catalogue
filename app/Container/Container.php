<?php

namespace App\Container;

class Container
{
    private array $container = [];

    public function register(string $interface, object $newInstance): void
    {
        $this->container[$interface] = $newInstance;
    }

    public function getContainer(): array
    {
        return $this->container;
    }
}