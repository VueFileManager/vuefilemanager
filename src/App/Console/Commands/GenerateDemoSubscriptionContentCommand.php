<?php
namespace App\Console\Commands;

use DB;
use App\Users\Models\User;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Domain\Settings\Models\Setting;
use VueFileManager\Subscription\Domain\Plans\Models\Plan;
use VueFileManager\Subscription\Domain\CreditCards\Models\CreditCard;
use VueFileManager\Subscription\Domain\Plans\DTO\CreateFixedPlanData;
use VueFileManager\Subscription\Domain\Plans\DTO\CreateMeteredPlanData;
use VueFileManager\Subscription\Domain\Subscriptions\Models\Subscription;
use VueFileManager\Subscription\Domain\Plans\Actions\StoreFixedPlanAction;
use VueFileManager\Subscription\Domain\Plans\Actions\StoreMeteredPlanAction;

class GenerateDemoSubscriptionContentCommand extends Command
{
    public $signature = 'subscription:demo {type=fixed}';

    public $description = 'Generate demo content for subscription module';

    public function __construct(
        private StoreFixedPlanAction $storeFixedPlan,
        private StoreMeteredPlanAction $storeMeteredPlan,
    ) {
        parent::__construct();
    }

    public function handle()
    {
        // Truncate all subscription relate tables
        collect([
            'balances', 'billing_alerts', 'credit_cards', 'customers', 'failed_payments', 'metered_tiers', 'plan_drivers', 'plan_fixed_features', 'plan_metered_features', 'plans', 'subscription_drivers', 'subscriptions', 'usages', 'transactions',
        ])->each(fn ($name) => DB::table($name)->truncate());

        // Create plans and subscriptions for metered billing
        if ($this->argument('type') === 'metered') {
            $this->info('Setting up new metered plans demo data...');
            $this->generateMeteredPlans();

            $this->info('Setting up new pre-paid subscriptions data...');
            $this->generateMeteredSubscription();
        }

        // Create plans and subscriptions for fixed billing
        if ($this->argument('type') === 'fixed') {
            // TODO: check for credentials

            $this->info('Setting up new fixed plans demo data...');
            $this->generateFixedPlans();

            $this->info('Setting up new fixed subscriptions data...');
            $this->generateFixedSubscription();
        }

        // Set subscription type for the app
        Setting::updateOrCreate([
            'name'  => 'subscription_type',
        ], [
            'value' => $this->argument('type'),
        ]);
    }

