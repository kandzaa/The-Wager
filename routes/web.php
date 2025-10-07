<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BalanceController;
use App\Http\Controllers\FriendsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WagerController;
use App\Http\Middleware\AdminMiddleware;
use App\Models\User;
use App\Models\Wager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $wagersCount = Wager::count();
    $usersCount  = User::count();
    return view('dashboard', compact('wagersCount', 'usersCount'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/balance', function () {
    $user           = Auth::user();
    $last           = $user->last_daily_claim_at;
    $nextEligibleAt = $last ? $last->copy()->addDay() : now()->subSecond();
    $canClaim       = now()->greaterThanOrEqualTo($nextEligibleAt);
    return view('balance', compact('canClaim', 'nextEligibleAt'));
})->middleware(['auth', 'verified'])->name('balance');

Route::post('/dailyBalance', [BalanceController::class, 'dailyBalance'])->middleware(['auth', 'verified'])->name('balance.daily');

//profila rouotes
Route::get('/profile', [ProfileController::class, 'index'])->middleware(['auth', 'verified'])->name('profile');
Route::post('/profile/change-username', [ProfileController::class, 'changeUsername'])->middleware(['auth', 'verified'])->name('profile.change-username');
Route::post('/profile/change-email', [ProfileController::class, 'changeEmail'])->middleware(['auth', 'verified'])->name('profile.change-email');
Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])->middleware(['auth', 'verified'])->name('profile.change-password');
//derību routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/wagers', [WagerController::class, 'index'])->name('wagers');
    Route::post('/wagers', [WagerController::class, 'store'])->name('wagers.store');
    Route::get('/wagers/{wager}/edit', [WagerController::class, 'edit'])->name('wagers.edit');
    Route::get('/wagers/search', [WagerController::class, 'search'])->name('wagers.search');
    Route::get('/wagers/{wager}', [WagerController::class, 'show'])->name('wagers.show');
    Route::post('/wagers/{wager}/join', [WagerController::class, 'join'])->name('wagers.join');
    Route::post('/wagers/{wager}/bet', [WagerController::class, 'bet'])->name('wagers.bet');
    Route::get('/wagers/{wager}/stats', [WagerController::class, 'stats'])->name('wagers.stats');
    Route::put('/wagers/{wager}', [WagerController::class, 'update'])->name('wagers.update');
    Route::delete('/wagers/{wager}', [WagerController::class, 'destroy'])->name('wagers.destroy');
    Route::patch('/wagers/{wager}/end', [WagerController::class, 'end'])->name('wagers.end');
});

// FRIENDS ROUTES
Route::get('/friends', [FriendsController::class, 'index'])->middleware(['auth', 'verified'])->name('friends');
Route::get('/friends/search', [FriendsController::class, 'searchUsers'])->name('friends.search');
Route::post('/friends/add', [FriendsController::class, 'addFriend'])->name('friends.add');
Route::post('/friends/remove', [FriendsController::class, 'removeFriend'])->name('friends.remove');
Route::post('/friends/request', [FriendsController::class, 'requestFriend'])->middleware(['auth', 'verified'])->name('friends.request');
Route::post('/friends/accept', [FriendsController::class, 'acceptRequest'])->middleware(['auth', 'verified'])->name('friends.accept');
Route::get('/user/{id}', [FriendsController::class, 'showUser'])->middleware(['auth', 'verified'])->name('user.show');

// admin derību routes
Route::prefix('admin/Manage/wagers')->middleware(['auth', 'verified', AdminMiddleware::class])->group(function () {
    Route::get('/edit/{id}', [AdminController::class, 'editWager'])->name('admin.Manage.wagers.edit');
    Route::put('/{id}', [AdminController::class, 'updateWager'])->name('admin.Manage.wagers.update');
    Route::delete('/{id}', [AdminController::class, 'deleteWager'])->name('admin.Manage.wagers.destroy');
});
//admin user routes
Route::prefix('admin/Manage/users')->middleware(['auth', 'verified', AdminMiddleware::class])->group(function () {
    Route::get('/edit/{id}', [AdminController::class, 'editUser'])->name('admin.Manage.users.edit');
    Route::put('/{id}', [AdminController::class, 'updateUser'])->name('admin.Manage.users.update');
    Route::delete('/{id}', [AdminController::class, 'deleteUser'])->name('admin.Manage.users.destroy');
    Route::get('/{id}', [AdminController::class, 'showUser'])->name('admin.Manage.users.show');
});
//admin views
Route::get('/admin', [AdminController::class, 'index'])->middleware(['auth', 'verified', AdminMiddleware::class])->name('admin');
Route::get('/admin/statistics', [AdminController::class, 'statistics'])->middleware(['auth', 'verified', AdminMiddleware::class])->name('statistics');

require __DIR__ . '/auth.php';
