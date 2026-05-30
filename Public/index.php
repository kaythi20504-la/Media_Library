<?php

declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', 1);

/*
|--------------------------------------------------------------------------
| BASE PATH
|--------------------------------------------------------------------------
*/
define('BASE_PATH', dirname(__DIR__));
define('BASE_URL', 'http://localhost:8080/MediaLibrary-MVC-');

/*
|--------------------------------------------------------------------------
| AUTOLOADER
|--------------------------------------------------------------------------
*/
require_once BASE_PATH . '/vendor/autoload.php';
session_start(); // ✅ MUST BE HERE

/*
|--------------------------------------------------------------------------
| ERROR HANDLING (IMPORTANT - MUST BE FIRST)
|--------------------------------------------------------------------------
*/
use App\Error\ErrorHandler;

ErrorHandler::register();

/*
|--------------------------------------------------------------------------
| ENV
|--------------------------------------------------------------------------
*/
if (file_exists(BASE_PATH . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
    $dotenv->load();
}

/*
|--------------------------------------------------------------------------
| USE CLASSES
|--------------------------------------------------------------------------
*/
use App\Core\Database;
use App\Core\Router;

use App\Controllers\CatalogController;
use App\Controllers\DetailsController;
use App\Controllers\AuthController;
use App\Controllers\SuggestController;

use App\Repositories\CatalogRepository;
use App\Repositories\FormatRepository;
use App\Repositories\UserRepository;

use App\Services\CatalogService;
use App\Services\FormatService;
use App\Services\UserService;
use App\Validation\Validator;
use App\Controllers\Api\ApiUserController;
/*
|--------------------------------------------------------------------------
| DB CONNECTION
|--------------------------------------------------------------------------
*/
$db = Database::connection();

/*
|--------------------------------------------------------------------------
| REPOSITORIES
|--------------------------------------------------------------------------
*/
$catalogRepo = new CatalogRepository($db);
$formatRepo  = new FormatRepository($db);
$userRepo    = new UserRepository($db);

/*
|--------------------------------------------------------------------------
| SERVICES
|--------------------------------------------------------------------------
*/
$catalogService = new CatalogService($catalogRepo);
$formatService  = new FormatService($formatRepo);
$validator = new Validator();
$userService    = new UserService($userRepo, $validator);

/*
|--------------------------------------------------------------------------
| ROUTER
|--------------------------------------------------------------------------
*/
$router = new Router();

/*
|--------------------------------------------------------------------------
| REGISTER SERVICES (IMPORTANT)
|--------------------------------------------------------------------------
*/
$router->registerService(CatalogController::class, $catalogService);
$router->registerService(DetailsController::class, $catalogService);
$router->registerService(SuggestController::class, $formatService);
$router->registerService(AuthController::class, $userService);
$router->registerService(ApiUserController::class, $userService);
/*
|--------------------------------------------------------------------------
| ROUTES
|--------------------------------------------------------------------------
*/
require BASE_PATH . '/routes/web.php';
require BASE_PATH . '/routes/api.php';

/*
|--------------------------------------------------------------------------
| DISPATCH
|--------------------------------------------------------------------------
*/
$page = $_GET['page'] ?? 'home';
$router->dispatch($page);