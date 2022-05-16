<?php
namespace Tests\Domain\Languages;

use Tests\TestCase;
use App\Users\Models\User;
use Domain\Localization\Models\Language;
use Domain\Localization\Actions\SeedDefaultLanguageAction;

class AdminLanguageTranslatorTest extends TestCase
{
    /**
     * @test
     */
    public function it_create_language()
    {
        $admin = User::factory()
            ->create(['role' => 'admin']);

        $this
            ->actingAs($admin)
            ->postJson('/api/admin/languages', [
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
            'key'   => 'close',
            'value' => 'Close',
            'lang'  => 'sk',
        ]);
    }

    /**
     * @test
     */
    public function it_update_language()
    {
        resolve(SeedDefaultLanguageAction::class)();

        $admin = User::factory()
            ->create(['role' => 'admin']);

        $language = Language::first();

        $this
            ->actingAs($admin)
            ->patchJson("/api/admin/languages/$language->id", [
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
            'locale' => 'sk',
        ]);

        $admin = User::factory()
            ->create(['role' => 'admin']);

        $this
            ->actingAs($admin)
            ->deleteJson("/api/admin/languages/$language->id")
            ->assertStatus(200);

        $this->assertDatabaseMissing('languages', [
            'name'   => 'Slovenčina',
            'locale' => 'sk',
        ]);

        $this->assertDatabaseMissing('language_translations', [
            'key'   => 'close',
            'value' => 'Close',
            'lang'  => 'sk',
        ]);
    }

    /**
     * @test
     */
    public function it_try_to_delete_default_language()
    {
        resolve(SeedDefaultLanguageAction::class)();

        $admin = User::factory()
            ->create(['role' => 'admin']);

        $language = Language::first();

        $this
            ->actingAs($admin)
            ->deleteJson("/api/admin/languages/$language->id")
            ->assertStatus(422);
    }

    /**
     * @test
     */
    public function it_get_all_languages()
    {
        resolve(SeedDefaultLanguageAction::class)();

        $admin = User::factory()
            ->create(['role' => 'admin']);

        $this
            ->actingAs($admin)
            ->getJson('/api/admin/languages')
            ->assertStatus(200)
            ->assertJsonFragment([
                'locale'        => 'en',
                'close'         => 'Close',
            ]);
    }

    /**
     * @test
     */
    public function it_update_language_string()
    {
        resolve(SeedDefaultLanguageAction::class)();

        $admin = User::factory()
            ->create(['role' => 'admin']);

        $language = Language::first();

        $this
            ->actingAs($admin)
            ->patchJson("/api/admin/languages/$language->id/strings", [
                'name'  => 'close',
                'value' => 'Close It, now!',
            ]);

        $this->assertDatabaseHas('language_translations', [
            'key'   => 'close',
            'value' => 'Close It, now!',
            'lang'  => 'en',
        ]);
    }

    /**
     * @test
     */
    public function it_get_language_with_strings_by_selected_language_id()
    {
        resolve(SeedDefaultLanguageAction::class)();

        $admin = User::factory()
            ->create(['role' => 'admin']);

        $language = Language::first();

        $this
            ->actingAs($admin)
            ->getJson("/api/admin/languages/$language->id")
            ->assertStatus(200)
            ->assertJsonFragment([
                'close'         => 'Close',
                'locale'        => 'en',
            ]);
    }
}