    public function generateMeteredSubscription(): void
    {
        $plan = Plan::where('name', 'Pay as You Go')
            ->first();

        User::all()
            ->each(function ($user) use ($plan) {
                $isHowdy = $user->email === 'howdy@hi5ve.digital';

                $this->info("Storing {$plan->name} for {$user->email}...");

                // 1. Create subscription
                $subscription = Subscription::create([
                    'user_id'    => $user->id,
                    'type'       => 'pre-paid',
                    'plan_id'    => $plan->id,
                    'name'       => $plan->name,
                    'status'     => 'active',
                    'renews_at'  => now()->addDays(16),
                    'created_at' => now()->subDays(14),
                    'updated_at' => now()->subDays(14),
                ]);

                // 2. Log fake storage and bandwidth
                foreach (range(1, 31) as $item) {
                    $this->info('Logging fake bandwidth usage...');

                    $bandwidthFeature = $plan
                        ->meteredFeatures()
                        ->where('key', 'bandwidth')
                        ->first();

                    $subscription->usages()->create([
                        'metered_feature_id' => $bandwidthFeature->id,
                        'quantity'           => random_int(111, 999) / 1000,
                        'created_at'         => now()->subDays($item),
                    ]);

                    $this->info('Logging fake storage usage...');

                    $storageFeature = $plan
                        ->meteredFeatures()
                        ->where('key', 'storage')
                        ->first();

                    $subscription->usages()->create([
                        'metered_feature_id' => $storageFeature->id,
                        'quantity'           => random_int(1111, 3999) / 1000,
                        'created_at'         => now()->subDays($item),
                    ]);
                }

                // 3. Store flat fee
                $flatFeeFeature = $plan
                    ->meteredFeatures()
                    ->where('key', 'flatFee')
                    ->first();

                $subscription->usages()->create([
                    'metered_feature_id' => $flatFeeFeature->id,
                    'quantity'           => 1,
                    'created_at'         => now()->subDays(2),
                ]);

                // 4. Store member count
                $memberFeature = $plan
                    ->meteredFeatures()
                    ->where('key', 'member')
                    ->first();

                $subscription->usages()->create([
                    'metered_feature_id' => $memberFeature->id,
                    'quantity'           => 7,
                    'created_at'         => now()->subDays(2),
                ]);

                // 5. Store fake transactions
                $this->info("Storing transactions for {$user->email}...");

                collect([
                    [
                        'type'       => 'withdrawal',
                        'created_at' => now()->subDays(2),
                        'amount'     => $isHowdy ? 12.59 : random_int(1, 20),
                        'note'       => now()->subDays(32)->format('d. M') . ' - ' . now()->subDays(2)->format('d. M'),
                        'driver'     => 'system',
                    ],
                    [
                        'type'       => 'credit',
                        'created_at' => now()->subDays(26 * 1),
                        'note'       => __('Bonus'),
                        'amount'     => $isHowdy ? 12.00 : random_int(1, 20),
                        'driver'     => 'system',
                    ],
                    [
                        'type'       => 'withdrawal',
                        'created_at' => now()->subDays(26 * 1),
                        'note'       => now()->subDays(30 + 26 * 1)->format('d. M') . ' - ' . now()->subDays(26 * 1)->format('d. M'),
                        'amount'     => $isHowdy ? 2.38 : random_int(1, 20),
                        'driver'     => 'system',
                    ],
                    [
                        'type'       => 'withdrawal',
                        'created_at' => now()->subDays(26 * 2),
                        'note'       => now()->subDays(30 + 26 * 2)->format('d. M') . ' - ' . now()->subDays(26 * 2)->format('d. M'),
                        'amount'     => $isHowdy ? 5.12 : random_int(1, 20),
                        'driver'     => 'system',
                    ],
                    [
                        'type'       => 'withdrawal',
                        'created_at' => now()->subDays(26 * 3),
                        'note'       => now()->subDays(30 + 26 * 3)->format('d. M') . ' - ' . now()->subDays(26 * 3)->format('d. M'),
                        'amount'     => $isHowdy ? 3.89 : random_int(1, 20),
                        'driver'     => 'system',
                    ],
                    [
                        'type'       => 'withdrawal',
                        'created_at' => now()->subDays(26 * 4),
                        'note'       => now()->subDays(30 + 26 * 4)->format('d. M') . ' - ' . now()->subDays(26 * 4)->format('d. M'),
                        'amount'     => $isHowdy ? 7.42 : random_int(1, 20),
                        'driver'     => 'system',
                    ],
                    [
                        'type'       => 'charge',
                        'created_at' => now()->subDays(26 * 5),
                        'note'       => 'Account Fund',
                        'amount'     => $isHowdy ? 50.00 : random_int(1, 20),
                        'driver'     => 'paypal',
                    ],
                ])->each(
                    function ($transaction) use ($user, $plan) {
                        $bandwidthUsage = random_int(1000, 12000) / 1000;
                        $storageUsage = random_int(300, 4900) / 1000;
                        $memberUsage = random_int(3, 20);

                        $user->transactions()->create([
                            'type'       => $transaction['type'],
                            'status'     => 'completed',
                            'note'       => $transaction['note'],
                            'currency'   => $plan->currency,
                            'driver'     => $transaction['driver'],
                            'amount'     => $transaction['amount'],
                            'created_at' => $transaction['created_at'],
                            'reference'  => Str::random(12),
                            'metadata'   => $transaction['type'] === 'withdrawal'
                                ? [
                                    [
                                        'feature' => 'bandwidth',
                                        'amount'  => 0.29 * $bandwidthUsage,
                                        'usage'   => $bandwidthUsage,
                                    ],
                                    [
                                        'feature' => 'storage',
                                        'amount'  => 0.19 * $storageUsage,
                                        'usage'   => $storageUsage,
                                    ],
                                    [
                                        'feature' => 'flatFee',
                                        'amount'  => 2.49,
                                        'usage'   => 1,
                                    ],
                                    [
                                        'feature' => 'member',
                                        'amount'  => 0.10 * $memberUsage,
                                        'usage'   => $memberUsage,
                                    ],
                                ]
                                : null,
                        ]);
                    }
                );

                // Make fake credit card
                $creditCard = CreditCard::factory()
                    ->make();

                // 6. Store credit card
                if (! $isHowdy) {
                    $user->creditCards()->create([
                        'brand'      => $creditCard->brand,
                        'last4'      => $creditCard->last4,
                        'service'    => $creditCard->service,
                        'reference'  => $creditCard->reference,
                        'expiration' => $creditCard->expiration,
                    ]);
                }

                // 7. Add default user balance
                $user->balance()->create([
                    'currency' => 'USD',
                    'amount'   => $isHowdy ? 30.60 : random_int(20, 60),
                ]);

                // 8. Create billing alert
                $user->billingAlert()->create([
                    'amount'   => $isHowdy ? 25 : random_int(30, 80),
                ]);
            });
    }

