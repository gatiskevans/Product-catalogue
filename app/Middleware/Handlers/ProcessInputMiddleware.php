<?php

namespace App\Middleware\Handlers;

use App\Middleware\Middleware;

class ProcessInputMiddleware implements Middleware
{
    public function handle(): void
    {
        foreach($_POST as $key => $data)
        {
            if($_POST['tags']) continue;
            $_POST[$key] = trim($data);
        }
    }
}