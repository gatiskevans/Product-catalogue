<?php

namespace App\Middleware\Handlers;

use App\Middleware\Middleware;
use App\Redirect\Redirect;

class LoggedInMiddleware implements Middleware
{
    public function handle(): void
    {
        if(!isset($_SESSION['id']))
        {
            Redirect::to('/');
        }
    }
}