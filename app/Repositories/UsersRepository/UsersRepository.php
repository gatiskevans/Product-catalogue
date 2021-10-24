<?php

namespace App\Repositories\UsersRepository;

use App\Models\User;

interface UsersRepository
{
    public function getByEmail(string $email): ?User;
    public function getById(string $id): ?User;
    public function register(User $user): void;
    public function edit(array $info, string $id): void;
    public function delete(string $id): void;
}