<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\VoteController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\Auth\BlockedController;
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
Route::redirect('/', '/questions/top')->name('homepage');

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
    Route::patch('/question/upvote/{question}', 'upvote')->where('question', '[0-9]+')->middleware(LoggedMiddleware::class);
    Route::patch('/question/downvote/{question}', 'downvote')->where('question', '[0-9]+')->middleware(LoggedMiddleware::class);
    Route::patch('/questions/follow/{question}', 'follow')->where('question', '[0-9]+')->middleware(LoggedMiddleware::class);
});

Route::controller(AnswerController::class)->group(function () {
    Route::post('/answers/create', 'store')->name('answer/create');
    Route::patch('/answers/edit', 'edit')->name('answer/edit');
    Route::delete('/answers/delete', 'destroy')->name('answer/delete');
    Route::patch('/answer/upvote/{answer}', 'upvote')->where('answer', '[0-9]+')->middleware(LoggedMiddleware::class);
    Route::patch('/answer/downvote/{answer}', 'downvote')->where('answer', '[0-9]+')->middleware(LoggedMiddleware::class);
    Route::patch('/answer/correct', 'correct')->name('answer.correct');
});

Route::controller(CommentController::class)->group(function () {
    Route::post('/comments/create', 'store')->name('comment/create');
    Route::patch('/comments/edit', 'edit')->name('comment/edit');
    Route::delete('/comments/delete', 'destroy')->name('comment/delete');
    Route::get('/comment/event', 'commentEvent')->name('comment.event');
});

Route::middleware(AdminMiddleware::class)->group(function () {
    Route::get('/admin/users', [UserController::class, 'list'])->name('admin.users');
    Route::patch('/admin/users/{user}/promote', [UserController::class, 'promote'])->name('user.promote');
    Route::patch('/admin/users/{user}/demote', [UserController::class, 'demote'])->name('user.demote');
    Route::post('/admin/user/store', [UserController::class, 'store'])->name('user.store');

    Route::get('/admin/tags', [TagController::class, 'list'])->name('admin.tags');
    Route::patch('/admin/tags/{tag}/approve', [TagController::class, 'approve'])->name('tag.approve');
    Route::delete('/admin/tags/{tag}/delete', [TagController::class, 'destroy'])->name('tag.destroy');
    Route::get('/admin/tags/{tag}/edit', [TagController::class, 'edit'])->name('tag.edit');
    Route::patch('/admin/tags/{tag}/update', [TagController::class, 'update'])->name('tag.update');
});

Route::controller(UserController::class)->group(function () {
    Route::get('/users/{user}', 'show')->where('user', '[0-9]+')->name('user.profile')->middleware(LoggedMiddleware::class);
    Route::delete('/users/{user}/delete', 'destroy')->name('user.destroy');
    Route::patch('/users/{user}/update', 'update')->name('user.update');
    Route::get('/users/{user}/edit', 'edit')->name('user.edit');
});

Route::controller(TagController::class)->group(function () {
    Route::get('/tags', 'index')->name('tags');
    Route::get('/questions/tag/{tag}', 'show')->name('tag.show');
    Route::post('/tags/store', 'store')->name('tag.store')->middleware(LoggedMiddleware::class);
});

Route::controller(NotificationController::class)->group(function () {
    Route::post('/notifications/read', 'read')->middleware(LoggedMiddleware::class);
    Route::post('/notifications/delete', 'destroyAll')->middleware(LoggedMiddleware::class);
    Route::post('/notifications/delete/{notification}', 'destroy')->where('notification', '[0-9]+')->middleware(LoggedMiddleware::class);
    Route::post('/notifications/read/question/{id}', 'readAllRelatedTo')->where('id', '[0-9]+')->middleware(LoggedMiddleware::class);
});

Route::get('/info', function () {
    return view('pages.info');
})->name('info');

Route::get('/faq', function () {
    return view('pages.faq');
})->name('faq');

//API
Route::controller(QuestionController::class)->group(function () {
    Route::get('/api/questions', 'fetch');
    Route::get('/api/questions/top', 'fetch');
    Route::get('/api/questions/followed', 'fetch');
    Route::get('/api/questions/tag/{tag}', 'fetch')->where('tag', '[0-9]+');
    Route::get('/api/questions/{question}/answers', 'fetch')->where('question', '[0-9]+');
    Route::get('/api/questions/{question}/tags', 'fetchTags')->where('question', '[0-9]+');
});

Route::controller(AnswerController::class)->group(function () {
    Route::get('/api/answers', 'index');
});

Route::controller(CommentController::class)->group(function () {
    Route::get('/api/questions/{question_id}/comments', 'fetch')->where('question_id', '[0-9]+');
    Route::get('/api/answers/{answer_id}/comments', 'fetch')->where('answer_id', '[0-9]+');
});

Route::controller(TagController::class)->group(function () {
    Route::get('/api/tags', 'fetch');
    Route::get('/api/tags/all', 'fetchAll');
    Route::post('/api/tags/{tag}/follow', 'follow')->where('tag', '[0-9]+')->middleware(LoggedMiddleware::class);
});

Route::controller(NotificationController::class)->group(function () {
   Route::get('/api/notifications', 'fetch');
   Route::get('/api/notifications/count', 'count');
});

Route::controller(FileController::class)->middleware(LoggedMiddleware::class)->group(function (){
    Route::patch('/file/upload', 'upload')->name('file.upload');
});

// Authentication
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'authenticate');
    Route::post('/logout', 'logout')->name('logout');
});

Route::controller(RegisterController::class)->middleware('guest')->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register');
});

Route::controller(PasswordResetController::class)->middleware('guest')->group(function () {
    Route::get('/forgot-password', 'show')->name('password.request');
    Route::post('/forgot-password', 'sendToken')->name('password.email');

    Route::get('/reset-password/{token}', 'resetPassword')->name('password.reset');
    Route::post('/reset-password', 'updatePassword')->name('password.update');
});

Route::controller(GoogleController::class)->group(function () {
    Route::get('auth/google', 'redirect')->name('google-auth');
    Route::get('auth/google/call-back', 'callbackGoogle')->name('google-call-back');
});

Route::controller(BlockedController::class)->group(function () {
    Route::get('/banned','show') -> name('banned.user');
});
