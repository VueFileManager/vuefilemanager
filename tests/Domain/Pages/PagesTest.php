<?php
namespace Tests\Domain\Pages;

use Domain\Pages\Actions\SeedDefaultPagesAction;
use Tests\TestCase;

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
