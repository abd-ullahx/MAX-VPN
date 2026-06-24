<?php

use App\Http\Controllers\Api\ApiServerController;
use App\Http\Controllers\Api\ServerCountApiController;
use App\Http\Middleware\AuthorizeApiClient;
use Illuminate\Support\Facades\Route;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');

Route:: middleware(AuthorizeApiClient::class)->prefix('servers')->group(function () {
    Route::get('/', [ApiServerController::class, 'servers']);
});

Route::post('/server-status', [ServerCountApiController::class, 'count']);
