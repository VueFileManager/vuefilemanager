<?php
namespace Tests;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use Domain\SetupWizard\Services\SetupService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow('1. January 2021');

        Notification::fake();

        Storage::fake('local');

        resolve(SetupService::class)->create_directories();

        $this->withoutExceptionHandling();
    }
}
