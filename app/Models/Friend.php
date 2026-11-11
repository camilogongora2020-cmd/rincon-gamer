<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    protected $fillable = [
        'requester_id',
        'receiver_id',
        'status'
    ];

    /**
     * Usuario que envía la solicitud.
     */
    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    /**
     * Usuario que recibe la solicitud.
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}