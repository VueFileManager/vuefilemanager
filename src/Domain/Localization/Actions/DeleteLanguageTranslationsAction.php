<?php
namespace Domain\Localization\Actions;

use DB;

class DeleteLanguageTranslationsAction
{
    public function __invoke(array $list): void
    {
        DB::table('language_translations')
            ->whereIn('key', $list)
            ->delete();
    }
}
