<?php


namespace Tests\Domain\Plans;


use Illuminate\Support\Str;
use Tests\TestCase;

class SetupWizardPlansTest extends TestCase
{
    /**
     *
     */
    public function it_store_stripe_plans_via_setup_wizard()
    {
        $this->postJson('/api/setup/stripe-plans', [
            'plans' => [
                [
                    'type'       => 'plan',
                    'attributes' => [
                        'name'        => 'test-plan-' . Str::random(),
                        'price'       => (string)rand(1, 99),
                        'description' => 'Some random description',
                        'capacity'    => rand(1, 999),
                    ],
                ],
                [
                    'type'       => 'plan',
                    'attributes' => [
                        'name'        => 'test-plan-' . Str::random(),
                        'price'       => (string)rand(1, 99),
                        'description' => 'Some random description',
                        'capacity'    => rand(1, 999),
                    ],
                ],
            ]
        ])->assertStatus(204);
    }
}