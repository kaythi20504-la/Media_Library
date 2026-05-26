<?php

use App\Controllers\Api\ApiUserController;
use App\Controllers\Api\ApiCatalogController;
use App\Controllers\Api\ApiDetailsController;
use App\Controllers\Api\ApiSuggestController;

/*
|--------------------------------------------------------------------------
| API ROUTES
|--------------------------------------------------------------------------
| REST-style API structure
*/

/* =========================
   USER CRUD API
   ========================= */

$router->get('api/users', [ApiUserController::class, 'index']);
$router->get('api/users/{id}', [ApiUserController::class, 'show']);

$router->post('api/users', [ApiUserController::class, 'store']);
$router->post('api/users/update/{id}', [ApiUserController::class, 'update']);
$router->post('api/users/delete/{id}', [ApiUserController::class, 'delete']);


/* =========================
   EXISTING APIs (KEEP)
   ========================= */

$router->get('api-catalog', [ApiCatalogController::class, 'index']);

$router->get('api-details', [ApiDetailsController::class, 'show']);

$router->post('api-suggest', [ApiSuggestController::class, 'store']);