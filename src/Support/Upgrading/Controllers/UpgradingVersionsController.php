<?php
namespace Support\Upgrading\Controllers;

use DB;
use Artisan;
use Storage;
use App\Users\Models\User;
use Illuminate\Support\Arr;
use Domain\Files\Models\File;
use Domain\Folders\Models\Folder;
use Domain\Maintenance\Actions\UpgradeDatabaseAction;
use VueFileManager\Subscription\Domain\Plans\Models\Plan;
use Domain\Localization\Actions\DeleteLanguageTranslationsAction;
use Domain\Localization\Actions\UpdateLanguageTranslationsAction;

class UpgradingVersionsController
{
    public function __construct(
        public UpgradeDatabaseAction $upgradeDatabase,
        public DeleteLanguageTranslationsAction $deleteLanguageStrings,
        public UpdateLanguageTranslationsAction $updateLanguageStrings,
    ) {}
}
