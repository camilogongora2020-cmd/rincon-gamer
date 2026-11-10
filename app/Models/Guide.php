<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Guide extends Model
{
    protected $fillable = [
        'user_id',
        'game_id',
        'title',
        'content',
        'image',
    ];

    /**
     * Relación: una guía pertenece a un usuario.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación: una guía pertenece a un videojuego.
     */
    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }
}  //

