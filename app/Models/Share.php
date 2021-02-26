<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Notifications\SharedSendViaEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class Share extends Model
{
    use Notifiable;

    protected $guarded = ['id'];

    protected $appends = ['link'];

    public $incrementing = false;

    protected $keyType = 'string';

    /**
     * Generate share link
     *
     * @return string
     */
    public function getLinkAttribute()
    {
        return url('/shared', ['token' => $this->attributes['token']]);
    }

    /**
     * Model events
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string)Str::uuid();
        });
    }
}
