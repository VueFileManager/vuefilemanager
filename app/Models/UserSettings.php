<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class UserSettings extends Model
{
    public $timestamps = false;

    protected $guarded = [
        'id',
        'storage_capacity'
    ];

    /**
     * Format avatar to full url
     *
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function getAvatarAttribute()
    {
        // Get avatar from external storage
        if ($this->attributes['avatar'] && is_storage_driver(['s3', 'spaces', 'wasabi', 'backblaze'])) {
            return Storage::temporaryUrl($this->attributes['avatar'], now()->addDay());
        }

        // Get avatar from local storage
        if ($this->attributes['avatar']) {
            return url('/' . $this->attributes['avatar']);
        }

        return url('/assets/images/' . 'default-avatar.png');
    }
}
