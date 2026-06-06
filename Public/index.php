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

/*
|-----------------------------
| REPOSITORIES
|-----------------------------
*/
use App\Infrastructure\Persistence\User\UserRepository;
use App\Infrastructure\Persistence\Catalog\CatalogRepository;
use App\Infrastructure\Persistence\Catalog\FormatRepository;

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

/*
|-----------------------------
| DB
|-----------------------------
*/
$db = Database::connection();

/*
|-----------------------------
| REPOSITORIES
|-----------------------------
*/
$catalogRepo = new CatalogRepository($db);
$formatRepo  = new FormatRepository($db);
$userRepo    = new UserRepository($db);

/*
|-----------------------------
| USE CASES
|-----------------------------
*/
$homeUseCase = new GetHomePageUseCase($catalogRepo);
$catalogPageUseCase = new GetCatalogPageUseCase($catalogRepo);
$catalogItemUseCase = new GetCatalogItemUseCase($catalogRepo);
$formatUseCase = new GetFormatDataUseCase($formatRepo);

$registerUserUseCase = new RegisterUserUseCase($userRepo);
$loginUserUseCase = new LoginUserUseCase($userRepo);

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
$router->registerService(AuthController::class, [
    $registerUserUseCase,
    $loginUserUseCase
]);

$router->registerService(CatalogController::class, [
    $homeUseCase,
    $catalogPageUseCase,
    $catalogItemUseCase
]);

$router->registerService(DetailsController::class, $catalogItemUseCase);
$router->registerService(SuggestController::class, $formatUseCase);

/*
|-----------------------------
| ROUTES
|-----------------------------
*/
require BASE_PATH . '/routes/web.php';
require BASE_PATH . '/routes/api.php';

/*
|-----------------------------
| DISPATCH
|-----------------------------
*/
$page = $_GET['page'] ?? 'home';
$router->dispatch($page);