<?php
namespace App\Users\Models;

use Illuminate\Support\Str;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use TeamTNT\TNTSearch\Indexer\TNTIndexer;
use Database\Factories\UserSettingFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserSetting extends Model
{
    use Searchable;

    public $timestamps = false;

    protected $guarded = [];

    public $incrementing = false;

    protected $keyType = 'string';

    protected $casts = [
        'id' => 'string',
    ];

    protected $appends = [
        'name',
    ];

    protected static function newFactory(): UserSettingFactory
    {
        return UserSettingFactory::new();
    }

    /**
     * Format avatar to full url
     */
    public function getAvatarAttribute(): array|null
    {
        $link = [];

        // Get avatar from external storage
        if ($this->attributes['avatar'] && isStorageDriver('s3')) {
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

    public function getNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function toSearchableArray(): array
    {
        $name = mb_convert_encoding(
            mb_strtolower($this->name, 'UTF-8'),
            'UTF-8'
        );

        $nameNgrams = (new TNTIndexer)
            ->buildTrigrams(implode(', ', [$name]));

        $emailNgrams = (new TNTIndexer)
            ->buildTrigrams(implode(', ', [$this->user->email]));

        return [
            'id'          => $this->id,
            'name'        => $name,
            'nameNgrams'  => $nameNgrams,
            'email'       => $this->user->email,
            'emailNgrams' => $emailNgrams,
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->id = Str::uuid();
            $user->color = config('vuefilemanager.colors')[rand(0, 9)];
            $user->emoji_type = 'twemoji';
            $user->theme_mode = 'system';
        });
    }
}
