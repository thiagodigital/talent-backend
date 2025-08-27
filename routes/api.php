<?php

use App\Http\Controllers\CollaboratorController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ProfileCategoryController;
use App\Http\Controllers\ProfileTraitController;
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

    Route::get('/categories', [ProfileCategoryController::class, 'index']);
    Route::get('/categories/{profileCategory}', [ProfileCategoryController::class, 'show']);

    Route::get('/traits', [ProfileTraitController::class, 'index']);
    Route::post('/traits', [ProfileTraitController::class, 'store']);
    Route::get('/traits/{profileTrait}', [ProfileTraitController::class, 'show']);

    Route::apiResource('/positions', PositionController::class)->parameters([
        'positions' => 'position'
    ]);
    Route::apiResource('/collaborators', CollaboratorController::class)->parameters([
        'collaborators' => 'collaborator'
    ]);
    Route::get('/profile-category/list/group', [ProfileTraitController::class, 'listGroup']);
    Route::get('/exams/profile/list/disc', [ProfileTraitController::class, 'discList']);
    Route::post('/exams/profile/store/disc', [ProfileTraitController::class, 'discStore']);
});

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::get('/auth/verify', [UserController::class, 'verify'])->middleware('auth:sanctum');
