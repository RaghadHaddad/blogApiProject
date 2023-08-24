<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['middleware' => 'api','prefix' => 'auth'], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
});


Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/category-show/{id}' , [CategoryController::class , 'show']);
Route::post('/category' , [CategoryController::class , 'store']);
Route::post('/category-update/{id}' , [CategoryController::class , 'update']);
Route::post('/category/{id}' , [CategoryController::class , 'destroy']);

Route::get('/tags', [TagController::class, 'index']);
Route::get('/tag-show/{id}' , [TagController::class , 'show']);
Route::post('/tag' , [TagController::class , 'store']);
Route::post('/tag-update/{id}' , [TagController::class , 'update']);
Route::post('/tag/{id}' , [TagController::class , 'destroy']);
