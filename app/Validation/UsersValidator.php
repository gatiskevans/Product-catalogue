<?php

namespace App\Validation;

use App\Models\User;

abstract class UsersValidator
{
    private array $errors = [];

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function validateUserData(array $data, ?User $user): void
    {
        if($user && !isset($_SESSION['id']))
        {
            $this->errors['user'] = "User with this email already exists";
        }

        if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL))
        {
            $this->errors['email'] = "Email format is incorrect";
        }

        if(empty($data['email']))
        {
            $this->errors['email'] = "Email is required";
        }

        if(empty($data['name']))
        {
            $this->errors['name'] = "Name is required";
        }

        if(strlen($data['name']) > 100)
        {
            $this->errors['name'] = "Name is too long";
        }

        if($data['password'] !== $data['password_confirmation'])
        {
            $this->errors['password'] = "Passwords do not match";
        }

        if(strlen($data['password']) < 6)
        {
            $this->errors['password'] = "Password must be at least 6 characters long";
        }

        if(empty($data['password']))
        {
            $this->errors['password'] = "Password is required";
        }

        if(empty($data['password_confirmation']))
        {
            $this->errors['password_confirmation'] = "Password confirmation is required";
        }

        if(count($this->errors) > 0)
        {
            throw new FormValidationException();
        }
    }

    public function validateLogin(array $data, ?User $user): void
    {
        if($user === null)
        {
            $this->errors['email'] = "Cannot find user with this email";
        }

        if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL))
        {
            $this->errors['email'] = "Email format is incorrect";
        }

        if(empty($data['email']))
        {
            $this->errors['email'] = "Email is required";
        }

        if($user && !password_verify($data['password'], $user->getPassword()) || $data['password'] < 6)
        {
            $this->errors['password'] = "Wrong password";
        }

        if(empty($data['password']))
        {
            $this->errors['password'] = "Password is required";
        }

        if(count($this->errors) > 0)
        {
            throw new FormValidationException();
        }
    }
}