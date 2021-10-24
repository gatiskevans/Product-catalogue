<?php

namespace App\Validation;

use App\Messages\Messages;
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
            $this->errors['user'] = Messages::USER_EXISTS;
        }

        if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL))
        {
            $this->errors['email'] = Messages::INVALID_EMAIL;
        }

        if(empty($data['email']))
        {
            $this->errors['email'] = Messages::EMAIL_REQUIRED;
        }

        if(empty($data['name']))
        {
            $this->errors['name'] = Messages::NAME_REQUIRED;
        }

        if(strlen($data['name']) > 100)
        {
            $this->errors['name'] = Messages::NAME_TOO_LONG;
        }

        if($data['password'] !== $data['password_confirmation'])
        {
            $this->errors['password'] = Messages::PASSWORDS_DONT_MATCH;
        }

        if(strlen($data['password']) < 6)
        {
            $this->errors['password'] = Messages::PASSWORD_TOO_SHORT;
        }

        if(empty($data['password']))
        {
            $this->errors['password'] = Messages::PASSWORD_REQUIRED;
        }

        if(empty($data['password_confirmation']))
        {
            $this->errors['password_confirmation'] = Messages::PASSWORD_CONFIRM_REQUIRED;
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
            $this->errors['email'] = Messages::USER_DONT_EXIST;
        }

        if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL))
        {
            $this->errors['email'] = Messages::INVALID_EMAIL;
        }

        if(empty($data['email']))
        {
            $this->errors['email'] = Messages::EMAIL_REQUIRED;
        }

        if($user && !password_verify($data['password'], $user->getPassword()) || $data['password'] < 6)
        {
            $this->errors['password'] = Messages::WRONG_PASSWORD;
        }

        if(empty($data['password']))
        {
            $this->errors['password'] = Messages::PASSWORD_REQUIRED;
        }

        if(count($this->errors) > 0)
        {
            throw new FormValidationException();
        }
    }
}