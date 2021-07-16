<?php

namespace Tests\Feature\Setup;

use App\Models\Language;
use App\Models\LanguageTranslation;
use App\Models\Setting;
use App\Services\SetupService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Storage;
use Tests\TestCase;

class SetupServiceTest extends TestCase
{
    use DatabaseMigrations;

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
        $this->setup->create_directories();

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
            'locale' => 'en'
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
