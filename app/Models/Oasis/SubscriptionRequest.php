<?php

namespace App\Models\Oasis;

use App\Models\User;
use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SubscriptionRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'requested_plan', 'creator', 'status'
    ];

    public $incrementing = false;

    protected $keyType = 'string';

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $order->id = (string)Str::uuid();
            $order->creator = Auth::user()->email ?? $order->creator;
        });
    }
}
