<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
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
Route::middleware('jwt.auth')->get('/users',function(Request $request){
    return auth()->user();
});


Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/category/{id}' , [CategoryController::class , 'show']);
Route::post('/categories' , [CategoryController::class , 'store']);
Route::post('/categories/{id}' , [CategoryController::class , 'update']);
Route::post('/category/{id}' , [CategoryController::class , 'destroy']);
