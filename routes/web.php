<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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

Route::get('/welcome', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');



// middleware(['auth'])
Route::middleware(['auth'])->group(function() {
    // メイン画面：相手を表示し、likes or unlikes ボタンを設置する
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    // likes or unlikes メソッドを swipes に保存するメソッド
    Route::post('/users/{user}', [UserController::class, 'store'])->name('users.store');
    // マッチしたユーザー一覧を表示する
    Route::get('/users/matches', [UserController::class, 'matches'])->name('users.matches');
    // users.show(マッチングしたユーザーのプロフィールを表示するルート)
    Route::get('/users/matches/{num}', [UserController::class, 'matches_show'])->name('users.matches_show');
    //users.room(マッチングしたユーザーとチャットするルート）
    Route::get('/users/room/{user}', [UserController::class, 'room'])->name('users.room');
    //ユーザーとの個人メッセージの取得メソッド
    Route::get('/users/room/{user}/messages', [UserController::class, 'get_messages'])->name('get_messages');
    //ユーザーとの個人メッセージの保存・イベントの発火メソッド
    Route::post('/users/room/{user}/store', [UserController::class, 'store_message'])->name('store_message');
});
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
