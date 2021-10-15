<?php

namespace App\Redirect;

class Redirect
{
    public static function to(string $location): void
    {
        header('Location: ' . $location);
        exit;
    }
}