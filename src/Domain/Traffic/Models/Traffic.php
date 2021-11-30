<?php
namespace Domain\Traffic\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property string id
 * @property string user_id
 * @property int upload
 * @property int download
 */
class Traffic extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $incrementing = false;

    protected $keyType = 'string';

    public function scopeCurrentDay($query): Builder
    {
        return $query->whereDate('created_at', today());
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(fn ($model) => $model->id = (string) Str::uuid());
    }
}
