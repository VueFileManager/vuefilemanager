<?php
namespace Tests\Support\Scheduler;

use Storage;
use Tests\TestCase;
use App\Users\Models\User;
use Domain\Files\Models\File;
use Domain\Sharing\Models\Share;
use Illuminate\Http\UploadedFile;
use Domain\Traffic\Models\Traffic;
use Support\Scheduler\Actions\ReportUsageAction;
use Support\Scheduler\Actions\DeleteFailedFilesAction;
use VueFileManager\Subscription\Domain\Plans\Models\Plan;
use Support\Scheduler\Actions\DeleteUnverifiedUsersAction;
use Support\Scheduler\Actions\DeleteExpiredShareLinksAction;
use VueFileManager\Subscription\Domain\Plans\Models\PlanMeteredFeature;
use VueFileManager\Subscription\Domain\Subscriptions\Models\Subscription;

class SchedulerTest extends TestCase
{
    /**
     * @test
     */
    public function it_report_usage_of_subscription()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $plan = Plan::factory()
            ->create([
                'type' => 'metered',
            ]);

        PlanMeteredFeature::factory()
            ->count(4)
            ->sequence(
                ['key' => 'storage'],
                ['key' => 'bandwidth'],
                ['key' => 'flatFee'],
                ['key' => 'member'],
            )
            ->create([
                'plan_id' => $plan->id,
            ]);

        $subscription = Subscription::factory()
            ->create([
                'status'  => 'active',
                'type'    => 'pre-paid',
                'plan_id' => $plan->id,
                'user_id' => $user->id,
            ]);

        File::factory()
            ->create([
                'user_id'  => $user->id,
                'filesize' => 125000000,
            ]);

        Traffic::factory()
            ->create([
                'user_id'    => $user->id,
                'download'   => 155000000,
                'upload'     => 255000000,
                'created_at' => now()->subDay(),
            ]);

        resolve(ReportUsageAction::class)();

        $this
            ->assertDatabaseHas('usages', [
                'metered_feature_id' => $plan->meteredFeatures()->get()[0]->id,
                'subscription_id'    => $subscription->id,
                'quantity'           => 0.125,
            ])
            ->assertDatabaseHas('usages', [
                'metered_feature_id' => $plan->meteredFeatures()->get()[1]->id,
                'subscription_id'    => $subscription->id,
                'quantity'           => 0.410,
            ]);
    }

    /**
     * @test
     */
    public function it_delete_expired_shared_links()
    {
        $share = Share::factory()
            ->create([
                'expire_in'  => 24,
                'created_at' => now()->subDay(),
            ]);

        resolve(DeleteExpiredShareLinksAction::class)();

        $this->assertModelMissing($share);
    }

    /**
     * @test
     */
    public function it_delete_failed_files_older_than_one_day()
    {
        $this->travel(-26)->hours();

        $file = UploadedFile::fake()
            ->create('fake-file.zip', 2000, 'application/zip');

        collect(['chunks'])
            ->each(function ($folder) use ($file) {
                Storage::putFileAs($folder, $file, 'fake-file.zip');
            });

        resolve(DeleteFailedFilesAction::class)();

        collect(['chunks'])
            ->each(function ($folder) {
                Storage::disk('local')
                    ->assertMissing("$folder/fake-file.zip");
            });
    }

    /**
     * @test
     */
    public function it_delete_non_verified_users_after_30_days()
    {
        $expiredUser = User::factory()
            ->create([
                'email_verified_at' => null,
                'created_at'        => now()->subDays(31),
            ]);

        $nonExpiredUser = User::factory()
            ->create([
                'email_verified_at' => null,
                'created_at'        => now()->subDays(14),
            ]);

        $verifiedUser = User::factory()
            ->create([
                'email_verified_at' => now()->subDays(15),
                'created_at'        => now()->subDays(31),
            ]);

        resolve(DeleteUnverifiedUsersAction::class)();

        $this
            ->assertModelMissing($expiredUser)
            ->assertModelExists($nonExpiredUser)
            ->assertModelExists($verifiedUser);
    }
}