    public function generateFixedSubscription(): void
    {
        $howdy = User::where('email', 'howdy@hi5ve.digital')
            ->first();

        $alice = User::where('email', 'alice@hi5ve.digital')
            ->first();

        $johan = User::where('email', 'johan@hi5ve.digital')
            ->first();

        $professionalPackPlan = Plan::where('name', 'Professional Pack')
            ->where('interval', 'month')
            ->first();

        $businessPackPlan = Plan::where('name', 'Business Pack')
            ->where('interval', 'month')
            ->first();

        $this->info("Storing {$professionalPackPlan->name} for {$howdy->email}...");

        $howdySubscription = $howdy->subscription()->create([
            'plan_id'    => $professionalPackPlan->id,
            'name'       => $professionalPackPlan->name,
            'status'     => 'active',
            'created_at' => now()->subDays(14),
            'updated_at' => now()->subDays(14),
        ]);

        $this->info("Storing {$businessPackPlan->name} for {$alice->email}...");

        $aliceSubscription = $alice->subscription()->create([
            'plan_id'    => $businessPackPlan->id,
            'name'       => $businessPackPlan->name,
            'status'     => 'active',
            'created_at' => now()->subDays(9),
            'updated_at' => now()->subDays(9),
        ]);

        $this->info("Storing {$professionalPackPlan->name} for {$johan->email}...");

        $johanSubscription = $johan->subscription()->create([
            'plan_id'    => $professionalPackPlan->id,
            'name'       => $professionalPackPlan->name,
            'status'     => 'cancelled',
            'ends_at'    => now()->addDays(18),
            'created_at' => now()->subDays(8),
            'updated_at' => now()->subDays(8),
        ]);

        $this->info("Storing transactions for {$howdy->email}...");

        collect([
            ['created_at' => now()->subDays(2)],
            ['created_at' => now()->subDays(26)],
            ['created_at' => now()->subDays(26 * 2)],
            ['created_at' => now()->subDays(26 * 3)],
            ['created_at' => now()->subDays(26 * 4)],
            ['created_at' => now()->subDays(26 * 5)],
        ])->each(
            fn ($transaction) => $howdy->transactions()->create([
                'status'     => 'completed',
                'note'       => $professionalPackPlan->name,
                'currency'   => $professionalPackPlan->currency,
                'amount'     => $professionalPackPlan->amount,
                'driver'     => 'paypal',
                'created_at' => $transaction['created_at'],
                'reference'  => Str::random(12),
            ])
        );

        $this->info("Storing transactions for {$johan->email}...");

        collect([
            ['created_at' => now()->subDay()],
            ['created_at' => now()->subDays(29)],
            ['created_at' => now()->subDays(29 * 2)],
            ['created_at' => now()->subDays(29 * 3)],
        ])->each(
            fn ($transaction) => $johan->transactions()->create([
                'status'     => 'completed',
                'note'       => $professionalPackPlan->name,
                'currency'   => $professionalPackPlan->currency,
                'amount'     => $professionalPackPlan->amount,
                'driver'     => 'stripe',
                'created_at' => $transaction['created_at'],
                'reference'  => Str::random(12),
            ])
        );

        $this->info("Storing transactions for {$alice->email}...");

        collect([
            ['created_at' => now()],
            ['created_at' => now()->subDays(28)],
            ['created_at' => now()->subDays(28 * 2)],
            ['created_at' => now()->subDays(28 * 3)],
            ['created_at' => now()->subDays(28 * 4)],
        ])->each(
            fn ($transaction) => $alice->transactions()->create([
                'status'     => 'completed',
                'note'       => $businessPackPlan->name,
                'currency'   => $businessPackPlan->currency,
                'amount'     => $businessPackPlan->amount,
                'driver'     => 'paystack',
                'created_at' => $transaction['created_at'],
                'reference'  => Str::random(12),
            ])
        );

        $howdySubscription->driver()->create([
            'driver'                 => 'stripe',
            'driver_subscription_id' => Str::random(),
        ]);

        // Make fake credit card
        $creditCard = CreditCard::factory()
            ->make();

        // 6. Store credit card
        $howdy->creditCards()->create([
            'brand'      => $creditCard->brand,
            'last4'      => $creditCard->last4,
            'service'    => $creditCard->service,
            'reference'  => $creditCard->reference,
            'expiration' => $creditCard->expiration,
        ]);

        $aliceSubscription->driver()->create([
            'driver'                 => 'paystack',
            'driver_subscription_id' => Str::random(),
        ]);

        $johanSubscription->driver()->create([
            'driver'                 => 'stripe',
            'driver_subscription_id' => Str::random(),
        ]);
    }

