<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\TitleController;
use App\Http\Controllers\UsersController;

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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

//新規登録
Route::post('/register', [App\Http\Controllers\Auth\RegisteredUserController::class, 'store']);

//ログイン
Route::post('/login', [App\Http\Controllers\Auth\RegisteredUserController::class, 'index']);

//----------ランキング----------

//ランキング全件取得
Route::get('/ranking', [ RankingController::class, "index"]);


//----------投稿----------
Route::post('/posts', [PostController::class, 'store']);

Route::get('/posts/{id}', [PostController::class, 'show']);

Route::post('/posts/{id}/comments', [PostController::class, 'comments']);

Route::get('/posts/{id}/comments', [PostController::class, 'getcomments']);

//----------フォロー----------
Route::post('/users/{id}/follow', [FollowController::class, 'follow']);

Route::get('/users/{id}/follow', [FollowController::class, 'confirmfollow']);

Route::post('/users/{id}/unfollow', [FollowController::class, 'unfollow']);

Route::get('/follower/{id}', [FollowController::class, 'show']);

//----------コミュニティ----------
Route::post('/community', [CommunityController::class, 'store']);

Route::get('/community/{id}', [CommunityController::class, 'index']);

Route::post('/community/{id}/messages', [CommunityController::class, 'postmessage']);

Route::get('/community/{id}/messages', [CommunityController::class, 'show']);

//----------称号----------
Route::post('/titles', [TitleController::class, 'store']);

Route::put('user/{id}/title', [TitleController::class, "update"]);

Route::get('user/{id}/title', [TitleController::class, "usertitle"]);

Route::get('user/title', [TitleController::class, "index"]);

Route::get('user/{id}', [UserController::class, "index"]);