<?php

namespace App\Models\Oasis;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SubscriptionRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'requested_plan'
    ];

    public $incrementing = false;

    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $order->id = (string)Str::uuid();
        });
    }
}
