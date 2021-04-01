<?php

namespace App\Models;

use App\Services\HelperService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

/**
 * @method static whereLocale(string $param)
 */
class Language extends Model
{
    use Sortable;

    public $sortable = [
        'created_at',
    ];

    protected $guarded = [
        'id'
    ];

    protected $keyType = 'string';

    protected $primaryKey = 'id';

    public $incrementing = false;

    public function languageStrings()
    {
        return $this->hasMany(LanguageString::class, 'lang', 'locale');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($language) {
            $language->id = Str::uuid();

            resolve(HelperService::class)
                ->create_default_language_strings(
                    get_setting('license') ?? 'extended', $language->locale
                );
        });

        static::updating(function ($language) {
            cache()->forget("language-strings-$language->locale");
        });

        static::deleting(function ($language) {
            DB::table('language_strings')
                ->whereLang($language->locale)
                ->delete();

            cache()->forget("language-strings-$language->locale");
        });
    }
}
