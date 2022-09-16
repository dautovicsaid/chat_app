<?php

use App\Http\Controllers\ConversationController;
use App\Http\Controllers\FriendshipController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
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

Route::get('/', function () {
    return redirect('login');
});

Route::group(['middleware' => 'auth'], function (){

    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::get('users/{user}',[UserController::class,'show'])->name('users.show');
    Route::get('users/{user}/edit',[UserController::class,'edit'])->name('users.edit');
    Route::get('users/{user}/password-reset',[UserController::class,'password_reset_edit'])->name('users.password_reset_edit');
    Route::put('users/{user}',[UserController::class,'update'])->name('users.update');
    Route::patch('users/{user}/password-reset',[UserController::class,'password_reset_update'])->name('users.password_reset_update');


    Route::post('/{target_user}/add-friend',[FriendshipController::class,'add_friend'])->name('add_friend');
    Route::get('/friendships/friend-requests',[FriendshipController::class,'get_friend_requests'])->name('get_friend_requests');
    Route::patch('/friendships/friend-requests/{friendship}/accept',[FriendshipController::class,'accept_friend_request'])->name('accept_friend_request');
    Route::patch('/friendships/friend-requests/{friendship}/decline',[FriendshipController::class,'decline_friend_request'])->name('decline_friend_request');
    Route::patch('/friendships/friend-requests/{friendship}/cancel',[FriendshipController::class,'cancel_friend_request'])->name('cancel_friend_request');
    Route::get('/friendships/friends',[FriendshipController::class,'get_friends'])->name('get_friends');
    Route::delete('/friendships/{friendship}/delete',[FriendshipController::class,'unfriend'])->name('unfriend');

    Route::get('/conversations/{conversation}/messages',[ConversationController::class,'message_history'])->name('message_history');
    Route::get('/conversations',[ConversationController::class,'index'])->name('conversations.index');
});

Auth::routes();


