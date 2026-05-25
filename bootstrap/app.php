<?php

use Dotenv\Dotenv;

use App\Core\Database;
use App\Core\Router;

use App\Controllers\CatalogController;
use App\Controllers\AuthController;
use App\Controllers\SuggestController;

use App\Repositories\CatalogRepository;
use App\Repositories\FormatRepository;
use App\Repositories\UserRepository;

use App\Services\CatalogService;
use App\Services\FormatService;
use App\Services\UserService;

/*
|--------------------------------------------------------------------------
| ENV
|--------------------------------------------------------------------------
*/
$dotenv = Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

/*
|--------------------------------------------------------------------------
| DB
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
$userService    = new UserService($userRepo);

/*
|--------------------------------------------------------------------------
| ROUTER
|--------------------------------------------------------------------------
*/
$router = new Router();

/*
|--------------------------------------------------------------------------
| REGISTER SERVICES (VERY IMPORTANT)
|--------------------------------------------------------------------------
*/
$router->registerService(CatalogController::class, $catalogService);
$router->registerService(SuggestController::class, $formatService);
$router->registerService(AuthController::class, $userService);

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