<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\AnswerController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\LoggedMiddleware;

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
    Route::get('/questions/top', 'index')->name('questions.top');
    Route::get('/questions/followed', 'index')->name('questions.followed')->middleware(LoggedMiddleware::class);
    Route::get('/questions/tag/{id}', 'index')->where('id', '[0-9]+')->name('questions.tag');
    Route::get('/questions/create', 'create')->name('question.create');
    Route::post('/questions/store', 'store')->name('question.store');
    Route::get('/questions/search', 'search')->name('search');
    Route::get('/questions/{question}', 'show')->name('question.show');
    Route::patch('/questions/edit', 'edit')->name('question/edit');
    Route::delete('/questions/delete', 'destroy')->name('question/delete');
});

Route::controller(AnswerController::class)->group(function () {
    Route::post('/answers/create', 'store')->name('answer/create');
    Route::patch('/answers/edit', 'edit')->name('answer/edit');
    Route::delete('/answers/delete', 'destroy')->name('answer/delete');
});

Route::middleware(AdminMiddleware::class)->group(function () {
    Route::get('/admin/users', [UserController::class, 'list']);
    Route::patch('/admin/users/{user}/promote', [UserController::class, 'promote'])->name('user.promote');
    Route::patch('/admin/users/{user}/demote', [UserController::class, 'demote'])->name('user.demote');
    Route::get('/admin//user/create', [UserController::class, 'create'])->name('user.create');
    Route::post('/admin/user/store', [UserController::class, 'store'])->name('user.store');

    Route::get('/admin/tags', [TagController::class, 'list']);
    Route::patch('/tags/{tag}/approve', [TagController::class, 'approve'])->name('tag.approve');
    Route::delete('/tags/{tag}/delete', [TagController::class, 'destroy'])->name('tag.destroy');
    Route::get('/tags/{tag}/edit', [TagController::class, 'edit'])->name('tag.edit');
    Route::patch('/tags/{tag}/update', [TagController::class, 'update'])->name('tag.update');
});

Route::controller(UserController::class)->group(function () {
    Route::get('/users/{user}', 'show')->where('user', '[0-9]+')->name('user.profile')->middleware(LoggedMiddleware::class);
    Route::delete('/users/{user}/delete', 'destroy')->name('user.destroy');
    Route::patch('/users/{user}/update', 'update')->name('user.update');
    Route::get('/users/{user}/edit', 'edit')->name('user.edit');
});

Route::controller(TagController::class)->group(function () {
    Route::get('/tags', 'index')->name('tags');
    Route::get('/tags/{tag}', 'show')->name('tag.show');
    Route::post('/tags/create', 'store')->name('tag.create');
});

//API
Route::controller(QuestionController::class)->group(function () {
    Route::get('/api/questions', 'fetch');
    Route::get('/api/questions/top', 'fetch');
    Route::get('/api/questions/followed', 'fetch');
    Route::get('/api/questions/tag/{id}', 'fetch')->where('id', '[0-9]+');
});

Route::controller(TagController::class)->group(function () {
    Route::get('/api/tags', 'fetch');
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
