<?php

use App\DD;
use App\Twig\View;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require 'vendor/autoload.php';

session_start();

//Twig Implementation
$loader = new FilesystemLoader('app/Views');
$twigEngine = new Environment($loader);

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/', 'ProductsController@index');
    $r->addRoute('GET', '/products', 'ProductsController@index');
    $r->addRoute('GET', '/product/{id}', 'ProductsController@showProduct');
    $r->addRoute('GET', '/add', 'ProductsController@showAddProduct');
    $r->addRoute('POST', '/add', 'ProductsController@addProduct');
    $r->addRoute('GET', '/edit/{id}', 'ProductsController@showEditProduct');
    $r->addRoute('POST', '/edit/{id}', 'ProductsController@editProduct');
    $r->addRoute('POST', '/delete/{id}', 'ProductsController@deleteProduct');
    $r->addRoute('GET', '/search', 'ProductsController@searchByCategory');
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

        [$controller, $method] = explode('@', $handler);
        $controller = "App\\Controllers\\" . $controller;
        $controller = new $controller;
        $response = $controller->$method($vars);

        if ($response instanceof View) {
            echo $twigEngine->render($response->getTemplate(), $response->getVariables());
        }

        break;
}