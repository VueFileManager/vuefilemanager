<?php
namespace Tests\Models;

use Illuminate\Support\Str;
use Tests\Factories\UserFactory;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use VueFileManager\Subscription\App\User\Traits\Billable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends Model implements AuthorizableContract, AuthenticatableContract
{
    use Notifiable;
    use Authorizable;
    use Authenticatable;
    use HasFactory;
    use Billable;

    protected $guarded = [];

    protected $casts = [
        'id'                => 'string',
        'email_verified_at' => 'datetime',
    ];

    protected $table = 'users';

    public $incrementing = false;

    protected $keyType = 'string';

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->id = Str::uuid();

            // Create user directory for his files
            Storage::makeDirectory("files/$user->id");
        });
    }
}
