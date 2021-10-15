<?php

namespace App\Repositories;

use App\DD;
use App\Models\User;
use App\MySQLConnect\MySQLConnect;

class MySQLUsersRepository extends MySQLConnect implements UsersRepository
{

    public function login(string $email): void
    {
        // TODO: Implement login() method.
    }

    public function register(User $user): void
    {
        $sql = "INSERT INTO users (user_id, email, name, password) VALUES (?, ?, ?, ?)";
        $this->connect()->prepare($sql)->execute([
            $user->getUserId(),
            $user->getEmail(),
            $user->getName(),
            $user->getPassword()
        ]);
    }

    public function edit(string $id): void
    {
        // TODO: Implement edit() method.
    }

    public function delete(string $id): void
    {
        // TODO: Implement delete() method.
    }

    public function logout(string $id): void
    {
        // TODO: Implement logout() method.
    }
}