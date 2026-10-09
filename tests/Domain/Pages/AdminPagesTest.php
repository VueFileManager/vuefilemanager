<?php
namespace Tests\Domain\Pages;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use App\Users\Models\User;
use Laravel\Sanctum\Sanctum;
use Domain\Pages\Actions\SeedDefaultPagesAction;

class AdminPagesTest extends TestCase
{
    #[Test]
    public function it_get_all_pages()
    {
        resolve(SeedDefaultPagesAction::class)();

        $admin = User::factory()
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

    #[Test]
    public function it_get_page()
    {
        resolve(SeedDefaultPagesAction::class)();

        $admin = User::factory()
            ->create(['role' => 'admin']);

        $this
            ->actingAs($admin)
            ->getJson('/api/admin/pages/terms-of-service')
            ->assertStatus(200)
            ->assertJsonFragment([
                'slug' => 'terms-of-service',
            ]);
    }

    #[Test]
    public function it_update_page()
    {
        resolve(SeedDefaultPagesAction::class)();

        $admin = User::factory()
            ->create(['role' => 'admin']);

        $this
            ->actingAs($admin)
            ->patchJson('/api/admin/pages/terms-of-service', [
                'name'  => 'title',
                'value' => 'New Title',
            ])->assertStatus(200);

        $this->assertDatabaseHas('pages', [
            'title' => 'New Title',
        ]);
    }
}
