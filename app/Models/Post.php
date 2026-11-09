<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Comment;

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
    /**
 * Relación: una publicación puede tener muchos comentarios.
 */
    public function comments()
{
    return $this->hasMany(Comment::class);
 }
}