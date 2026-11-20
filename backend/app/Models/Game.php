<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Guide;

class Game extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image',
        'genre',
    ];
    /**
 * Relación: un videojuego puede tener muchas guías.
 */
public function guides()
{
    return $this->hasMany(Guide::class);
}
}