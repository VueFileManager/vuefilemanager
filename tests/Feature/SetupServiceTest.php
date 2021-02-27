<?php

namespace Tests\Feature;

use App\Services\SetupService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Storage;
use Tests\TestCase;

class SetupServiceTest extends TestCase
{

    public function __construct()
    {
        parent::__construct();
        $this->setup = app()->make(SetupService::class);
    }

    /**
     * @test
     */
    public function it_create_system_folders()
    {
        $this->setup->create_directories();

        collect(['avatars', 'chunks', 'system', 'files', 'temp', 'zip'])
            ->each(function ($directory) {
                Storage::disk('local')->assertExists($directory);
            });
    }
}
