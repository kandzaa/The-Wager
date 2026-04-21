<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BalanceController;
use App\Http\Controllers\FriendsController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\WagerController;
use App\Http\Middleware\AdminMiddleware;
use App\Models\FriendRequest;
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
    $betsCount   = \App\Models\Wager::sum('pot');

    // Pending wager invitations for this user
    $pendingInvitations = auth()->user()->wagerInvitations()
        ->with(['wager.creator'])
        ->where('status', \App\Models\WagerInvitation::STATUS_PENDING)
        ->where('expires_at', '>', now())
        ->get();

    // Pending friend requests sent TO this user
    $pendingFriendRequests = FriendRequest::with('requester')
        ->where('recipient_id', auth()->id())
        ->where('status', 'pending')
        ->get();

    // Active wagers this user has joined
    $joinedWagers = auth()->user()->wagerPlayers()
        ->with(['wager' => function ($query) {
            $query->withCount('players');
        }])
        ->whereHas('wager', function ($query) {
            $query->where('status', '!=', 'ended');
        })
        ->get()
        ->sortByDesc('wager.ending_time');

    // Pending incoming money transfers (always shown until acted on)
    $incomingTransfers = \App\Models\MoneyTransfer::with('sender')
        ->where('recipient_id', auth()->id())
        ->where('status', 'pending')
        ->latest()
        ->get();

    // Toast notifications: sent transfers that were resolved since last visit
    $seenIds      = session('seen_transfer_ids', []);
    $resolvedToasts = \App\Models\MoneyTransfer::with('recipient')
        ->where('sender_id', auth()->id())
        ->whereIn('status', ['accepted', 'declined'])
        ->whereNotIn('id', $seenIds)
        ->latest()
        ->get();
    session(['seen_transfer_ids' => array_merge($seenIds, $resolvedToasts->pluck('id')->toArray())]);

    return view('dashboard', compact(
        'wagersCount',
        'usersCount',
        'betsCount',
        'pendingInvitations',
        'pendingFriendRequests',
        'joinedWagers',
        'incomingTransfers',
        'resolvedToasts'
    ));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/balance', function () {
    $user           = Auth::user();
    $last           = $user->last_daily_claim_at;
    $nextEligibleAt = $last ? $last->copy()->addHours(3) : now()->subSecond();
    $canClaim       = now()->greaterThanOrEqualTo($nextEligibleAt);
    $friends        = $user->friends()->orderBy('name')->get();
    $incoming       = \App\Models\MoneyTransfer::with('sender')
                        ->where('recipient_id', $user->id)
                        ->where('status', 'pending')
                        ->latest()
                        ->get();
    $sent           = \App\Models\MoneyTransfer::with('recipient')
                        ->where('sender_id', $user->id)
                        ->latest()
                        ->limit(10)
                        ->get();
    return view('balance', compact('canClaim', 'nextEligibleAt', 'friends', 'incoming', 'sent'));
})->middleware(['auth', 'verified'])->name('balance');

Route::post('/dailyBalance', [BalanceController::class, 'dailyBalance'])->middleware(['auth', 'verified'])->name('balance.daily');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/transfers/send', [TransferController::class, 'send'])->name('transfers.send');
    Route::post('/transfers/{transfer}/accept', [TransferController::class, 'accept'])->name('transfers.accept');
    Route::post('/transfers/{transfer}/decline', [TransferController::class, 'decline'])->name('transfers.decline');
});

// Profile routes
Route::get('/profile', [ProfileController::class, 'index'])->middleware(['auth', 'verified'])->name('profile');
Route::post('/profile/change-username', [ProfileController::class, 'changeUsername'])->middleware(['auth', 'verified'])->name('profile.change-username');
Route::post('/profile/change-email', [ProfileController::class, 'changeEmail'])->middleware(['auth', 'verified'])->name('profile.change-email');
Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])->middleware(['auth', 'verified'])->name('profile.change-password');

// Dashboard activity polling endpoint
Route::get('/dashboard/activity', function () {
    $user = auth()->user();
    return response()->json([
        'friend_requests' => \App\Models\FriendRequest::where('recipient_id', $user->id)->where('status', 'pending')->count(),
        'transfers'       => \App\Models\MoneyTransfer::where('recipient_id', $user->id)->where('status', 'pending')->count(),
        'invitations'     => $user->wagerInvitations()->where('status', \App\Models\WagerInvitation::STATUS_PENDING)->where('expires_at', '>', now())->count(),
        'resolved'        => \App\Models\MoneyTransfer::where('sender_id', $user->id)->whereIn('status', ['accepted', 'declined'])->whereNotIn('id', session('seen_transfer_ids', []))->count(),
    ]);
})->middleware(['auth', 'verified'])->name('dashboard.activity');

