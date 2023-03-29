<?php

use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/* User perform task functions */
Route::prefix('/tasks')->group(function(){
    Route::get('/',[TaskController::class,'index']);
    Route::get('/{id}',[TaskController::class,'show']);
    Route::post('/',[TaskController::class,'store']);
    Route::put('/{task}',[TaskController::class,'update']);
    Route::delete('/{task}',[TaskController::class,'destroy']);
    Route::post('/markcompleted/{id}',[TaskController::class,'markcompleted']);
    Route::post('/markincompleted/{id}',[TaskController::class,'markincompleted']);
});

/* User Registration */
Route::post('/register',[RegisteredUserController::class,'store']);
Route::middleware('auth:sanctum')->post('/logout',[AuthenticatedSessionController::class,'destroy']);
Route::post('/login',[AuthenticatedSessionController::class,'store']);