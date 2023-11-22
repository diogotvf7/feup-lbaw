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
Route::redirect('/', '/login');

// Main Page (welcome.blade.php)
// Route::get('/welcome', function () {
//     return view('/welcome');
// });

Route::controller(QuestionController::class)->group(function () {
    Route::get('/questions/top', 'top')->name('topQuestions');
    Route::get('/questions/{question}', 'show');
});

// Cards
Route::controller(CardController::class)->group(function () {
    Route::get('/cards', 'list')->name('cards');
    Route::get('/cards/{id}', 'show');
});

Route::controller(UserController::class)->group(function () {
    Route::get('/admin/users', 'index')->name('users')->middleware(AdminMiddleware::class);
    Route::get('/users/{user}', 'show')->where('user', '[0-9]+')->name('users.profile');
    Route::delete('/users/{id}', 'destroy')->name('users.destroy');
    Route::get('/users/{id}/edit', 'edit')->name('admin.users.edit')->middleware(AdminMiddleware::class);;
    Route::patch('/users/{id}/update', 'update')->name('users.update');
    Route::patch('/users/{id}/promote', 'promote')->name('user.promote');
    Route::patch('/users/{id}/demote', 'demote')->name('user.demote');
    Route::get('/user/create', 'create')->name('user.create')->middleware(AdminMiddleware::class);;
    Route::post('/user/store', 'store')->name('user.store');
});

// API
Route::controller(CardController::class)->group(function () {
    Route::put('/api/cards', 'create');
    Route::delete('/api/cards/{card_id}', 'delete');
});

Route::controller(ItemController::class)->group(function () {
    Route::put('/api/cards/{card_id}', 'create');
    Route::post('/api/item/{id}', 'update');
    Route::delete('/api/item/{id}', 'delete');
});

Route::controller(AnswerController::class)->group(function () {
    Route::post('/answer/create', 'store')->name('answer/create');
    Route::patch('/answer/edit', 'edit')->name('answer/edit');
    Route::delete('/answer/delete', 'destroy')->name('answer/delete');
});

Route::controller(QuestionController::class)->group(function () {
    Route::patch('/question/edit', 'edit')->name('question/edit');
    Route::delete('/question/delete', 'destroy')->name('question/delete');
});

// Authentication
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'authenticate');
    Route::post('/logout', 'logout')->name('logout');
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register');
});
