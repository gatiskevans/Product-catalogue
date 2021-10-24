<?php

namespace App\Repositories\UsersRepository;

use App\Models\User;
use App\MySQLConnect\MySQLConnect;
use PDO;

class MySQLUsersRepository extends MySQLConnect implements UsersRepository
{
    public function getByEmail(string $email): ?User
    {
        $sql = "SELECT * FROM users WHERE email=?";
        $statement = $this->connect()->prepare($sql);
        $statement->execute([$email]);
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        if(!$user) return null;

        return $this->buildUser($user);
    }

    public function getById(string $id): ?User
    {
        $sql = "SELECT * FROM users WHERE user_id=?";
        $statement = $this->connect()->prepare($sql);
        $statement->execute([$id]);
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        if(!$user) return null;

        return $this->buildUser($user);
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

    private function buildUser(array $user): User
    {
        return new User(
            $user['user_id'],
            $user['email'],
            $user['name'],
            $user['password']
        );
    }

    public function edit(array $info, string $id): void
    {
        $sql = "UPDATE users SET email=?, name=? WHERE user_id=?";
        $this->connect()->prepare($sql)->execute([
            $info['email'],
            $info['name'],
            $id
        ]);

        $_SESSION['email'] = $info['email'];
        $_SESSION['name'] = $info['name'];
    }

    public function delete(string $id): void
    {
        $sql = "DELETE FROM users WHERE user_id=?";
        $this->connect()->prepare($sql)->execute([$id]);
    }
}