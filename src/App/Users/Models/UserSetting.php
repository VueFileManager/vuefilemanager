<?php
namespace App\Users\Models;

use Illuminate\Support\Str;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use TeamTNT\TNTSearch\Indexer\TNTIndexer;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserSetting extends Model
{
    use Searchable;

    public $timestamps = false;

    protected $guarded = [];

    public $incrementing = false;

    protected $keyType = 'string';

    protected $table = 'user_settings';

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
            $user->color = ['#9ad2bf', '#9ad2cd', '#d29a9a', '#d2ce9a', '#9aadd2', '#c59ad2'][rand(0, 5)];
        });
    }
}
