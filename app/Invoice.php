<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $casts = [
        'seller' => 'array',
        'client' => 'array',
        'bag'    => 'array',
    ];

    public function user() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
