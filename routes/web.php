<?php
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WagerController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/wagers', [WagerController::class, 'index'])->middleware(['auth', 'verified'])->name('wagers');

Route::post('/wagers', [WagerController::class, 'create'])->middleware(['auth', 'verified'])->name('wager.create');

Route::get('/friends', function () {
    return view('friends');
})->middleware(['auth', 'verified'])->name('friends');

Route::get('/balance', function () {
    return view('balance');
})->middleware(['auth', 'verified'])->name('balance');

Route::get('/admin', function () {
    return view('admin/admin');
})->middleware(['auth', 'verified', AdminMiddleware::class])->name('admin');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
