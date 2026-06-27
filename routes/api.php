<?php

use App\Http\Controllers\ScoreController;
use Illuminate\Support\Facades\Route;

// Papan pendahulu — leaderboard Algebra Mission.
Route::get('/scores', [ScoreController::class, 'index']);   // GET  /api/scores
Route::post('/scores', [ScoreController::class, 'store']);  // POST /api/scores
