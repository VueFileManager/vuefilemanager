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

/**
 * App\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property \Illuminate\Contracts\Routing\UrlGenerator|string $avatar
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Client[] $clients
 * @property-read int|null $clients_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\FileManagerFolder[] $favourites
 * @property-read int|null $favourites_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\FileManagerFile[] $files
 * @property-read int|null $files_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\FileManagerFile[] $files_with_trashed
 * @property-read int|null $files_with_trashed_count
 * @property-read mixed $used_capacity
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\FileManagerFile[] $latest_uploads
 * @property-read int|null $latest_uploads_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar', 'role',
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
        'used_capacity', 'storage'
    ];

    /**
     * Get user used storage details
     *
     * @return mixed
     */
    public function getStorageAttribute() {

        return [
            'used' => (float) get_storage_fill_percentage($this->used_capacity, $this->settings->storage_capacity),
            'capacity' => $this->settings->storage_capacity,
            'capacity_formatted' => Metric::gigabytes($this->settings->storage_capacity)->format(),
        ];
    }

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
        return $this->belongsToMany(FileManagerFolder::class, 'favourite_folder', 'user_id', 'folder_unique_id', 'id', 'unique_id')->with('shared:token,id,item_id,permission,protected');
    }

    /**
     * Get 5 latest uploads
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|\Illuminate\Database\Query\Builder
     */
    public function latest_uploads() {

        return $this->hasMany(FileManagerFile::class)->with(['parent'])->orderBy('created_at', 'DESC')->take(40);
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

    /**
     * Get user attributes
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function settings() {

        return $this->hasOne(UserSettings::class);
    }
}
