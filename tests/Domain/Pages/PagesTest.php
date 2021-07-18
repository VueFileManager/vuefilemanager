<?php


namespace Tests\Domain\Pages;


use Domain\SetupWizard\Services\SetupService;
use Tests\TestCase;

class PagesTest extends TestCase
{
    /**
     * @test
     */
    public function it_get_legal_page()
    {
        resolve(SetupService::class)->seed_default_pages();

        $this->getJson('/api/page/terms-of-service')
            ->assertStatus(200)
            ->assertJsonFragment([
                'title' => 'Terms of Service',
            ]);
    }
}