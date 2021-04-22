<?php

namespace App\Models\Oasis;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;
use TeamTNT\TNTSearch\Indexer\TNTIndexer;

class Client extends Model
{
    use HasFactory, Searchable;

    public $guarded = ['id'];

    public $incrementing = false;

    protected $keyType = 'string';

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

        static::creating(function ($order) {
            $order->id = (string) Str::uuid();
        });
    }
}
