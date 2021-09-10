<?php

use App\Http\Controllers\Api\v1\ArticleController;
use App\Http\Controllers\Api\v1\ArticleUserFavoriteController;
use App\Http\Controllers\Api\v1\ChannelController;
use App\Http\Controllers\Api\v1\KeywordController;
use App\Http\Controllers\Api\v1\MediaController;
use App\Http\Controllers\Api\v1\PlatformAccountController;
use App\Http\Controllers\Api\v1\PlatformController;
use App\Http\Controllers\Api\v1\UserController;
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
        Route::post('articles/{article_id}/{behavior_type}', [ArticleController::class, 'setArticleBehavior'])->name('api.article.set_behavior');

        Route::get('favorites', [ArticleUserFavoriteController::class, 'index'])->name('api.favorite.list');
        Route::post('favorites', [ArticleUserFavoriteController::class, 'setFavorite'])->name('api.favorite.set_favorite');
    });

    Route::middleware('auth:api')->group(function(){
        Route::get('admin/articles', [ArticleController::class, 'index'])->name('api.article.list');

        Route::prefix('articles')->group(function () {
            Route::put('', [ArticleController::class, 'setMultipleState'])->name('api.article.set_multiple_state');
            Route::put('/{article_id}/state', [ArticleController::class, 'setState'])->name('api.article.set_state');
        });

        Route::prefix('platforms')->group(function () {
            Route::get('', [PlatformController::class, 'index'])->name('api.platform.list');
            Route::get('/{platform_id}', [PlatformController::class, 'show'])->name('api.platform.show');
        });

        Route::prefix('platform-accounts')->group(function () {
            Route::post('', [PlatformAccountController::class, 'store'])->name('api.platform_account.store');
            Route::put('/{platform_account_id}', [PlatformAccountController::class, 'update'])->name('api.platform_account.update');
        });

        Route::prefix('channels')->group(function () {
            Route::post('', [ChannelController::class, 'store'])->name('api.channel.store');
            Route::put('/{channel_id}', [ChannelController::class, 'update'])->name('api.channel.update');
        });

        Route::prefix('keywords')->group(function () {
            Route::post('', [KeywordController::class, 'store'])->name('api.keyword.store');
            Route::put('/{keyword_id}', [KeywordController::class, 'update'])->name('api.keyword.update');
        });

        Route::prefix('medias')->group(function () {
            Route::get('', [MediaController::class, 'index'])->name('api.media.list');
            Route::get('/{media_id}', [MediaController::class, 'show'])->name('api.media.show');
        });
    });
});
