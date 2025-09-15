<?php
use App\Http\Controllers\FriendsController;
use App\Http\Controllers\WagerController;
use App\Http\Middleware\AdminMiddleware;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/wagers', [WagerController::class, 'index'])->middleware(['auth', 'verified'])->name('wagers');
Route::post('/wagers', [WagerController::class, 'create'])->middleware(['auth', 'verified'])->name('wager.create');
Route::get('/wagers/search', [WagerController::class, 'search'])->middleware(['auth', 'verified'])->name('wagers.search');
Route::put('/wagers/{wager}', [WagerController::class, 'update'])->middleware(['auth', 'verified'])->name('wagers.update');
Route::delete('/wagers/{wager}', [WagerController::class, 'destroy'])->middleware(['auth', 'verified'])->name('wagers.destroy');

Route::get('/friends', [FriendsController::class, 'index'])->middleware(['auth', 'verified'])->name('friends');
Route::get('/friends/search', [FriendsController::class, 'searchUsers'])->name('friends.search');
Route::post('/friends/add', [FriendsController::class, 'addFriend'])->name('friends.add');
Route::post('/friends/remove', [FriendsController::class, 'removeFriend'])->name('friends.remove');

Route::post('/friends/request', [FriendsController::class, 'requestFriend'])->middleware(['auth', 'verified'])->name('friends.request');
Route::post('/friends/accept', [FriendsController::class, 'acceptRequest'])->middleware(['auth', 'verified'])->name('friends.accept');

Route::get('/user/{id}', [FriendsController::class, 'showUser'])->middleware(['auth', 'verified'])->name('user.show');

Route::get('/wagers/{id}', [WagerController::class, 'showWager'])->middleware(['auth', 'verified'])->name('wager.show');
Route::post('/wagers/{wager}/bet', [WagerController::class, 'bet'])->middleware(['auth', 'verified'])->name('wagers.bet');
Route::get('/wagers/{wager}/stats', [WagerController::class, 'stats'])->middleware(['auth', 'verified'])->name('wagers.stats');
Route::post('/wagers/{wager}/join', [WagerController::class, 'join'])->middleware(['auth', 'verified'])->name('wagers.join');

Route::get('/admin/edit/{id}', [AdminControll::class, 'edit'])->middleware(['auth', 'verified', AdminMiddleware::class])->name('admin.edit');
Route::get('/admin/delete/{id}', [AdminControll::class, 'delete'])->middleware(['auth', 'verified', AdminMiddleware::class])->name('admin.delete');

Route::get('/admin', function () {
    $users  = User::orderBy('id')->get(['id', 'name', 'email', 'created_at']);
    $wagers = Wagers::orderBy('id')->get(['id', 'name', 'creator_id', 'created_at']);
    return view('Admin.admin', compact('users', 'wagers'));
})->middleware(['auth', 'verified', AdminMiddleware::class])->name('admin');

Route::get('/balance', function () {
    return view('balance');
})->middleware(['auth', 'verified'])->name('balance');

Route::get('/profile', function () {
    return view('profile');
})->middleware(['auth', 'verified'])->name('profile');

require __DIR__ . '/auth.php';
