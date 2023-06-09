<?php

use App\Http\Controllers\ArticalController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ReelController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
/**
 * one to one polymorphic relationship
 */

Route::controller(UserController::class)->prefix('user')->group(function(){
    Route::post('create','create');
    Route::get('list','list');
    Route::post('update/{id}','update');
    Route::delete('delete/{id}','destory');
    Route::get('get/{id}','get');
    Route::get('latest-image/{id}','latestImage');
});

Route::controller(PostController::class)->prefix('post')->group(function(){
    Route::post('create','create');
    Route::get('list','list');
    Route::post('update/{id}','update');
    Route::delete('delete/{id}','destory');
    Route::get('get/{id}','get');
});

/**
 * one to many
 */
Route::controller(PhotoController::class)->prefix('photo')->group(function(){
    Route::post('create','create');
    Route::get('list','list');
    Route::put('update/{id}','update');
    Route::delete('delete/{id}','destory');
    Route::get('get/{id}','get');
});

Route::controller(VideoController::class)->prefix('video')->group(function(){
    Route::post('create','create');
    Route::get('list','list');
    Route::put('update/{id}','update');
    Route::delete('delete/{id}','destory');
    Route::get('get/{id}','get');
});

/**
 * polymorphic many to many
 */

 Route::controller(ReelController::class)->prefix('reel')->group(function(){
    Route::post('create','create');
    Route::get('list','list');
    Route::put('update/{id}','update');
    Route::delete('delete/{id}','destory');
    Route::get('get/{id}','get');
});

Route::controller(TagController::class)->prefix('tag')->group(function(){
    Route::post('create','create');
    Route::get('list','list');
    Route::put('update/{id}','update');
    Route::delete('delete/{id}','destory');
    Route::get('get/{id}','get');
});

Route::controller(ArticalController::class)->prefix('artical')->group(function(){
    Route::post('create','create');
    Route::get('list','list');
    Route::put('update/{id}','update');
    Route::delete('delete/{id}','destory');
    Route::get('get/{id}','get');
});
