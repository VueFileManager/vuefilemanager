<?php

namespace Tests\Feature\App;

use App\Services\SetupService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AppTest extends TestCase
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
    public function it_get_index_page()
    {
        $this->setup->seed_default_pages();

        $this->setup->seed_default_settings('Extended');

        $this->get('/')
            ->assertStatus(200);
    }
}
