<?php

namespace App\Services\AddMessages;

class AddMessageService
{
    public function add(string $message): void
    {
        $_SESSION['message'] = $message;
    }
}