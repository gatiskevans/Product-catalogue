<?php

namespace App\Repositories;

interface UsersRepository
{
    public function login(string $email): void;
    public function register(): void;
    public function edit(string $id): void;
    public function delete(string $id): void;
    public function logout(string $id): void;
}