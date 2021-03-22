<?php

namespace App\Models;

use App\Notifications\ResetPassword;
use App\Services\HelperService;
use App\Services\StripeService;
use App\Traits\Oasis;
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

    use Oasis;

    protected $guarded = [
        'id',
        'role'
    ];

    protected $fillable = [
        'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'id'                => 'string',
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'used_capacity',
        'storage'
    ];

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
        // Get tax rates
        $rates = collect(resolve(StripeService::class)->getTaxRates());

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
        return Folder::with(['folders.shared', 'shared:token,id,item_id,permission,is_protected,expire_in'])
            ->where('parent_id', null)
            ->where('user_id', $this->id)
            ->sortable()
            ->get();
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
            'address'      => $billing['billing_address'],
            'city'         => $billing['billing_city'],
            'country'      => $billing['billing_country'],
            'name'         => $billing['billing_name'],
            'phone_number' => $billing['billing_phone_number'],
            'postal_code'  => $billing['billing_postal_code'],
            'state'        => $billing['billing_state'],
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
    public function favouriteFolders()
    {
        return $this->belongsToMany(Folder::class, 'favourite_folder', 'user_id', 'folder_id', 'id', 'id')
            ->with('shared:token,id,item_id,permission,is_protected,expire_in');
    }

    /**
     * Get 5 latest uploads
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|\Illuminate\Database\Query\Builder
     */
    public function latest_uploads()
    {
        return $this->hasMany(File::class)->with(['parent:id,name'])->take(40);
    }

    /**
     * Get all user files
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function files()
    {
        return $this->hasMany(File::class);
    }

    /**
     * Get all user files
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function files_with_trashed()
    {
        return $this->hasMany(File::class)->withTrashed();
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

        static::creating(function ($user) {
            $user->id = Str::uuid();

            // Create user directory for his files
            Storage::makeDirectory("files/$user->id");
        });

        static::deleted(function ($user) {
            resolve(HelperService::class)
                ->erase_user_data($user);
        });
    }
}
