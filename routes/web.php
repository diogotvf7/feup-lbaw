<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\AnswerController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

use App\Http\Middleware\AdminMiddleware;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Home
Route::redirect('/', '/questions?filter=top');

Route::controller(QuestionController::class)->group(function () {
    Route::get('/questions', 'index')->name('questions');
    Route::get('/questions/create', 'create')->name('question.create');
    Route::post('/questions/store', 'store')->name('question.store');
    Route::get('/questions/search', 'search')->name('search');
    Route::get('/questions/{question}', 'show');
});

Route::controller(UserController::class)->group(function () {
    Route::get('/admin/users', 'index')->name('users')->middleware(AdminMiddleware::class);
    Route::patch('/admin/users/{user}/promote', 'promote')->name('user.promote');
    Route::patch('/admn/users/{user}/demote', 'demote')->name('user.demote');
    Route::get('/admin/users/{user}/edit', 'edit')->name('admin.users.edit')->middleware(AdminMiddleware::class);;

    //Route::get('/users/{user}', 'show')->where('user', '[0-9]+')->name('users.profile');
    //Route::delete('/users/{user}/delete', 'destroy')->name('users.destroy');
    //Route::patch('/users/{user}/update', 'update')->name('users.update');
    //Route::get('/user/create', 'create')->name('user.create')->middleware(AdminMiddleware::class);;
    Route::post('/user/store', 'store')->name('user.store');
});

Route::controller(AnswerController::class)->group(function () {
    Route::post('/answers/create', 'store')->name('answer/create');
    Route::patch('/answers/edit', 'edit')->name('answer/edit');
    Route::delete('/answers/delete', 'destroy')->name('answer/delete');
});

Route::controller(QuestionController::class)->group(function () {
    Route::patch('/questions/edit', 'edit')->name('question/edit');
    Route::delete('/questions/delete', 'destroy')->name('question/delete');
});

// API
Route::controller(QuestionController::class)->group(function () {
    Route::get('/api/questions', 'fetch');
});

// Authentication
Route::controller(LoginController::class)->group(function () {
   // Route::get('/login', 'showLoginForm')->name('login');
    //Route::post('/login', 'authenticate');
    //Route::post('/logout', 'logout')->name('logout');
});

Route::controller(RegisterController::class)->group(function () {
   // Route::get('/register', 'showRegistrationForm')->name('register');
    //Route::post('/register', 'register');
});
