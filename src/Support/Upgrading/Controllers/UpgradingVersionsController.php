<?php
namespace Support\Upgrading\Controllers;

use Illuminate\Support\Facades\Artisan;
use Domain\Localization\Models\Language;
use Domain\Settings\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
    ) {
    }

    public function upgrade_to_2_2_4(): void
    {
        // Delete members from team folders where team folder doesn't exist
        collect(['team_folder_members', 'team_folder_invitations'])
            ->each(function ($table) {
                DB::table($table)
                    ->get()
                    ->groupBy('parent_id')
                    ->each(function ($item, $id) use ($table) {
                        if (! Folder::find($id)) {
                            DB::table($table)
                                ->where('parent_id', $id)
                                ->delete();
                        }
                    });
            });
    }

    public function upgrade_to_2_2_3(): void
    {
        ($this->upgradeDatabase)();

        // Apply only for regular licenses
        if (get_settings('license') === 'regular') {
            // Store default settings for extended version
            collect([
                [
                    'name'  => 'section_pricing_content',
                    'value' => 1,
                ],
                [
                    'name'  => 'paypal_payment_description',
                    'value' => 'Available PayPal Credit, Debit or Credit Card.',
                ],
                [
                    'name'  => 'paystack_payment_description',
                    'value' => 'Available Bank Account, USSD, Mobile Money, Apple Pay.',
                ],
                [
                    'name'  => 'stripe_payment_description',
                    'value' => 'Available credit card or Apple Pay.',
                ],
                [
                    'name'  => 'allowed_registration_bonus',
                    'value' => 0,
                ],
                [
                    'name'  => 'registration_bonus_amount',
                    'value' => 0,
                ],
                [
                    'name'  => 'pricing_title',
                    'value' => 'Pick the <span class="text-theme">Best Plan</span> For Your Needs',
                ],
                [
                    'name'  => 'pricing_description',
                    'value' => 'Your private cloud storage software build on Laravel & Vue.js. No limits & no monthly fees. Truly freedom.',
                ],
            ])->each(function ($col) {
                Setting::updateOrCreate([
                    'name' => $col['name'],
                ], [
                    'value' => $col['value'],
                ]);
            });

            // Seed translations for extended version
            Language::all()
                ->each(function ($lang) {
                    $translations = collect(
                        config('language-translations.extended')
                    )
                        ->map(fn ($value, $key) => [
                            'lang'  => $lang->locale,
                            'value' => $value,
                            'key'   => $key,
                        ])->toArray();

                    $chunks = array_chunk($translations, 100);

                    foreach ($chunks as $chunk) {
                        DB::table('language_translations')
                            ->insert($chunk);
                    }
                });

            // Clear config and cache
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
        }
    }

    public function upgrade_to_2_2_2(): void
    {
        ($this->upgradeDatabase)();

        ($this->updateLanguageStrings)([
            'preview_sorting.sort_alphabet' => 'Sort By Alphabet',
        ]);
    }

    public function upgrade_to_2_2_1(): void
    {
        ($this->upgradeDatabase)();
    }

    public function upgrade_to_2_2_0_13(): void
    {
        setEnvironmentValue([
            'SESSION_LIFETIME' => 15120,
        ]);

        Artisan::call('config:clear');
    }

    public function upgrade_to_2_2_0(): void
    {
        setEnvironmentValue([
            'DB_MYSQLDUMP_PATH' => '/usr/bin',
        ]);
    }

    public function upgrade_to_2_1_2(): void
    {
        ($this->updateLanguageStrings)([
            'allow_recaptcha' => 'Allow ReCaptcha v3',
        ]);
    }

    public function upgrade_to_2_1_1(): void
    {
        ($this->upgradeDatabase)();
    }

    public function upgrade_to_2_0_10(): void
    {
        ($this->upgradeDatabase)();

        // Upgrade team folder content ownership
        Folder::where('parent_id', null)
            ->where('team_folder', true)
            ->cursor()
            ->each(function ($teamFolder) {
                // Get all inherited folder from team folder
                $childrenFolderIds = Folder::with('folders:id,parent_id')
                    ->where('id', $teamFolder->id)
                    ->get('id');

                $teamFolderIds = Arr::flatten(filter_folders_ids($childrenFolderIds));

                // Replace user content ownership for author of team folder
                DB::table('files')
                    ->whereIn('parent_id', $teamFolderIds)
                    ->cursor()
                    ->each(function ($file) use ($teamFolder) {
                        // Move image thumbnails
                        if ($file->type === 'image') {
                            // Get image thumbnail list
                            $thumbnailList = getThumbnailFileList($file->basename);

                            // move thumbnails to the new location
                            $thumbnailList->each(function ($basename) use ($file, $teamFolder) {
                                $oldPath = "files/$file->user_id/$basename";
                                $newPath = "files/$teamFolder->user_id/$basename";

                                if (Storage::exists($oldPath)) {
                                    Storage::move($oldPath, $newPath);
                                }
                            });
                        }

                        // Get single file path
                        $filePath = "files/$file->user_id/$file->basename";

                        // Move single file
                        if (Storage::exists($filePath)) {
                            Storage::move($filePath, "files/$teamFolder->user_id/$file->basename");
                        }

                        // Update file permission
                        File::find($file->id)->update([
                            'user_id'     => $teamFolder->user_id,
                            'creator_id'  => $teamFolder->user_id !== $file->user_id ? $file->user_id : null,
                        ]);
                    });

                // Update folder ownership
                DB::table('folders')
                    ->whereIn('parent_id', $teamFolderIds)
                    ->update(['user_id' => $teamFolder->user_id]);
            });

        // Upgrade dwg files
        File::withTrashed()
            ->where('mimetype', 'vnd.dwg')
            ->cursor()
            ->each(fn ($file) => $file->update([
                'mimetype' => 'dwg',
                'type'     => 'file',
            ]));
    }

    public function upgrade_to_2_0_13(): void
    {
        // Force plan synchronization
        if (get_settings('license') === 'extended' && Plan::count() !== 0) {
            Artisan::call('subscription:synchronize-plans');
        }
    }

    public function upgrade_to_2_0_14(): void
    {
        ($this->upgradeDatabase)();

        User::whereNotNull('two_factor_secret')
            ->cursor()
            ->each(fn ($user) => $user->forceFill(['two_factor_confirmed_at' => now()])->save());

        ($this->deleteLanguageStrings)([
            'popup_2fa.disappear_qr',
        ]);

        ($this->updateLanguageStrings)([
            'require_email_verification'      => 'Require Verify Email Address',
            'require_email_verification_note' => 'Turn on, if you want to verify user email address after registration.',
        ]);

        Artisan::call('cache:clear');
    }

    public function upgrade_to_2_0_16(): void
    {
        ($this->updateLanguageStrings)([
            'write_feedback'                        => 'Help Us Improve',
            'change_password'                       => 'Security & API',
            'shared.empty_shared'                   => 'Nothing Shared Yet',
            'admin_settings.others.default_storage' => 'Default Storage Space for User Accounts (in GB)',
        ]);

        Artisan::call('cache:clear');
    }
}
