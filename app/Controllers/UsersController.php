<?php

namespace App\Controllers;

use App\DD;
use App\Messages\Messages;
use App\Models\User;
use App\Redirect\Redirect;
use App\Repositories\UsersRepository\MySQLUsersRepository;
use App\Repositories\UsersRepository\UsersRepository;
use App\Twig\View;
use App\Validation\FormValidationException;
use App\Validation\UsersValidator;
use Ramsey\Uuid\Uuid;

class UsersController extends UsersValidator
{
    private UsersRepository $usersRepository;

    public function __construct(MySQLUsersRepository $usersRepository)
    {
        $this->usersRepository = $usersRepository;
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
        try {
            $user = $this->usersRepository->getByEmail($_POST['email']);
            $this->validateLogin($_POST, $user);

            $_SESSION['id'] = $user->getUserId();
            $_SESSION['name'] = $user->getName();
            $_SESSION['email'] = $user->getEmail();
            $_SESSION['message'] = Messages::LOGIN_SUCCESS;
            Redirect::to('/');
        } catch(FormValidationException $exception)
        {
            $_SESSION['_errors'] = $this->getErrors();
            Redirect::to('/login');
        }
    }

    public function registerUser(): void
    {
        try {
            $user = $this->usersRepository->getByEmail($_POST['email']);
            $this->validateUserData($_POST, $user);

            $this->usersRepository->register(new User(
                Uuid::uuid4(),
                $_POST['email'],
                $_POST['name'],
                password_hash($_POST['password'], PASSWORD_DEFAULT)
            ));

            $_SESSION['message'] = Messages::REGISTRATION_SUCCESS;

            Redirect::to('/login');
        } catch (FormValidationException $exception)
        {
            $_SESSION['_errors'] = $this->getErrors();
            Redirect::to('/register');
        }

    }

    public function editUser(): void
    {
        try {
            $user = $this->usersRepository->getById($_SESSION['id']);
            $this->validateUserData($_POST, $user);
            $this->usersRepository->edit($_POST, $_SESSION['id']);
            $_SESSION['message'] = Messages::USER_UPDATE_SUCCESS;
            Redirect::to('/profile');
        } catch (FormValidationException $exception)
        {
            $_SESSION['_errors'] = $this->getErrors();
            Redirect::to('/profile');
        }

    }

    public function deleteUser(): void
    {
        $this->usersRepository->delete($_SESSION['id']);
        $_SESSION['message'] = Messages::USER_DELETE_SUCCESS;
        $this->logout();
    }

    public function logout(): void
    {
        unset($_SESSION['id']);
        unset($_SESSION['email']);
        unset($_SESSION['name']);
        Redirect::to('/');
    }
}