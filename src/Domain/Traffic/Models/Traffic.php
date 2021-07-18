<?php
namespace Domain\Traffic\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Traffic extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'upload',
        'download',
    ];

    public $incrementing = false;

    protected $keyType = 'string';

    /**
     * Model events
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }
}
