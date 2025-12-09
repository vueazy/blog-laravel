<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RolePermissionController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('/login', [AuthController::class, 'authenticate'])->name('login');
    Route::get('/logout', [AuthController::class, 'revokingAccessToken'])
        ->middleware('auth:sanctum')
        ->name('logout');
});

Route::resource('user', UserController::class)->except('create', 'edit');
Route::resource('category', CategoryController::class)->except('create', 'edit');
Route::resource('tag', TagController::class)->except('create', 'edit');
Route::resource('role', RolePermissionController::class)->except('create', 'edit');

Route::resource('post', PostController::class)->except('create', 'edit')->middleware('auth:sanctum');
Route::get('/post/{post}/comment', [PostController::class, 'comment'])->name('post.comment')->middleware('auth:sanctum');
Route::post('/post/{post}/comment', [PostController::class, 'comments'])->name('post.comments')->middleware('auth:sanctum');
Route::get('/post/{post}/publish', [PostController::class, 'publish'])->name('post.publish')->middleware('auth:sanctum');