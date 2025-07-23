<?php

use App\Http\Controllers\UserController;
use App\Http\Middleware\DecryptSanctumToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:sanctum'
])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
