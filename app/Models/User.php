<?php

namespace App\Models;

use App\Models\Message;
use App\Models\Reaction;
use App\Models\Guide;
use App\Models\Comment;
use App\Models\Post;
use App\Models\CommunityMember;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relación: un usuario puede pertenecer a varias comunidades.
     */
    public function communityMembers()
    {
        return $this->hasMany(CommunityMember::class);
    }
    /**

 * Relación: una comunidad puede tener muchas publicaciones.
 */
public function posts()
{
    return $this->hasMany(Post::class);
}
 /**
 * Relación: un usuario puede escribir muchos comentarios.
 */
public function comments()
{
    return $this->hasMany(Comment::class);

}
  /**
 * Relación: un usuario puede crear muchas guías.
 */
public function guides()
{
    return $this->hasMany(Guide::class);

}
  /**
 * Un usuario puede realizar muchas reacciones.
 */
public function reactions()
{
    return $this->hasMany(Reaction::class);
}
  /**
 * Mensajes enviados por el usuario.
 */
public function sentMessages()
{
    return $this->hasMany(Message::class, 'sender_id');
}

/**
 * Mensajes recibidos por el usuario.
 */
public function receivedMessages()
{
    return $this->hasMany(Message::class, 'receiver_id');
}
}