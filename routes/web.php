<?php

use App\Controllers\CatalogController;
use App\Controllers\AuthController;
use App\Controllers\SuggestController;
use App\Controllers\DetailsController;

/*
|--------------------------------------------------------------------------
| CATALOG
|--------------------------------------------------------------------------
*/
$router->get('home', [CatalogController::class, 'home']);
$router->get('catalog', [CatalogController::class, 'index']);
$router->get('details', [DetailsController::class, 'show']);

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
$router->get('register', [AuthController::class, 'showRegister']);
$router->post('register-submit', [AuthController::class, 'register']);

$router->get('login', [AuthController::class, 'showLogin']);
$router->post('login-submit', [AuthController::class, 'login']);
$router->get('logout', [App\Controllers\AuthController::class, 'logout']);
/*
|--------------------------------------------------------------------------
| SUGGEST
|--------------------------------------------------------------------------
*/
$router->get('suggest', [SuggestController::class, 'index']);