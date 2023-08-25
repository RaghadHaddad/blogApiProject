<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TagController;
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
Route::group(['prefix' => 'auth'], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/user-profile', [AuthController::class, 'profile'])->middleware('jwt.verify');
    Route::post('/update-profile', [AuthController::class, 'updateProfile'])->middleware('jwt.verify');
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/softDelete/{id}', [AuthController::class, 'SoftDelete'])->middleware('jwt.verify','isAdmin');
    Route::post('/restore/{id}', [AuthController::class, 'restore'])->middleware('jwt.verify','isAdmin');
   Route::post('/deleted/{id}',[AuthController::class,'forceDeleted'])->middleware('jwt.verify','isAdmin');
   Route::post('/check/{id}',[AuthController::class,'check'])->middleware('jwt.verify','isAdmin');
});

Route::group(['middleware' => ['jwt.verify'],'prefix' => 'user'], function() {

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

    Route::get('/posts', [PostController::class, 'index'])->middleware('plan');
    Route::get('/post-show/{id}' , [PostController::class , 'show']);
    Route::post('/post' , [PostController::class , 'store']);
    Route::post('/post-update/{id}' , [PostController::class , 'update']);
    Route::post('/post/{id}' , [PostController::class , 'destroy']);
    Route::get('/post/search/{name}' , [PostController::class , 'search']);


    Route::post('/post-tag-add/{id}' , [PostTagController::class , 'addTags']);
    Route::post('/post-tag-delete/{id}' , [PostTagController::class , 'deleteTag']);
    Route::get('/post-tags-show/{id}' , [PostTagController::class , 'show']);



    Route::get('/images', [ImageController::class, 'index']);
    Route::get('/image/{id}' , [ImageController::class , 'show']);
    Route::post('/images' , [ImageController::class , 'store']);
    Route::post('/images/{id}' , [ImageController::class , 'update']);
    Route::post('/image/{id}' , [ImageController::class , 'destroy']);

});

