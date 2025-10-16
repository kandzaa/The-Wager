<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BalanceController;
use App\Http\Controllers\FriendsController;
use App\Http\Controllers\HistoryController;
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

    $pendingInvitations = auth()->user()->wagerInvitations()
        ->with('wager')
        ->where('status', \App\Models\WagerInvitation::STATUS_PENDING)
        ->where('expires_at', '>', now())
        ->get();

    $joinedWagers = auth()->user()->wagerPlayers()
        ->with(['wager' => function ($query) {
            $query->withCount('players');
        }])
        ->whereHas('wager', function ($query) {
            $query->where('status', '!=', 'ended');
        })
        ->get()
        ->sortByDesc('wager.ending_time');

    return view('dashboard', compact('wagersCount', 'usersCount', 'pendingInvitations', 'joinedWagers'));
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
    Route::get('/wagers/search', [WagerController::class, 'search'])->name('wagers.search');
    Route::resource('wagers', WagerController::class)->only([
        'index', 'create', 'store', 'show', 'edit', 'update', 'destroy',
    ]);
    Route::post('/wagers/{wager}/join', [WagerController::class, 'join'])->name('wagers.join');
    Route::post('/wagers/{wager}/bet', [WagerController::class, 'bet'])->name('wagers.bet');
    Route::get('/wagers/{wager}/stats', [WagerController::class, 'stats'])->name('wagers.stats');
    Route::get('/wagers/{wager}/results', [WagerController::class, 'results'])->name('wagers.results');
    Route::get('/wagers/{wager}/end', [WagerController::class, 'showEndForm'])->name('wagers.end.form');
    Route::post('/wagers/{wager}/end', [WagerController::class, 'end'])->name('wagers.end');

    // Invitation routes
    Route::post('/wagers/{wager}/invite', [WagerController::class, 'sendInvitation'])->name('wagers.invite');
    Route::get('/invitations/accept/{token}', [WagerController::class, 'acceptInvitation'])->name('invitations.accept');
    Route::get('/invitations/decline/{token}', [WagerController::class, 'declineInvitation'])->name('invitations.decline');

    // Show end wager form
    Route::get('/wagers/{wager}/end', [WagerController::class, 'showEndForm'])->name('wagers.end.form');
    // Process end wager form submission
    Route::post('/wagers/{wager}/end', [WagerController::class, 'end'])->name('wagers.end');

    Route::get('/wagers/{wager}/results', [WagerController::class, 'results'])->name('wagers.results');
});

// FRIENDS ROUTES
Route::get('/friends', [FriendsController::class, 'index'])->middleware(['auth', 'verified'])->name('friends');
Route::get('/friends/search', [FriendsController::class, 'searchUsers'])->name('friends.search');
Route::post('/friends/add', [FriendsController::class, 'addFriend'])
    ->middleware(['auth', 'verified'])
    ->name('friends.add');
Route::post('/friends/remove', [FriendsController::class, 'removeFriend'])
    ->middleware(['auth', 'verified'])
    ->name('friends.remove');
Route::post('/friends/request', [FriendsController::class, 'requestFriend'])->middleware(['auth', 'verified'])->name('friends.request');
Route::post('/friends/accept', [FriendsController::class, 'acceptRequest'])->middleware(['auth', 'verified'])->name('friends.accept');
Route::get('/user/{id}', [FriendsController::class, 'showUser'])->middleware(['auth', 'verified'])->name('user.show');

// History routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/history', [HistoryController::class, 'index'])->name('history');
    Route::get('/history/wager/{wager}', [HistoryController::class, 'show'])->name('history.wager.show');
});

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
// Admin routes
Route::get('/admin', [AdminController::class, 'index'])->middleware(['auth', 'verified', AdminMiddleware::class])->name('admin');
Route::get('/admin/statistics', [\App\Http\Controllers\Admin\StatisticsController::class, 'index'])->middleware(['auth', 'verified', AdminMiddleware::class])->name('admin.statistics');

require __DIR__ . '/auth.php';
