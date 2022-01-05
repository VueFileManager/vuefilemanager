<?php
namespace Tests\App\Restrictions;

use Tests\TestCase;
use Domain\Settings\Models\Setting;

class RestrictionsTest extends TestCase
{
    /**
     * @test
     */
    public function it_get_metered_driver()
    {
        Setting::updateOrCreate([
            'name' => 'subscription_type',
        ], [
            'value' => 'metered',
        ]);

        $this->assertEquals('metered', get_restriction_driver());
    }
    /**
     * @test
     */
    public function it_get_fixed_driver()
    {
        Setting::updateOrCreate([
            'name' => 'subscription_type',
        ], [
            'value' => 'fixed',
        ]);

        $this->assertEquals('fixed', get_restriction_driver());
    }
    /**
     * @test
     */
    public function it_get_default_driver()
    {
        $subscriptionType = Setting::where('name', 'subscription_type')
            ->first();

        $subscriptionType?->delete();

        $this->assertEquals('default', get_restriction_driver());
    }
}
