<?php
namespace Domain\Localization\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static whereLang(string $string)
 * @property string key
 * @property string value
 * @property string lang

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
