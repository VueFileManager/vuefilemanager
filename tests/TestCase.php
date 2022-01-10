<?php
namespace Tests;

use DB;
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

        $this->storeDefaultSettings();

        //$this->withoutExceptionHandling();
    }

    public function storeDefaultSettings()
    {
        DB::table('settings')->insert([
            [
                'name'  => 'storage_limitation',
                'value' => 1,
            ],
            [
                'name'  => 'license',
                'value' => 'extended',
            ],
            [
                'name'  => 'language',
                'value' => 'en',
            ],
            [
                'name'  => 'user_verification',
                'value' => 0,
            ],
            [
                'name'  => 'registration',
                'value' => 1,
            ],
            [
                'name'  => 'subscription_type',
                'value' => null,
            ],
        ]);
    }
}
