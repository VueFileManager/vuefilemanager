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
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string|array
     */
    public function getAvatarAttribute()
    {
        $link = [];

        // Get avatar from external storage
        if ($this->attributes['avatar'] && ! is_storage_driver('local')) {
            foreach (config('vuefilemanager.avatar_sizes') as $item) {
                $filePath = "avatars/{$item['name']}-{$this->attributes['avatar']}";

                $link[$item['name']] = Storage::temporaryUrl($filePath, now()->addDay());
            }

            return $link;
        }

        // Get avatar from local storage
        if ($this->attributes['avatar']) {
            foreach (config('vuefilemanager.avatar_sizes') as $item) {
                $link[$item['name']] = url("/avatars/{$item['name']}-{$this->attributes['avatar']}");
            }

            return $link;
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
