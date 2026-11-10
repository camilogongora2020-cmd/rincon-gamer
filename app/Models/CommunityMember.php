<?php

namespace App\Models;
use App\Models\Post;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommunityMember extends Model
{
    protected $fillable = [
        'user_id',
        'community_id',
        'joined_at',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function community(): BelongsTo
    {
        return $this->belongsTo(Community::class);

    }
    /**
 * Relación: una comunidad puede tener muchas publicaciones.
 */
public function posts()
{
    return $this->hasMany(Post::class);
}
}
