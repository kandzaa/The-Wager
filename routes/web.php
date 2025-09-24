<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\FriendsController;
use App\Http\Controllers\WagerController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/balance', function () {
    return view('balance');
})->middleware(['auth', 'verified'])->name('balance');

Route::get('/profile', function () {
    return view('profile');
})->middleware(['auth', 'verified'])->name('profile');

// WAGER ROUTES - IMPORTANT: Specific routes MUST come before dynamic routes
Route::middleware(['auth'])->group(function () {
    Route::get('/wagers', [WagerController::class, 'index'])->name('wagers');
    Route::post('/wagers', [WagerController::class, 'store'])->name('wagers.store');

    // CRITICAL: These specific routes must come BEFORE /wagers/{id}
    Route::get('/wagers/search', [WagerController::class, 'search'])->name('wagers.search');

    // Dynamic route comes after specific routes
    Route::get('/wagers/{id}', [WagerController::class, 'show'])->name('wagers.show');

    // Other wager routes
    Route::post('/wagers/{wager}/join', [WagerController::class, 'join'])->name('wagers.join');
    Route::post('/wagers/{wager}/bet', [WagerController::class, 'bet'])->name('wagers.bet');
    Route::get('/wagers/{wager}/stats', [WagerController::class, 'stats'])->name('wagers.stats');
    Route::put('/wagers/{wager}', [WagerController::class, 'update'])->name('wagers.update');
    Route::delete('/wagers/{wager}', [WagerController::class, 'destroy'])->name('wagers.destroy');
});

// FRIENDS ROUTES
Route::get('/friends', [FriendsController::class, 'index'])->middleware(['auth', 'verified'])->name('friends');
Route::get('/friends/search', [FriendsController::class, 'searchUsers'])->name('friends.search');
Route::post('/friends/add', [FriendsController::class, 'addFriend'])->name('friends.add');
Route::post('/friends/remove', [FriendsController::class, 'removeFriend'])->name('friends.remove');
Route::post('/friends/request', [FriendsController::class, 'requestFriend'])->middleware(['auth', 'verified'])->name('friends.request');
Route::post('/friends/accept', [FriendsController::class, 'acceptRequest'])->middleware(['auth', 'verified'])->name('friends.accept');
Route::get('/user/{id}', [FriendsController::class, 'showUser'])->middleware(['auth', 'verified'])->name('user.show');

// ADMIN ROUTES
Route::prefix('admin/Manage/wagers')->middleware(['auth', 'verified', AdminMiddleware::class])->group(function () {
    Route::get('/edit/{id}', [AdminController::class, 'editWager'])->name('admin.Manage.wagers.edit');
    Route::put('/{id}', [AdminController::class, 'updateWager'])->name('admin.Manage.wagers.update');
    Route::delete('/{id}', [AdminController::class, 'deleteWager'])->name('admin.Manage.wagers.destroy');
});

Route::prefix('admin/Manage/users')->middleware(['auth', 'verified', AdminMiddleware::class])->group(function () {
    Route::get('/edit/{id}', [AdminController::class, 'editUser'])->name('admin.Manage.users.edit');
    Route::put('/{id}', [AdminController::class, 'updateUser'])->name('admin.Manage.users.update');
    Route::delete('/{id}', [AdminController::class, 'deleteUser'])->name('admin.Manage.users.destroy');
    Route::get('/{id}', [AdminController::class, 'showUser'])->name('admin.Manage.users.show');
});

Route::get('/admin', [AdminController::class, 'index'])->middleware(['auth', 'verified', AdminMiddleware::class])->name('admin');
Route::get('/statistics', [AdminController::class, 'statistics'])->middleware(['auth', 'verified', AdminMiddleware::class])->name('statistics');

require __DIR__ . '/auth.php';