    public function generateFixedPlans()
    {
        // Define plans
        $plans = [
            [
                'type'        => 'fixed',
                'name'        => 'Professional Pack',
                'description' => 'Best for all professionals',
                'currency'    => 'USD',
                'features'    => [
                    'max_storage_amount' => 200,
                    'max_team_members'   => 20,
                ],
                'intervals'   => [
                    [
                        'interval' => 'month',
                        'amount'   => 9.99,
                    ],
                    [
                        'interval' => 'year',
                        'amount'   => 99.49,
                    ],
                ],
            ],
            [
                'type'        => 'fixed',
                'name'        => 'Business Pack',
                'description' => 'Best for business needs',
                'currency'    => 'USD',
                'features'    => [
                    'max_storage_amount' => 500,
                    'max_team_members'   => 50,
                ],
                'intervals'   => [
                    [
                        'interval' => 'month',
                        'amount'   => 29.99,
                    ],
                    [
                        'interval' => 'year',
                        'amount'   => 189.99,
                    ],
                ],
            ],
            [
                'type'        => 'fixed',
                'name'        => 'Elite Pack',
                'description' => 'Best for all your needs',
                'currency'    => 'USD',
                'features'    => [
                    'max_storage_amount' => 2000,
                    'max_team_members'   => -1,
                ],
                'intervals'   => [
                    [
                        'interval' => 'month',
                        'amount'   => 59.99,
                    ],
                    [
                        'interval' => 'year',
                        'amount'   => 349.99,
                    ],
                ],
            ],
        ];

        // Create plans
        foreach ($plans as $plan) {
            foreach ($plan['intervals'] as $interval) {
                $data = CreateFixedPlanData::fromArray([
                    'type'        => $plan['type'],
                    'name'        => $plan['name'],
                    'description' => $plan['description'],
                    'features'    => $plan['features'],
                    'currency'    => $plan['currency'],
                    'amount'      => $interval['amount'],
                    'interval'    => $interval['interval'],
                ]);

                $this->info("Creating plan with name: {$plan['name']} and interval: {$interval['interval']}");

                // Store plans to the database and gateway
                ($this->storeFixedPlan)($data);
            }
        }
    }

    public function generateMeteredPlans()
    {
        // Define plans
        $plans = [
            [
                'type'        => 'metered',
                'name'        => 'Pay as You Go',
                'description' => 'Best for all professionals',
                'currency'    => 'USD',
                'meters'      => [
                    [
                        'key'                => 'bandwidth',
                        'aggregate_strategy' => 'sum_of_usage',
                        'tiers'              => [
                            [
                                'first_unit' => 1,
                                'last_unit'  => null,
                                'per_unit'   => 0.29,
                                'flat_fee'   => null,
                            ],
                        ],
                    ],
                    [
                        'key'                => 'storage',
                        'aggregate_strategy' => 'maximum_usage',
                        'tiers'              => [
                            [
                                'first_unit' => 1,
                                'last_unit'  => null,
                                'per_unit'   => 0.19,
                                'flat_fee'   => null,
                            ],
                        ],
                    ],
                    [
                        'key'                => 'flatFee',
                        'aggregate_strategy' => 'maximum_usage',
                        'tiers'              => [
                            [
                                'first_unit' => 1,
                                'last_unit'  => null,
                                'per_unit'   => 2.49,
                                'flat_fee'   => null,
                            ],
                        ],
                    ],
                    [
                        'key'                => 'member',
                        'aggregate_strategy' => 'maximum_usage',
                        'tiers'              => [
                            [
                                'first_unit' => 1,
                                'last_unit'  => null,
                                'per_unit'   => 0.10,
                                'flat_fee'   => null,
                            ],
                        ],
                    ],
                ],
            ],
        ];

        // Create plans
        foreach ($plans as $plan) {
            $data = CreateMeteredPlanData::fromArray([
                'type'        => $plan['type'],
                'name'        => $plan['name'],
                'meters'      => $plan['meters'],
                'currency'    => $plan['currency'],
                'description' => $plan['description'],
            ]);

            $this->info("Creating plan with name: {$plan['name']}");

            // Store plans to the database and gateway
            ($this->storeMeteredPlan)($data);
        }
    }
}
