<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    protected $fillable = [
        'user_id',
        'community_id',
        'title',
        'content',
        'image',
    ];

    /**
     * Relación: una publicación pertenece a un usuario.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación: una publicación pertenece a una comunidad.
     */
    public function community(): BelongsTo
    {
        return $this->belongsTo(Community::class);
    }
}