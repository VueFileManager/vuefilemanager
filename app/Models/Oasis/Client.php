<?php

namespace App\Models\Oasis;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;
use TeamTNT\TNTSearch\Indexer\TNTIndexer;

/**
 * @method static whereUserId($id)
 */
class Client extends Model
{
    use HasFactory, Searchable;

    public $guarded = ['id'];

    public $incrementing = false;

    protected $keyType = 'string';

    /**
     * Format avatar to full url
     *
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function getAvatarAttribute()
    {
        // Get avatar from external storage
        if ($this->attributes['avatar'] && ! is_storage_driver('local')) {
            return Storage::temporaryUrl($this->attributes['avatar'], now()->addDay());
        }

        // Get avatar from local storage
        if ($this->attributes['avatar']) {
            return url('/' . $this->attributes['avatar']);
        }

        return url('/assets/images/default-avatar.png');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'client_id', 'id');
    }

    /**
     * Index file
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $array = $this->toArray();

        $client_name = Str::slug($array['name'], ' ');
        $client_email = Str::slug($array['email'], ' ');

        return [
            'id'                  => $this->id,
            'clientName'          => $array['name'],
            'clientNameNgrams'    => utf8_encode((new TNTIndexer)->buildTrigrams(implode(', ', [$client_name]))),
            'clientEmail'          => $array['email'],
            'clientEmailNgrams'    => utf8_encode((new TNTIndexer)->buildTrigrams(implode(', ', [$client_email]))),
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($client) {
            $client->id = (string) Str::uuid();
        });

        static::deleting(function ($client) {
            if ($client->getRawOriginal('avatar')) {
                Storage::delete($client->getRawOriginal('avatar'));
            }
        });
    }
}
