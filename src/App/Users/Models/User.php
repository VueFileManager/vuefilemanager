<?php
namespace App\Users\Models;

use ByteUnits\Metric;
use Illuminate\Support\Str;
use Domain\Files\Models\File;
use Domain\Folders\Models\Folder;
use Laravel\Sanctum\HasApiTokens;
use Domain\Traffic\Models\Traffic;
use Illuminate\Support\Facades\DB;
use Database\Factories\UserFactory;
use Domain\Settings\Models\Setting;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use App\Users\Notifications\ResetPassword;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Users\Restrictions\RestrictionsManager;
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
 * @property string email_verified_at
 * @method static count()
 * @method static sortable(string[] $array)
 * @method static forceCreate(array $array)
 * @method static where(string $string, string $string1)
 * @method static create(array $array)
 * @method static find(mixed $email)
 * @method canUpload(int $size)
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use TwoFactorAuthenticatable;
    use HasApiTokens;
    use Notifiable;
    use HasFactory;
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
            'used'               => (float) get_storage_percentage($this->usedCapacity, $this->limitations->max_storage_amount),
            'used_formatted'     => get_storage_percentage($this->usedCapacity, $this->limitations->max_storage_amount) . '%',
            'capacity'           => $this->limitations->max_storage_amount,
            'capacity_formatted' => format_gigabytes($this->limitations->max_storage_amount),
        ];
    }

    /**
     * Get user used storage capacity in bytes
     */
    public function getUsedCapacityAttribute(): int
    {
        return DB::table('files')
            ->where('user_id', $this->id)
            ->sum('filesize');
    }

    public function settings(): HasOne
    {
        return $this->hasOne(UserSetting::class);
    }

    public function limitations(): HasOne
    {
        return $this->hasOne(UserLimitation::class);
    }

    /**
     * Get user favourites folder
     */
    public function favouriteFolders(): BelongsToMany
    {
        return $this->belongsToMany(Folder::class, 'favourite_folder', 'user_id', 'parent_id', 'id', 'id');
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
            ->with([
                'parent:id,name',
                'shared:token,id,item_id,permission,is_protected,expire_in',
            ])
            ->take(40);
    }

    /**
     * Get all user files
     */
    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }

    public function folders(): HasMany
    {
        return $this->hasMany(Folder::class);
    }

    public function traffics(): HasMany
    {
        return $this->hasMany(Traffic::class);
    }

    /**
     * Send the password reset notification.
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPassword($token));
    }

    public function __call($method, $parameters)
    {
        if (str_starts_with($method, 'can')) {
            return resolve(RestrictionsManager::class)
                ->driver()
                ->$method($this, ...$parameters);
        }

        return parent::__call($method, $parameters);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->id = Str::uuid();

            // Create default limitations
            $user->limitations()->create([
                'max_storage_amount' => get_settings('default_max_storage_amount') ?? 1,
            ]);

            // Create user directory for his files
            Storage::makeDirectory("files/$user->id");
        });

        static::updating(function ($user) {
            // Prevent to set 2fa in demo mode
            if (config('vuefilemanager.is_demo') && $user->email === 'howdy@hi5ve.digital') {
                $user->two_factor_secret = null;
                $user->two_factor_recovery_codes = null;
            }
        });
    }
}
