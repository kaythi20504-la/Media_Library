<?php
// Import the classes at the top
use App\Controllers\Api\ApiCatalogController;
use App\Controllers\Api\ApiDetailsController;
use App\Controllers\Api\ApiSuggestController;
/*
|--------------------------------------------------------------------------
| API ROUTES
|--------------------------------------------------------------------------
*/

// Use the ::class syntax. This passes the full string "App\Controllers\Api\..." to the router
$router->get('api-catalog', [ApiCatalogController::class, 'index']);
$router->get('api-details', [ApiDetailsController::class, 'show']);
$router->post('api-suggest', [ApiSuggestController::class, 'store']);