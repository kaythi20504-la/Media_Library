<?php

use App\Presentation\Controllers\CatalogController;
use App\Presentation\Controllers\AuthController;
use App\Presentation\Controllers\SuggestController;
use App\Presentation\Controllers\DetailsController;
use App\Presentation\Controllers\ReservationController;

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
$router->post('register-submit', [AuthController::class, 'registerSubmit']);

$router->get('login', [AuthController::class, 'showLogin']);
$router->post('login-submit', [AuthController::class, 'loginSubmit']);

$router->get('logout', [AuthController::class, 'logout']);

/*
|--------------------------------------------------------------------------
| RESERVATION
|--------------------------------------------------------------------------
*/
$router->post('reserve', [ReservationController::class, 'reserve']);
$router->get('my', [ReservationController::class, 'myReservations']);
$router->get('admin/reservations', [
    \App\Presentation\Controllers\Admin\ReservationAdminController::class,
    'index'
]);

$router->post('admin/reservation/action', [
    \App\Presentation\Controllers\Admin\ReservationAdminController::class,
    'process'
]);


/*
|--------------------------------------------------------------------------
| SUGGEST
|--------------------------------------------------------------------------
*/
$router->get('suggest', [SuggestController::class, 'index']);