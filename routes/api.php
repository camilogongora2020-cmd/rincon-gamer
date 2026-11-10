<?php

use App\Http\Controllers\GameController;
use App\Http\Controllers\CommunityController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommunityMemberController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\GuideController;

Route::apiResource('games', GameController::class);
Route::apiResource('communities', CommunityController::class);
Route::apiResource('community-members', CommunityMemberController::class);
Route::apiResource('posts', PostController::class);
Route::apiResource('comments', CommentController::class);
Route::apiResource('guides', GuideController::class);
