<?php
namespace App\Users\Models;

use ByteUnits\Metric;
use Illuminate\Support\Str;
use Domain\Files\Models\File;
use Laravel\Cashier\Billable;
use Domain\Folders\Models\Folder;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Collection;
use Database\Factories\UserFactory;
use Domain\Settings\Models\Setting;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use App\Users\Notifications\ResetPassword;
use Domain\Subscriptions\Traits\Subscription;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property string id
 * @property Setting settings
 * @property string email
 * @property mixed favouriteFolders
 * @property string role
 * @method static count()
 * @method static sortable(string[] $array)
 * @method static forceCreate(array $array)
 * @method static where(string $string, string $string1)
 * @method static create(array $array)
 * @method static find(mixed $email)
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use TwoFactorAuthenticatable;
    use Subscription;
    use HasApiTokens;
    use Notifiable;
    use HasFactory;
    use Billable;
    use Sortable;

    protected $guarded = [
        'id',
        'role',
    ];

    protected $fillable = [
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'id'                => 'string',
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'usedCapacity',
        'storage',
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

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }

    /**
     * Get user used storage details
     */
    public function getStorageAttribute(): array
    {
        $is_storage_limit = get_settings('storage_limitation') ?? 1;

        if (! $is_storage_limit) {
            return [
                'used'           => $this->usedCapacity,
                'used_formatted' => Metric::bytes($this->usedCapacity)->format(),
            ];
        }

        return [
            'used'               => (float) get_storage_fill_percentage($this->usedCapacity, $this->settings->storage_capacity),
            'used_formatted'     => get_storage_fill_percentage($this->usedCapacity, $this->settings->storage_capacity) . '%',
            'capacity'           => $this->settings->storage_capacity,
            'capacity_formatted' => format_gigabytes($this->settings->storage_capacity),
        ];
    }

    /**
     * Get user used storage capacity in bytes
     */
    public function getUsedCapacityAttribute(): int
    {
        return $this->filesWithTrashed
            ->map(fn ($item) => $item->getRawOriginal())->sum('filesize');
    }

    /**
     * Get user full folder tree
     */
    public function getFolderTreeAttribute(): Collection
    {
        return Folder::with(['folders.shared', 'shared:token,id,item_id,permission,is_protected,expire_in'])
            ->where('parent_id', null)
            ->where('user_id', $this->id)
            ->sortable()
            ->get();
    }

    /**
     * Get user attributes
     */
    public function settings(): HasOne
    {
        return $this->hasOne(UserSettings::class);
    }

    /**
     * Get user favourites folder
     */
    public function favouriteFolders(): BelongsToMany
    {
        return $this->belongsToMany(Folder::class, 'favourite_folder', 'user_id', 'folder_id', 'id', 'id')
            ->with('shared:token,id,item_id,permission,is_protected,expire_in');
    }

    /**
     * Get all user files
     */
    public function filesWithTrashed(): HasMany
    {
        return $this->hasMany(File::class)
            ->withTrashed();
    }

    /**
     * Get 5 latest uploads
     */
    public function latestUploads(): HasMany
    {
        return $this->hasMany(File::class)
            ->with(['parent:id,name'])
            ->take(40);
    }

    /**
     * Get all user files
     */
    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }

    /**
     * Send the password reset notification.
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPassword($token));
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
