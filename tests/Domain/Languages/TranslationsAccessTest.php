<?php
namespace Tests\Domain\Languages;

use Tests\TestCase;
use Domain\Localization\Models\Language;
use Domain\Localization\Actions\SeedDefaultLanguageAction;

class TranslationsAccessTest extends TestCase
{
    /**
     * @test
     */
    public function it_get_language_translations_for_frontend()
    {
        resolve(SeedDefaultLanguageAction::class)();

        $this->getJson('/translations/en')
            ->assertStatus(200)
            ->assertJsonFragment([
                'close' => 'Close',
            ]);
    }

    /**
     * @test
     */
    public function it_get_custom_translations_from_file_config()
    {
        resolve(SeedDefaultLanguageAction::class)();

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
        resolve(SeedDefaultLanguageAction::class)();

        Language::first()
            ->languageTranslations()
            ->forceCreate([
                'key'   => 'test',
                'value' => 'Hi, my name is :name :surname',
                'lang'  => 'en',
            ]);

        $this->assertEquals(
            'Close',
            __t('close')
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
            __t('close'),
            'Close'
        );
    }
}
