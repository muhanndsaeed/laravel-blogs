<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Post\PostController;
use App\Http\Controllers\API\Files\FileController;
use App\Http\Controllers\API\Comments\CommentController;
use App\Http\Controllers\API\Category\CategoryController;
use App\Http\Controllers\API\Authentication\AuthController;
use App\Http\Controllers\API\Authentication\UserController;
use App\Http\Controllers\API\favorites\FavoritesController;
use App\Http\Controllers\API\Authentication\GoogleController;

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
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
Route::post('forgot', [AuthController::class, 'forgot']);
Route::post('reset', [AuthController::class, 'resetpassword']);
Route::middleware('auth:api')->prefix('user')->group(function(){
    Route::post('update/password' , [UserController::class , 'updatePassword']);
    Route::get('profile' , [UserController::class , 'showMyProfile']);
    Route::post('profile/edit' , [UserController::class , 'updateProfile']);
    Route::post('logout',[AuthController::class, 'logout']);
    
});
Route::middleware(['auth:api', 'admin'])->group(function(){
    //category
    Route::post('category',[CategoryController::class , 'store']);
    Route::put('category/{id}',[CategoryController::class , 'update']);
    Route::delete('category/{id}' , [CategoryController::class , 'destroy']);

});
Route::get('category' , [CategoryController::class , 'index']);

Route::resource('post',PostController::class)->middleware('auth:api');
Route::get('posts', [PostController::class,'ShowMyBlogs'])->middleware('auth:api');

Route::resource('comment', CommentController::class)->middleware('auth:api');
Route::resource('files', FileController::class)->middleware('auth:api');
Route::get('file/{id}', [FileController::class, 'DownloadFile']);

Route::resource('favorite', FavoritesController::class)->middleware('auth:api');


Route::post('updateFile/{id}',[FileController::class , 'update'])->middleware('auth:api');




