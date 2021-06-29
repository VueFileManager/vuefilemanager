<?php

namespace Tests\Feature\Admin;

use App\Models\Language;
use App\Models\Setting;
use App\Models\User;
use App\Services\SetupService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LanguageEditorTest extends TestCase
{
    use DatabaseMigrations;

    protected $setup;

    public function __construct()
    {
        parent::__construct();
        $this->setup = app()->make(SetupService::class);
    }

    /**
     * @test
     */
    public function it_create_language()
    {
        Setting::create([
            'name'  => 'license',
            'value' => 'Extended',
        ]);

        $admin = User::factory(User::class)
            ->create(['role' => 'admin']);

        Sanctum::actingAs($admin);

        $this->postJson('/api/admin/languages', [
            'name'   => 'Slovenčina',
            'locale' => 'sk',
        ])
            ->assertStatus(201)
            ->assertJsonFragment([
                'name'   => 'Slovenčina',
                'locale' => 'sk',
            ]);

        $this->assertDatabaseHas('languages', [
            'name'   => 'Slovenčina',
            'locale' => 'sk',
        ]);

        $this->assertDatabaseHas('language_translations', [
            'key'   => 'actions.close',
            'value' => 'Close',
            'lang'  => 'sk',
        ]);
    }

    /**
     * @test
     */
    public function it_update_language()
    {
        $this->setup->seed_default_language();

        $admin = User::factory(User::class)
            ->create(['role' => 'admin']);

        Sanctum::actingAs($admin);

        $language = Language::first();

        $this->patchJson("/api/admin/languages/$language->id", [
            'name'  => 'name',
            'value' => 'Slovenčina',
        ])
            ->assertStatus(201)
            ->assertJsonFragment([
                'name' => 'Slovenčina',
            ]);

        $this->assertDatabaseHas('languages', [
            'name'   => 'Slovenčina',
            'locale' => 'en',
        ]);
    }

    /**
     * @test
     */
    public function it_delete_language()
    {
        $language = Language::create([
            'name'   => 'Slovenčina',
            'locale' => 'sk'
        ]);

        $admin = User::factory(User::class)
            ->create(['role' => 'admin']);

        Sanctum::actingAs($admin);

        $this->deleteJson("/api/admin/languages/$language->id")
            ->assertStatus(204);

        $this->assertDatabaseMissing('languages', [
            'name'   => 'Slovenčina',
            'locale' => 'sk',
        ]);

        $this->assertDatabaseMissing('language_translations', [
            'key'   => 'actions.close',
            'value' => 'Close',
            'lang'  => 'sk',
        ]);
    }

    /**
     * @test
     */
    public function it_try_to_delete_default_language()
    {
        $this->setup->seed_default_language();

        $admin = User::factory(User::class)
            ->create(['role' => 'admin']);

        Sanctum::actingAs($admin);

        $language = Language::first();

        $this->deleteJson("/api/admin/languages/$language->id")
            ->assertStatus(401);
    }

    /**
     * @test
     */
    public function it_get_all_languages()
    {
        $this->setup->seed_default_language();

        $admin = User::factory(User::class)
            ->create(['role' => 'admin']);

        Sanctum::actingAs($admin);

        $this->getJson('/api/admin/languages')
            ->assertStatus(200)
            ->assertJsonFragment([
                "locale"        => "en",
                "actions.close" => "Close",
            ]);
    }

    /**
     * @test
     */
    public function it_update_language_string()
    {
        $this->setup->seed_default_language();

        $admin = User::factory(User::class)
            ->create(['role' => 'admin']);

        Sanctum::actingAs($admin);

        $language = Language::first();

        $this->patchJson("/api/admin/languages/$language->id/strings", [
            'name'  => 'actions.close',
            'value' => 'Close It, now!',
        ]);

        $this->assertDatabaseHas('language_translations', [
            'key'   => 'actions.close',
            'value' => 'Close It, now!',
            'lang'  => 'en',
        ]);
    }

    /**
     * @test
     */
    public function it_get_language_with_strings_by_selected_language_id()
    {
        $this->setup->seed_default_language();

        $admin = User::factory(User::class)
            ->create(['role' => 'admin']);

        Sanctum::actingAs($admin);

        $language = Language::first();

        $this->getJson("/api/admin/languages/$language->id")
            ->assertStatus(200)
            ->assertJsonFragment([
                "actions.close" => "Close",
                "locale"        => "en",
            ]);
    }

    /**
     * @test
     */
    public function it_get_language_translations_for_frontend()
    {
        $this->setup->seed_default_language();

        $this->getJson("/translations/en")
            ->assertStatus(200)
            ->assertJsonFragment([
                "actions.close" => "Close",
            ]);
    }

    /**
     * @test
     */
    public function it_get_custom_translations_from_file_config()
    {
        $this->setup->seed_default_language();

        $this->assertDatabaseHas('language_translations', [
            'key'   => 'custom',
            'value' => 'translation',
            'lang'  => 'en',
        ]);
    }

    /**
     * @test
     */
    public function it_get_translated_string_from_t_helper_function()
    {
        $this->setup->seed_default_language();

        Language::first()
            ->languageTranslations()
            ->forceCreate([
                "key"   => "test",
                "value" => "Hi, my name is :name :surname",
                "lang"  => "en",
            ]);

        $this->assertEquals(
            'Close',
            __t('actions.close')
        );

        $this->assertEquals(
            '🙋 John share some files with you. Look at it!',
            __t('shared_link_email_subject', ['user' => 'John'])
        );

        $this->assertEquals(
            'Hi, my name is John Doe',
            __t('test', ['name' => 'John', 'surname' => 'Doe'])
        );
    }


    /**
     * @test
     */
    public function it_get_translated_string_from_t_helper_without_database_connection()
    {
        $this->assertEquals(
            __t('actions.close'),
            'Close'
        );
    }
}
