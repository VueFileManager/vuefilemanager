<?php


namespace Tests\Domain\Pages;


use App\Users\Models\User;
use Domain\SetupWizard\Services\SetupService;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AdminPagesTest extends TestCase
{
    /**
     * @test
     */
    public function it_get_all_pages()
    {
        resolve(SetupService::class)->seed_default_pages();

        $admin = User::factory(User::class)
            ->create(['role' => 'admin']);

        Sanctum::actingAs($admin);

        collect(['terms-of-service', 'privacy-policy', 'cookie-policy'])
            ->each(function ($slug) {
                $this->getJson('/api/admin/pages')
                    ->assertStatus(200)
                    ->assertJsonFragment([
                        'slug' => $slug,
                    ]);
            });
    }

    /**
     * @test
     */
    public function it_get_page()
    {
        resolve(SetupService::class)->seed_default_pages();

        $admin = User::factory(User::class)
            ->create(['role' => 'admin']);

        $this
            ->actingAs($admin)
            ->getJson('/api/admin/pages/terms-of-service')
            ->assertStatus(200)
            ->assertJsonFragment([
                'slug' => 'terms-of-service',
            ]);
    }

    /**
     * @test
     */
    public function it_update_page()
    {
        resolve(SetupService::class)->seed_default_pages();

        $admin = User::factory(User::class)
            ->create(['role' => 'admin']);

        $this
            ->actingAs($admin)
            ->patchJson('/api/admin/pages/terms-of-service', [
                'name'  => 'title',
                'value' => 'New Title',
            ])->assertStatus(204);

        $this->assertDatabaseHas('pages', [
            'title' => 'New Title',
        ]);
    }
}