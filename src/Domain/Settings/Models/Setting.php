<?php
namespace Domain\Settings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @method static whereName(string $string)
 * @method static updateOrCreate(array $array, array $array1)
 * @method static forceCreate(array $array)
 * @method static where(string $string, mixed $get)
 * @method static whereIn(string $string, string[] $columns)
 * @method static create(string[] $array)
 * @method static find(array|string $setting)
 * @property string value
 * @property string name
 * @property mixed timezone
 */
class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'value', 'name',
    ];

    public $timestamps = false;

    protected $primaryKey = 'name';

    protected $keyType = 'string';
}
