<?php

use Illuminate\Support\Facades\Route;

Route::apiResource('user', App\Modules\User\Http\Controllers\UserController::class);
