<?php

use App\Http\Controllers\ClientController;

use App\Http\Controllers\Private\NotificationController;
use App\Http\Middleware\PrivateAuthorisationMiddleware;
use App\Http\Controllers\Private\ClientController as PrivateClientController;
use Illuminate\Support\Facades\Route;


Route::apiResource('client', ClientController::class)->only('show', 'store', 'update', 'destroy');

Route::middleware(PrivateAuthorisationMiddleware::class)->prefix('private')->group(function(){
    Route::apiResource('notification', NotificationController::class)->only('index', 'show', 'store');
    Route::apiResource('client', PrivateClientController::class)->only('index', 'show', 'store');
});

