<?php
namespace App\Users\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class UserSettings extends Model
{
    public $timestamps = false;

    protected $guarded = [
        'id',
        'storage_capacity',
    ];

    /**
     * Format avatar to full url
     *
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function getAvatarAttribute()
    {
        // Get avatar from external storage
        if ($this->attributes['avatar'] && ! is_storage_driver('local')) {
            return Storage::temporaryUrl($this->attributes['avatar'], now()->addDay());
        }

        // Get avatar from local storage
        if ($this->attributes['avatar']) {
            return url('/' . $this->attributes['avatar']);
        }

        return null;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->color = ['#9ad2bf', '#9ad2cd', '#d29a9a', '#d2ce9a', '#9aadd2', '#c59ad2'][rand(0, 4)];
        });
    }
}
