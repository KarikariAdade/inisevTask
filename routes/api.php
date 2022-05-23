<?php

use App\Http\Controllers\Api\PostsController;
use App\Http\Controllers\Api\WebsiteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::prefix('website')->group(function (){
    Route::get('list', [WebsiteController::class, 'list']);
    Route::post('create', [WebsiteController::class, 'store']);
    Route::post('subscribe', [WebsiteController::class, 'subscribe']);
    Route::post('view/subscriptions', [WebsiteController::class, 'viewSubscriptions']);
});

Route::prefix('posts')->group(function (){

    Route::get('list', [PostsController::class, 'listPosts']);
    Route::post('store', [PostsController::class, 'store']);

});



