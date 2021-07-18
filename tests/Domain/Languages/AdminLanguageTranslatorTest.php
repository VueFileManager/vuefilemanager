<?php


namespace Tests\Domain\Languages;


use Domain\Settings\Models\Language;
use Domain\Settings\Models\Setting;
use Domain\Settings\Models\User;
use Laravel\Sanctum\Sanctum;

class AdminLanguageTranslatorTest
{

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
            'locale' => 'sk'
        ]);

        $admin = User::factory(User::class)
            ->create(['role' => 'admin']);

        $this
            ->actingAs($admin)
            ->deleteJson("/api/admin/languages/$language->id")
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

        $language = Language::first();

        $this
            ->actingAs($admin)
            ->deleteJson("/api/admin/languages/$language->id")
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

        $this
            ->actingAs($admin)
            ->getJson('/api/admin/languages')
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

        $language = Language::first();

        $this
            ->actingAs($admin)
            ->patchJson("/api/admin/languages/$language->id/strings", [
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

        $language = Language::first();

        $this
            ->actingAs($admin)
            ->getJson("/api/admin/languages/$language->id")
            ->assertStatus(200)
            ->assertJsonFragment([
                "actions.close" => "Close",
                "locale"        => "en",
            ]);
    }
}