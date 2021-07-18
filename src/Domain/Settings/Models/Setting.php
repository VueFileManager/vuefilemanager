<?php
namespace Domain\Settings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @method static whereName(string $string)
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
