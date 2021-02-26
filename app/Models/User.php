<?php

namespace App\Models;

use App\Notifications\ResetPassword;
use App\Notifications\ResetUserPasswordNotification;
use App\UserSettings;
use ByteUnits\Metric;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Cashier\Billable;
use Kyslik\ColumnSortable\Sortable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, Billable, Sortable, HasFactory, HasApiTokens;

    protected $guarded = ['id', 'role'];

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
    ];

    protected $appends = [
        'used_capacity', 'storage'
    ];

    /**
     * Sortable columns
     *
     * @var string[]
     */
    public $sortable = [
        'id',
        'name',
        'role',
        'created_at',
        'storage_capacity',
    ];

    public $incrementing = false;

    protected $keyType = 'string';

    /**
     * Get tax rate id for user
     *
     * @return array
     */
    public function taxRates()
    {
        $stripe = resolve('App\Services\StripeService');

        // Get tax rates
        $rates = collect($stripe->getTaxRates());

        // Find tax rate
        $user_tax_rate = $rates->first(function ($item) {
            return $item['jurisdiction'] === $this->settings->billing_country && $item['active'];
        });

        return $user_tax_rate ? [$user_tax_rate['id']] : [];
    }

    /**
     * Get user used storage details
     *
     * @return mixed
     */
    public function getStorageAttribute()
    {
        // Get storage limitation setup
        $storage_limitation = get_setting('storage_limitation');
        $is_storage_limit = $storage_limitation ? $storage_limitation : 1;

        // Get user storage usage
        if (!$is_storage_limit) {

            return [
                'used'           => $this->used_capacity,
                'used_formatted' => Metric::bytes($this->used_capacity)->format(),
            ];
        }

        return [
            'used'               => (float)get_storage_fill_percentage($this->used_capacity, $this->settings->storage_capacity),
            'used_formatted'     => get_storage_fill_percentage($this->used_capacity, $this->settings->storage_capacity) . '%',
            'capacity'           => $this->settings->storage_capacity,
            'capacity_formatted' => format_gigabytes($this->settings->storage_capacity),
        ];
    }

    /**
     * Get user used storage capacity in bytes
     *
     * @return mixed
     */
    public function getUsedCapacityAttribute()
    {
        $user_capacity = $this->files_with_trashed->map(function ($item) {
            return $item->getRawOriginal();
        })->sum('filesize');

        return $user_capacity;
    }

    /**
     * Get user full folder tree
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getFolderTreeAttribute()
    {
        // Get sorting setup

        return FileManagerFolder::with(['folders.shared', 'shared:token,id,item_id,permission,protected,expire_in'])
            ->where('parent_id', 0)
            ->where('user_id', $this->id)
            ->sortable()
            ->get();
    }

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

    /**
     * Set user billing info
     *
     * @param $billing
     * @return UserSettings
     */
    public function setBilling($billing)
    {
        $this->settings()->update([
            'billing_address'      => $billing['billing_address'],
            'billing_city'         => $billing['billing_city'],
            'billing_country'      => $billing['billing_country'],
            'billing_name'         => $billing['billing_name'],
            'billing_phone_number' => $billing['billing_phone_number'],
            'billing_postal_code'  => $billing['billing_postal_code'],
            'billing_state'        => $billing['billing_state'],
        ]);

        return $this->settings;
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
     * Record user upload filesize
     *
     * @param $file_size
     */
    public function record_upload($file_size)
    {
        $now = Carbon::now();

        $record = Traffic::whereYear('created_at', '=', $now->year)
            ->whereMonth('created_at', '=', $now->month)
            ->firstOrCreate([
                'user_id' => $this->id,
            ]);

        $record->update([
            'upload' => $record->upload + $file_size
        ]);
    }

    /**
     * Record user download filesize
     *
     * @param $file_size
     */
    public function record_download($file_size)
    {
        $now = Carbon::now();

        $record = Traffic::whereYear('created_at', '=', $now->year)
            ->whereMonth('created_at', '=', $now->month)
            ->firstOrCreate([
                'user_id' => $this->id,
            ]);

        $record->update([
            'download' => $record->download + $file_size
        ]);
    }

    /**
     * Get user favourites folder
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function favourite_folders()
    {
        return $this->belongsToMany(FileManagerFolder::class, 'favourite_folder', 'user_id', 'folder_unique_id', 'id', 'unique_id')->with('shared:token,id,item_id,permission,protected,expire_in');
    }

    /**
     * Get 5 latest uploads
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|\Illuminate\Database\Query\Builder
     */
    public function latest_uploads()
    {
        return $this->hasMany(FileManagerFile::class)->with(['parent'])->take(40);
    }

    /**
     * Get all user files
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function files()
    {
        return $this->hasMany(FileManagerFile::class);
    }

    /**
     * Get all user files
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function files_with_trashed()
    {
        return $this->hasMany(FileManagerFile::class)->withTrashed();
    }

    /**
     * Get user attributes
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function settings()
    {
        return $this->hasOne(UserSettings::class);
    }

    /**
     * Model Events
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string)Str::uuid();
        });
    }
}
