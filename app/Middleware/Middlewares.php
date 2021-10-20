<?php

use App\DD;
use App\Middleware\Handlers\AuthorisedMiddleware;
use App\Middleware\Handlers\LoggedInMiddleware;
use App\Middleware\Handlers\ProcessInputMiddleware;

$middlewares = [
    'UsersController@showLogin' => [
        AuthorisedMiddleware::class
    ],
    'UsersController@login' => [
        AuthorisedMiddleware::class,
        ProcessInputMiddleware::class
    ],
    'UsersController@showRegistration' => [
        AuthorisedMiddleware::class
    ],
    'UsersController@registerUser' => [
        AuthorisedMiddleware::class,
        ProcessInputMiddleware::class
    ],
    'ProductsController@showProduct' => [
        LoggedInMiddleware::class
    ],
    'ProductsController@showEditProduct' => [
        LoggedInMiddleware::class
    ],
    'ProductsController@editProduct' => [
        LoggedInMiddleware::class,
        ProcessInputMiddleware::class
    ],
    'ProductsController@deleteProduct' => [
        LoggedInMiddleware::class
    ],
    'ProductsController@showAddProduct' => [
        LoggedInMiddleware::class
    ],
    'ProductsController@addProduct' => [
        LoggedInMiddleware::class
    ],
    'ProductsController@searchProducts' => [
        LoggedInMiddleware::class
    ],
    'ProductsController@searchByTags' => [
        LoggedInMiddleware::class
    ],
    'UsersController@logout' => [
        LoggedInMiddleware::class
    ],
    'UsersController@showEdit' => [
        LoggedInMiddleware::class
    ],
    'UsersController@editUser' => [
        LoggedInMiddleware::class,
        ProcessInputMiddleware::class
    ],
    'UsersController@deleteUser' => [
        LoggedInMiddleware::class
    ]
];

if(isset($middlewares[$handler]))
{
    foreach($middlewares[$handler] as $middleware)
    {
        $middleware = new $middleware();
        $middleware->handle();
    }
}