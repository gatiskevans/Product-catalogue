<?php

namespace App\Models;

class User
{
    private string $userId;
    private string $name;
    private string $email;
    private string $password;

    public function __construct(string $userId, string $name, string $email, string $password)
    {
        $this->userId = $userId;
        $this->name = $name;
        $this->email = $email;
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