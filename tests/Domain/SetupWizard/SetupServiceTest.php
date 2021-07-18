<?php
namespace Tests\Domain\SetupWizard;

use Storage;
use Tests\TestCase;
use Domain\Settings\Models\Setting;
use Domain\Localization\Models\Language;
use Domain\SetupWizard\Services\SetupService;

class SetupServiceTest extends TestCase
{
    public function __construct()
    {
        parent::__construct();
        $this->setup = app()->make(SetupService::class);
    }

    /**
     * @test
     */
    public function it_create_system_folders()
    {
        // folders are created in TestCase

        collect(['avatars', 'chunks', 'system', 'files', 'temp', 'zip'])
            ->each(function ($directory) {
                Storage::disk('local')->assertExists($directory);
            });
    }

    /**
     * @test
     */
    public function it_seed_default_language()
    {
        Setting::create([
            'name'  => 'license',
            'value' => 'Extended',
        ]);

        Language::create([
            'name'   => 'English',
            'locale' => 'en',
        ]);

        $this->assertDatabaseHas('languages', [
            'name'   => 'English',
            'locale' => 'en',
        ]);

        $this->assertDatabaseHas('language_translations', [
            'key'   => 'actions.close',
            'value' => 'Close',
            'lang'  => 'en',
        ]);
    }
}
