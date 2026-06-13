<?php

declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', 1);

define('BASE_PATH', dirname(__DIR__));
define('BASE_URL', 'http://localhost:8080/MediaLibrary-MVC- -DDD');

require BASE_PATH . '/vendor/autoload.php';

session_start();

use App\Error\ErrorHandler;
ErrorHandler::register();

use App\Infrastructure\Database\Database;
use App\Core\Router;

/*
|-----------------------------
| CONTROLLERS (ONLY PRESENTATION)
|-----------------------------
*/
use App\Presentation\Controllers\AuthController;
use App\Presentation\Controllers\CatalogController;
use App\Presentation\Controllers\DetailsController;
use App\Presentation\Controllers\SuggestController;
use App\Presentation\Controllers\ReservationController;
use App\Presentation\Controllers\Admin\ReservationAdminController;

/*
|-----------------------------
| REPOSITORIES
|-----------------------------
*/
use App\Infrastructure\Persistence\User\UserRepository;
use App\Infrastructure\Persistence\Catalog\CatalogRepository;
use App\Infrastructure\Persistence\Catalog\FormatRepository;
use App\Infrastructure\Persistence\Reservation\ReservationRepository;

/*
|-----------------------------
| USE CASES
|-----------------------------
*/
use App\Application\Catalog\UseCases\GetHomePageUseCase;
use App\Application\Catalog\UseCases\GetCatalogPageUseCase;
use App\Application\Catalog\UseCases\GetCatalogItemUseCase;
use App\Application\Catalog\UseCases\GetFormatDataUseCase;

use App\Application\User\UseCases\RegisterUserUseCase;
use App\Application\User\UseCases\LoginUserUseCase;
use App\Application\Reservation\UseCases\CreateReservationUseCase;
use App\Application\Reservation\UseCases\ReserveMediaUseCase;
use App\Application\Reservation\UseCases\AdminProcessReservationUseCase;

/*
|-----------------------------
| DATABASE CONNECTION
|-----------------------------
*/
$db = Database::connection();

/*
|-----------------------------
| REPOSITORIES INSTANCES
|-----------------------------
*/
$catalogRepo = new CatalogRepository($db);
$formatRepo  = new FormatRepository($db);
$userRepo    = new UserRepository($db);
$reservationRepo = new ReservationRepository($db);

/*
|-----------------------------
| USE CASES INSTANCES
|-----------------------------
*/
$homeUseCase = new GetHomePageUseCase($catalogRepo);
$catalogPageUseCase = new GetCatalogPageUseCase($catalogRepo);
$catalogItemUseCase = new GetCatalogItemUseCase($catalogRepo);
$formatUseCase = new GetFormatDataUseCase($formatRepo);

$registerUserUseCase = new RegisterUserUseCase($userRepo);
$loginUserUseCase = new LoginUserUseCase($userRepo);
$createReservationUseCase = new CreateReservationUseCase($reservationRepo);
$reservationUseCase = new ReserveMediaUseCase($reservationRepo);
$adminReservationUseCase = new AdminProcessReservationUseCase($reservationRepo);

/*
|-----------------------------
| ROUTER
|-----------------------------
*/
$router = new Router();

/*
|-----------------------------
| REGISTER SERVICES
|-----------------------------
*/
// Auth
$router->registerService(AuthController::class, [
    $registerUserUseCase,
    $loginUserUseCase
]);

// Catalog
$router->registerService(CatalogController::class, [
    $homeUseCase,
    $catalogPageUseCase,
    $catalogItemUseCase
]);

// Details
$router->registerService(DetailsController::class, [
    $catalogItemUseCase,
    $reservationRepo
]);

// Suggest
$router->registerService(SuggestController::class, $formatUseCase);

// User Reservations
$router->registerService(ReservationController::class, [
    $reservationUseCase,
    $reservationRepo
]);

// Admin Reservations
$router->registerService(ReservationAdminController::class, [
    $reservationRepo
]);

/*
|-----------------------------
| ROUTES
|-----------------------------
*/
require BASE_PATH . '/routes/web.php';
require BASE_PATH . '/routes/api.php';

/*
|-----------------------------
| ADMIN SPECIFIC ROUTES
|-----------------------------
*/
$router->get('admin/reservations', [
    ReservationAdminController::class,
    'index'
]);

$router->post('admin/reservation/action', [
    ReservationAdminController::class,
    'process'
]);

/*
|-----------------------------
| DISPATCH
|-----------------------------
*/
$page = $_GET['page'] ?? 'home';
$router->dispatch($page);
