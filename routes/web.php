<?php

use App\Http\Controllers\Oauth\AuthorizationController;
use App\Http\Controllers\Oauth\AuthorizedAccessTokenController;
use App\Http\Controllers\Oauth\ClientController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Passport override
Route::group([
    'prefix' => 'oauth/clients',
    'middleware' => [
        'auth'
    ],
], function() {
    Route::get('/', [ClientController::class, 'forUser'])->name('oauth.clients.index');
    Route::post('/', [ClientController::class, 'store'])->name('oauth.clients.store');
    Route::put('/{client_id}', [ClientController::class, 'update'])->name('oauth.clients.update');
    Route::delete('/{client_id}', [ClientController::class, 'destroy'])->name('oauth.clients.destroy');
    Route::put('/{client_id}/secret', [ClientController::class, 'updateSecret'])->name('oauth.clients.updateSecret');
});
Route::group([
    'prefix' => 'oauth/authorize',
    'middleware' => [
        'api'
    ],
], function() {
    Route::get('/', [AuthorizationController::class, 'authorize'])->name('oauth.authorizations.authorize');
});
Route::group([
    'prefix' => 'oauth/token',
    'middleware' => [
        'api'
    ],
], function() {
    Route::get('/', [AuthorizedAccessTokenController::class, 'forUser'])->name('oauth.tokens.index');
    Route::delete('/{token_id}', [AuthorizedAccessTokenController::class, 'destroy'])->name('oauth.tokens.destroy');
});
