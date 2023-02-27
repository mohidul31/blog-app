<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:api'])->group(function () {
    //jwt common apis
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::post('/user', [AuthController::class, 'me']);

    //article apis
    Route::get('/articles', [AuthController::class, 'index']);
    Route::post('/articles', [AuthController::class, 'store']);
    Route::put('/articles/{id}', [AuthController::class, 'update']);
    Route::delete('/articles', [AuthController::class, 'destroy']);
});
