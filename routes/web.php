<?php

use App\Http\Controllers\MatchController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MatchController::class, 'index'])->name('matches.index');
Route::get('/live', [MatchController::class, 'live'])->name('matches.live');
Route::get('/finished', [MatchController::class, 'finished'])->name('matches.finished');
Route::get('/scheduled', [MatchController::class, 'scheduled'])->name('matches.scheduled');
Route::get('/league/{league}', [MatchController::class, 'league'])->name('league.matches');
Route::get('/matches/{id}', [MatchController::class, 'show'])->name('matches.show');


// AJAX endpoints
Route::post('/refresh-live-matches', [MatchController::class, 'refreshLiveMatches'])->name('matches.refresh-live');
