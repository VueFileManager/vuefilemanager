<?php
namespace Domain\Localization\Models;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use Domain\Localization\Services\LanguageService;

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
        'id',
    ];

    protected $keyType = 'string';

    protected $primaryKey = 'id';

    public $incrementing = false;

    public function languageTranslations()
    {
        return $this->hasMany(LanguageTranslation::class, 'lang', 'locale');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($language) {
            $language->id = Str::uuid();

            resolve(LanguageService::class)
                ->create_default_language_translations(
                    get_setting('license') ?? 'extended',
                    $language->locale
                );
        });

        static::updating(function ($language) {
            cache()->forget("language-translations-$language->locale");
        });

        static::deleting(function ($language) {
            DB::table('language_translations')
                ->whereLang($language->locale)
                ->delete();

            cache()->forget("language-translations-$language->locale");
        });
    }
}
