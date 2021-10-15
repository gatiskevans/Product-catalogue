<?php

namespace App\Repositories;

use App\Models\User;

interface UsersRepository
{
    public function login(string $email): void;
    public function register(User $user): void;
    public function edit(string $id): void;
    public function delete(string $id): void;
    public function logout(string $id): void;
}