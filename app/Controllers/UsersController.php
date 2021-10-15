<?php

namespace App\Controllers;

use App\Redirect\Redirect;
use App\Repositories\MySQLUsersRepository;
use App\Repositories\UsersRepository;
use App\Twig\View;

class UsersController
{
    private UsersRepository $usersRepository;

    public function __construct()
    {
        $this->usersRepository = new MySQLUsersRepository();
    }

    public function showLogin(): View
    {
        return new View('Users/login.twig');
    }

    public function showRegistration(): View
    {
        return new View('Users/register.twig');
    }

    public function showEdit(): View
    {
        //todo create view for editing user
    }

    public function login(): void
    {
        //todo create login
    }

    public function register(): void
    {
        //todo create registration
    }

    public function editUser(): void
    {
        //todo create functionality to edit users information
    }

    public function deleteUser(): void
    {
        //todo create functionality to delete user
    }

    public function logout(): void
    {
        //todo create logout functionality for user
    }
}