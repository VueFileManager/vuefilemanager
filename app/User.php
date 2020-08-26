<?php

namespace App;

use App\Notifications\ResetPassword;
use App\Notifications\ResetUserPasswordNotification;
use ByteUnits\Metric;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Cashier\Billable;
use Laravel\Passport\HasApiTokens;
use Kyslik\ColumnSortable\Sortable;
use Rinvex\Subscriptions\Traits\HasSubscriptions;

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
 * @property string $role
 * @property string|null $stripe_id
 * @property string|null $card_brand
 * @property string|null $card_last_four
 * @property string|null $trial_ends_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\FileManagerFolder[] $favourite_folders
 * @property-read int|null $favourite_folders_count
 * @property-read mixed $folder_tree
 * @property-read mixed $storage
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Invoice[] $invoices
 * @property-read int|null $invoices_count
 * @property-read int|null $payment_cards_count
 * @property-read \App\UserSettings|null $settings
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Cashier\Subscription[] $subscriptions
 * @property-read int|null $subscriptions_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCardBrand($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCardLastFour($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereStripeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereTrialEndsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User sortable($defaultParameters = null)
 */
class User extends Authenticatable
{
    use HasApiTokens, Notifiable, Billable, Sortable;

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
        if (! $is_storage_limit) {

            return [
                'used' => $this->used_capacity,
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
        return FileManagerFolder::with(['folders.shared', 'shared:token,id,item_id,permission,protected,expire_in'])
            ->where('parent_id', 0)
            ->where('user_id', $this->id)
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
        return $this->hasMany(FileManagerFile::class)->with(['parent'])->orderBy('created_at', 'DESC')->take(40);
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
}
