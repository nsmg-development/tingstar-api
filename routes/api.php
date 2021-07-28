<?php

use App\Http\Controllers\Api\v1\ArticleController;
use App\Http\Controllers\Api\v1\UserController;
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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('v1')->group(function () {
    Route::post('user', [UserController::class, 'store'])->name('api.user.store');

    Route::middleware('client')->group(function () {
        Route::get('articles', [ArticleController::class, 'index'])->name('api.article.list');
        Route::get('articles/{article_id}', [ArticleController::class, 'show'])->name('api.article.show');
    });
});
