<?php
namespace App\Models\Oasis;

use Auth;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubscriptionRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'requested_plan', 'creator', 'status',
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
            $order->id = (string) Str::uuid();
            $order->creator = Auth::user()->email ?? $order->creator;
        });
    }
}
