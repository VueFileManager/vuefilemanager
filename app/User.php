<?php

namespace App;

use App\Notifications\ResetPassword;
use App\Notifications\ResetUserPasswordNotification;
use ByteUnits\Metric;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'favourites'        => 'array',
    ];

    protected $appends = [
        'used_capacity'
    ];

    /**
     * Get user used storage capacity in bytes
     *
     * @return mixed
     */
    public function getUsedCapacityAttribute() {

        $user_capacity = $this->files_with_trashed->map(function ($item) {
            return $item->getOriginal();
        })->sum('filesize');

        return $user_capacity;
    }

    /**
     * Format avatar to full url
     *
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function getAvatarAttribute()
    {
        if ($this->attributes['avatar']) {
            return url('/' . $this->attributes['avatar']);
        }

        return url('/assets/images/' . 'default-avatar.png');
    }

    /**
     * Send the password reset notification.
     *
     * @param string $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    /**
     * Get user favourites folder
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function favourites()
    {
        return $this->belongsToMany(FileManagerFolder::class, 'favourite_folder', 'user_id', 'folder_unique_id', 'id', 'unique_id')->select(['unique_id', 'name', 'type']);
    }

    /**
     * Get 5 latest uploads
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|\Illuminate\Database\Query\Builder
     */
    public function latest_uploads() {

        return $this->hasMany(FileManagerFile::class)->orderBy('created_at', 'DESC')->take(7);
    }

    /**
     * Get all user files
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function files() {

        return $this->hasMany(FileManagerFile::class);
    }

    /**
     * Get all user files
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function files_with_trashed() {

        return $this->hasMany(FileManagerFile::class)->withTrashed();
    }
}
