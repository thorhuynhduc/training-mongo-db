<?php
require_once __DIR__ . '/vendor/autoload.php';

use Src\Routes\Router;
use Src\Controllers\UserController;

// Tạo router
$router = new Router();

// Định nghĩa routes
$router->addRoute('GET', '/users', [UserController::class, 'index']);
$router->addRoute('GET', '/users/{id}', [UserController::class, 'show']);
$router->addRoute('POST', '/users', [UserController::class, 'create']);
$router->addRoute('PUT', '/users/{id}', [UserController::class, 'update']);
$router->addRoute('DELETE', '/users/{id}', [UserController::class, 'delete']);

// Xử lý request
$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI'];

$router->dispatch($requestMethod, $requestUri);