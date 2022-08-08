<?php
namespace Domain\Localization\Models;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Domain\Localization\Actions\SeedDefaultLanguageTranslationsAction;

/**
 * @method static whereLocale(string $param)
 * @method static create(string[] $array)
 * @property string id
 * @property string name
 * @property string locale
 * @property string created_at
 * @property string updated_at
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

    public function languageTranslations(): HasMany
    {
        return $this->hasMany(LanguageTranslation::class, 'lang', 'locale');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($language) {
            $language->id = Str::uuid();

            resolve(SeedDefaultLanguageTranslationsAction::class)(
                locale: $language->locale
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
