<?php

use App\Http\Controllers\GameController;
use App\Http\Controllers\CommunityController;
use Illuminate\Support\Facades\Route;

Route::apiResource('games', GameController::class);
Route::apiResource('communities', CommunityController::class);