<?php
namespace Domain\Localization\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static whereLang(string $string)
 */
class LanguageTranslation extends Model
{
    public $timestamps = false;

    public $primaryKey = null;

    public $incrementing = false;

    protected $fillable = [
        'value',
    ];
}
