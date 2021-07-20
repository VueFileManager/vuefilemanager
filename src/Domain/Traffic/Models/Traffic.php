<?php
namespace Domain\Traffic\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @method static whereYear(string $string, string $string1, int $year)
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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }
}
