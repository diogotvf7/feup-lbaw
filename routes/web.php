<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\QuestionController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UserController;

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
Route::redirect('/', '/questions');

// Main Page (welcome.blade.php)
// Route::get('/welcome', function () {
//     return view('/welcome');
// });

Route::controller(QuestionController::class)->group(function () {
    Route::get('/questions', 'index')->name('questions');
});

Route::controller(UserController::class)->group(function () {
    Route::get('/admin/users', 'index')->name('users')->middleware(AdminMiddleware::class);
    Route::get('/users/{id}', 'show');
    Route::delete('/users/{id}', 'destroy')->name('users.destroy');
    Route::get('/users/{id}/edit', 'edit')->name('users.edit')->middleware(AdminMiddleware::class);;
    Route::patch('/users/{id}', 'update')->name('users.update');
    Route::patch('/users/{id}/promote', 'promote')->name('user.promote');
    Route::patch('/users/{id}/demote', 'demote')->name('user.demote');
    Route::get('/user/create', 'create')->name('user.create')->middleware(AdminMiddleware::class);;
    Route::post('/user/store', 'store')->name('user.store');
});


// API
Route::controller(QuestionController::class)->group(function () {
    Route::get('/api/questions', 'fetch');
});


// Authentication
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'authenticate');
    Route::get('/logout', 'logout')->name('logout');
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register');
});
