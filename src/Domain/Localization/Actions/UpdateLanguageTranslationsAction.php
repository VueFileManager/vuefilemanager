<?php
namespace Domain\Localization\Actions;

use DB;
use Artisan;

class UpdateLanguageTranslationsAction
{
    public function __invoke(array $list): void
    {
        collect($list)
            ->each(
                fn (...$item) => DB::table('language_translations')
                    ->where('lang', 'en')
                    ->where('key', $item[1])
                    ->update(['value' => $item[0]])
            );

        Artisan::call('cache:clear');
    }
}
