<?php

namespace App\Models\Collections;

use App\Models\User;

class UsersCollection
{
    private array $users = [];

    public function __construct(array $users = [])
    {
        foreach($users as $user) $this->add($user);
    }

    public function add(User $user): void
    {
        $this->users[$user->getUserId()] = $user;
    }

    public function getUsers(): array
    {
        return $this->users;
    }

    public function removeUser(User $user): void
    {
        unset($this->users[$user->getUserId()]);
    }
}