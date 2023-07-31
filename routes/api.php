<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Authentication\AuthController;
use App\Http\Controllers\API\Authentication\UserController;
use App\Http\Controllers\API\Category\CategoryController;

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
    Route::get('profile' , [UserController::class , 'showMyProfile']);
    Route::post('profile/edit' , [UserController::class , 'updateProfile']);
    Route::post('logout',[AuthController::class, 'logout']);
});

Route::middleware(['auth:api', 'admin'])->group(function(){

    Route::post('category',[CategoryController::class , 'store']);

});
