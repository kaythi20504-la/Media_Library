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
define('BASE_URL', 'http://localhost:8080/MediaLibrary-MVC- -DDD');

/*
|--------------------------------------------------------------------------
| AUTOLOADER
|--------------------------------------------------------------------------
*/
require_once BASE_PATH . '/vendor/autoload.php';

session_start();

/*
|--------------------------------------------------------------------------
| ERROR HANDLING
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
| CORE
|--------------------------------------------------------------------------
*/
use App\Core\Database;
use App\Core\Router;

/*
|--------------------------------------------------------------------------
| CONTROLLERS
|--------------------------------------------------------------------------
*/
use App\Controllers\CatalogController;
use App\Controllers\DetailsController;
use App\Controllers\AuthController;
use App\Controllers\SuggestController;
use App\Controllers\Api\ApiUserController;

/*
|--------------------------------------------------------------------------
| REPOSITORIES
|--------------------------------------------------------------------------
*/
use App\Infrastructure\Persistence\UserRepository;
use App\Repositories\CatalogRepository;
use App\Repositories\FormatRepository;

/*
|--------------------------------------------------------------------------
| USE CASES
|--------------------------------------------------------------------------
*/
use App\Application\User\UseCases\RegisterUserUseCase;
use App\Application\User\UseCases\LoginUserUseCase;

/*
|--------------------------------------------------------------------------
| SERVICES
|--------------------------------------------------------------------------
*/
use App\Services\CatalogService;
use App\Services\FormatService;

/*
|--------------------------------------------------------------------------
| DATABASE
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

/*
|--------------------------------------------------------------------------
| USER USE CASES
|--------------------------------------------------------------------------
*/
$registerUserUseCase = new RegisterUserUseCase(
    $userRepo
);

$loginUserUseCase = new LoginUserUseCase(
    $userRepo
);

/*
|--------------------------------------------------------------------------
| ROUTER
|--------------------------------------------------------------------------
*/
$router = new Router();

/*
|--------------------------------------------------------------------------
| REGISTER CONTROLLER DEPENDENCIES
|--------------------------------------------------------------------------
*/
$router->registerService(
    CatalogController::class,
    $catalogService
);

$router->registerService(
    DetailsController::class,
    $catalogService
);

$router->registerService(
    SuggestController::class,
    $formatService
);

$router->registerService(
    AuthController::class,
    [
        $registerUserUseCase,
        $loginUserUseCase
    ]
);

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