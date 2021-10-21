<?php

use App\DD;
use App\Container\Container;
use App\Models\Product;
use App\Repositories\MySQLProductsRepository;
use App\Repositories\MySQLUsersRepository;
use App\Repositories\ProductsRepository;
use App\Repositories\UsersRepository;
use App\Twig\View;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require 'vendor/autoload.php';

session_start();

$container = new Container();
$container->register(UsersRepository::class, new MySQLUsersRepository());
$container->register(ProductsRepository::class, new MySQLProductsRepository());

//Twig Implementation
$loader = new FilesystemLoader('app/Views');
$twigEngine = new Environment($loader);
$twigEngine->addGlobal('session', $_SESSION);
$twigEngine->addGlobal('categories', Product::CATEGORIES);

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/', 'ProductsController@index');

    $r->addRoute('GET', '/products', 'ProductsController@index');
    $r->addRoute('GET', '/product/{id}', 'ProductsController@showProduct');

    $r->addRoute('GET', '/add', 'ProductsController@showAddProduct');
    $r->addRoute('POST', '/add', 'ProductsController@addProduct');

    $r->addRoute('GET', '/edit/{id}', 'ProductsController@showEditProduct');
    $r->addRoute('POST', '/edit/{id}', 'ProductsController@editProduct');

    $r->addRoute('POST', '/delete/{id}', 'ProductsController@deleteProduct');
    $r->addRoute('GET', '/search', 'ProductsController@searchProducts');
    $r->addRoute('GET', '/tags', 'ProductsController@searchByTags');

    $r->addRoute('GET', '/login', 'UsersController@showLogin');
    $r->addRoute('POST', '/login', 'UsersController@login');
    $r->addRoute('GET', '/logout', 'UsersController@logout');

    $r->addRoute('GET', '/register', 'UsersController@showRegistration');
    $r->addRoute('POST', '/register', 'UsersController@registerUser');

    $r->addRoute('GET', '/profile', 'UsersController@showEdit');
    $r->addRoute('POST', '/profile', 'UsersController@editUser');
    $r->addRoute('POST', '/delete', 'UsersController@deleteUser');
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];

        require_once 'app/Middleware/Middlewares.php';

        if(isset($middlewares[$handler]))
        {
            foreach($middlewares[$handler] as $middleware)
            {
                $middleware = new $middleware();
                $middleware->handle();
            }
        }

        [$controller, $method] = explode('@', $handler);
        $controller = "App\\Controllers\\" . $controller;
        $controller = new $controller($container->getContainer());
        $response = $controller->$method($vars);



        if ($response instanceof View) {
            echo $twigEngine->render($response->getTemplate(), $response->getVariables());
        }

        break;
}

unset($_SESSION['_errors']);
unset($_SESSION['message']);