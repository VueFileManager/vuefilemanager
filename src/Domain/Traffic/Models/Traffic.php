<?php
namespace Domain\Traffic\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @method static whereYear(string $string, string $string1, int $year)
 * @method static currentMonth()
 */
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

    public function scopeCurrentMonth($query): Builder
    {
        return $query
            ->whereYear('created_at', '=', now()->year)
            ->whereMonth('created_at', '=', now()->month);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }
}
