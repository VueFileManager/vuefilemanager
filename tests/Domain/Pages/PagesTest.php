<?php
namespace Tests\Domain\Pages;

use Tests\TestCase;
use Domain\Pages\Actions\SeedDefaultPagesAction;

class PagesTest extends TestCase
{
    /**
     * @test
     */
    public function it_get_legal_page()
    {
        resolve(SeedDefaultPagesAction::class)();

        $this->getJson('/api/page/terms-of-service')
            ->assertStatus(200)
            ->assertJsonFragment([
                'title' => 'Terms of Service',
            ]);
    }
}
