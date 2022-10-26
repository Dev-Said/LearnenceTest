<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\PollController;
use App\Http\Controllers\VoteController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified', 'admin'])->group(function() {
    Route::resource('polls', PollController::class);
    Route::get('polls/{poll}/clear', [PollController::class, 'clearVotes'])->name('polls.clear-votes');
});

Route::middleware(['auth', 'verified'])->group(function() {
    Route::get('votes', [VoteController::class, 'index'])->name('votes.index');
    Route::get('votes/{poll}', [VoteController::class, 'show'])->name('votes.show');
    Route::patch('votes/{poll}', [VoteController::class, 'voteForPoll'])->name('votes.update');
});

require __DIR__.'/auth.php';
