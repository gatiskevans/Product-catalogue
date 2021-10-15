<?php

namespace App\Repositories;

use App\MySQLConnect\MySQLConnect;

class MySQLUsersRepository extends MySQLConnect implements UsersRepository
{

    public function login(string $email): void
    {
        // TODO: Implement login() method.
    }

    public function register(): void
    {
        // TODO: Implement register() method.
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