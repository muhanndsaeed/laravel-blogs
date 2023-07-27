<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Authentication\AuthController;
use App\Http\Controllers\API\Authentication\UserController;

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


Route::post('register' , [AuthController::class , 'register']);
Route::post('login' , [AuthController::class , 'login']);
Route::post('forgot', [AuthController::class, 'forgot']);
Route::post('reset', [AuthController::class, 'resetpassword']);
Route::middleware('auth:api')->prefix('user')->group(function(){
    Route::post('update/password' , [UserController::class , 'updatePassword']);
    Route::post('logout',[AuthController::class, 'logout']);
});