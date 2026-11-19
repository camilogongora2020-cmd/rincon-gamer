
<?php

use Illuminate\Support\Facades\Route;

// Controladores de los CRUD existentes
use App\Http\Controllers\GameController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\CommunityMemberController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\GuideController;
use App\Http\Controllers\ReactionController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FriendController;

// Controladores de autenticación
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController as ProfileController;


// ========================================
// RUTAS PUBLICAS
// No requieren autenticación
// ========================================

Route::prefix('auth')->group(function () {

    // Registro de usuario
    Route::post('/register', [AuthController::class, 'register']);

    // Inicio de sesión
    Route::post('/login', [AuthController::class, 'login']);

});


// ========================================
// RUTAS PROTEGIDAS
// Requieren un token válido de Sanctum
// ========================================

Route::middleware('auth:sanctum')->group(function () {

    // Cerrar sesión
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // Perfil del usuario autenticado
    Route::get('/users/profile', [ProfileController::class, 'profile']);

    // Actualizar perfil del usuario autenticado
    Route::put('/users/update', [ProfileController::class, 'update']);

    // CRUD de usuarios
    Route::apiResource('users', UserController::class);

    // CRUD de Rincón Gamer
    Route::apiResource('games', GameController::class);
    Route::apiResource('communities', CommunityController::class);
    Route::apiResource('community-members', CommunityMemberController::class);
    Route::apiResource('friends', FriendController::class);
    Route::apiResource('posts', PostController::class);
    Route::apiResource('comments', CommentController::class);
    Route::apiResource('guides', GuideController::class);
    Route::apiResource('reactions', ReactionController::class);
    Route::apiResource('messages', MessageController::class);
    Route::apiResource('notifications', NotificationController::class);

});
