<?php

namespace App\Middleware\Handlers;

use App\DD;
use App\Middleware\Middleware;
use App\Redirect\Redirect;

class AuthorisedMiddleware implements Middleware
{
    public function handle(): void
    {
        if(isset($_SESSION['id']))
        {
            Redirect::to('/');
        }
    }
}