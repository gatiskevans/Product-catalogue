<?php

namespace App\Controllers;

use App\DD;
use App\Models\User;
use App\Redirect\Redirect;
use App\Repositories\MySQLUsersRepository;
use App\Repositories\UsersRepository;
use App\Twig\View;
use Ramsey\Uuid\Uuid;

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
        $user = $this->usersRepository->getById($_SESSION['id']);

        return new View('Users/profile.twig', ['user' => $user]);
    }

    public function login(): void
    {
        $user = $this->usersRepository->getByEmail($_POST['email']);

        if($user !== null && password_verify($_POST['password'], $user->getPassword())){
            $_SESSION['id'] = $user->getUserId();
            $_SESSION['name'] = $user->getName();
            $_SESSION['email'] = $user->getEmail();
            Redirect::to('/');
        } else {
            Redirect::to('/login');
        }
    }

    public function registerUser(): void
    {
        if($_POST['password'] !== $_POST['password_confirmation']) Redirect::to('/');

        $this->usersRepository->register(new User(
            Uuid::uuid4(),
            $_POST['email'],
            $_POST['name'],
            password_hash($_POST['password'], PASSWORD_DEFAULT)
        ));
        Redirect::to('/');
    }

    public function editUser(): void
    {
        $this->usersRepository->edit($_POST, $_SESSION['id']);
        Redirect::to('/');
    }

    public function deleteUser(): void
    {
        $this->usersRepository->delete($_SESSION['id']);
        $this->logout();
        Redirect::to('/');
    }

    public function logout(): void
    {
        unset($_SESSION['id']);
        unset($_SESSION['email']);
        unset($_SESSION['name']);
        Redirect::to('/');
    }
}