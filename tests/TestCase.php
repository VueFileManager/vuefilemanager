<?php
namespace Tests;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Domain\SetupWizard\Actions\CreateDiskDirectoriesAction;
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

        resolve(CreateDiskDirectoriesAction::class)();

        //$this->withoutExceptionHandling();
    }
}