// Wager routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/wagers', [WagerController::class, 'index'])->name('wagers.index');
    Route::get('/wagers/search', [WagerController::class, 'search'])->name('wagers.search');
    Route::get('/wagers/create', [WagerController::class, 'create'])->name('wagers.create');
    Route::post('/wagers', [WagerController::class, 'store'])->name('wagers.store');
    Route::get('/wagers/{wager}', [WagerController::class, 'show'])->name('wagers.show');
    Route::get('/wagers/{wager}/edit', [WagerController::class, 'edit'])->name('wagers.edit');
    Route::put('/wagers/{wager}', [WagerController::class, 'update'])->name('wagers.update');
    Route::delete('/wagers/{wager}', [WagerController::class, 'destroy'])->name('wagers.destroy');
    Route::post('/wagers/{wager}/join', [WagerController::class, 'join'])->name('wagers.join');
    Route::post('/wagers/{wager}/bet', [WagerController::class, 'bet'])->name('wagers.bet');
    Route::get('/wagers/{wager}/stats', [WagerController::class, 'stats'])->name('wagers.stats');
    Route::get('/wagers/{wager}/end', [WagerController::class, 'showEndForm'])->name('wagers.end.form');
    Route::post('/wagers/{wager}/end', [WagerController::class, 'end'])->name('wagers.end');

    // Invitation routes
    Route::post('/wagers/{wager}/invite', [WagerController::class, 'sendInvitation'])->name('wagers.invite');
    Route::get('/invitations/accept/{token}', [WagerController::class, 'acceptInvitation'])->name('invitations.accept');
    Route::get('/invitations/decline/{token}', [WagerController::class, 'declineInvitation'])->name('invitations.decline');
});

// Friends routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/friends', [FriendsController::class, 'index'])->name('friends');
    Route::get('/friends/search', [FriendsController::class, 'searchUsers'])->name('friends.search');
    Route::post('/friends/add', [FriendsController::class, 'addFriend'])->name('friends.add');
    Route::post('/friends/remove', [FriendsController::class, 'removeFriend'])->name('friends.remove');
    Route::post('/friends/request', [FriendsController::class, 'requestFriend'])->name('friends.request');
    Route::post('/friends/accept', [FriendsController::class, 'acceptRequest'])->name('friends.accept');
    Route::post('/friends/decline', [FriendsController::class, 'declineRequest'])->name('friends.decline'); // NEW
    Route::get('/user/{id}', [FriendsController::class, 'showUser'])->name('user.show');
});

// History routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/history', [HistoryController::class, 'index'])->name('history');
    Route::get('/history/wager/{wager}', [WagerController::class, 'results'])->name('history.wager.show');
    Route::get('/wagers/{wager}/results', [HistoryController::class, 'show'])->name('wagers.results');
});

// Cosmetic routes
Route::post('/cosmetics/buy',   [App\Http\Controllers\CosmeticController::class, 'buy'])->name('cosmetics.buy');
Route::post('/cosmetics/equip', [App\Http\Controllers\CosmeticController::class, 'equip'])->name('cosmetics.equip');

// Admin routes
Route::get('/admin', [AdminController::class, 'index'])->middleware(['auth', 'verified', AdminMiddleware::class])->name('admin');

// Admin management routes
Route::prefix('admin/Manage')->middleware(['auth', 'verified', AdminMiddleware::class])->group(function () {
    Route::get('/users', [AdminController::class, 'users'])->name('admin.Manage.users');
    
    Route::get('/wagers', [AdminController::class, 'wagers'])->name('admin.Manage.wagers');
    
    // User management routes
    Route::prefix('users')->group(function () {
        Route::get('/edit/{id}', [AdminController::class, 'editUser'])->name('admin.Manage.users.edit');
        Route::put('/{id}', [AdminController::class, 'updateUser'])->name('admin.Manage.users.update');
        Route::delete('/{id}', [AdminController::class, 'deleteUser'])->name('admin.Manage.users.destroy');
        Route::get('/{id}', [AdminController::class, 'showUser'])->name('admin.Manage.users.show');
    });
    
    // Wager management routes
    Route::prefix('wagers')->group(function () {
        Route::get('/edit/{id}', [AdminController::class, 'editWager'])->name('admin.Manage.wagers.edit');
        Route::put('/{id}', [AdminController::class, 'updateWager'])->name('admin.Manage.wagers.update');
        Route::delete('/{id}', [AdminController::class, 'deleteWager'])->name('admin.Manage.wagers.destroy');
    });

    // Customizations routes
    Route::prefix('customizations')->group(function () {
        Route::get('/', [AdminController::class, 'customizations'])->name('admin.Manage.customizations');
        Route::post('/', [AdminController::class, 'storeCosmetic'])->name('admin.Manage.customizations.store');
        Route::put('/{id}', [AdminController::class, 'updateCosmetic'])->name('admin.Manage.customizations.update');
        Route::delete('/{id}', [AdminController::class, 'destroyCosmetic'])->name('admin.Manage.customizations.destroy');
    });
});

require __DIR__ . '/auth.php';