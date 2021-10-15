<?php

namespace App\Models;

class User
{
    private string $userId;
    private string $email;
    private string $name;
    private string $password;

    public function __construct(string $userId, string $email, string $name, string $password)
    {
        $this->userId = $userId;
        $this->email = $email;
        $this->name = $name;
        $this->password = $password;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}