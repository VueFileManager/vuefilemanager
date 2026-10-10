<?php
namespace VueFileManager\Subscription\Support\Engines;

use Illuminate\Http\Request;
use Illuminate\Http\Client\Response;
use VueFileManager\Subscription\Domain\Plans\Models\Plan;
use VueFileManager\Subscription\Domain\Plans\DTO\CreateFixedPlanData;
use VueFileManager\Subscription\Domain\Subscriptions\Models\Subscription;

interface Engine
{
    /**
     * Get subscription plan
     */
    public function getPlan(string $planId): array;

    /**
     * Create new fixed plan
     */
    public function createFixedPlan(CreateFixedPlanData $data): array;

    /**
     * Update subscription plan
     */
    public function updatePlan(Plan $plan): Response;

    /**
     * Delete subscription plan
     */
    public function deletePlan(string $planId): void;

    /**
     * Create new customer for service
     */
    public function createCustomer(array $user): null|Response;

    /**
     * Update customer for service
     */
    public function updateCustomer(array $user): null|Response;

    /**
     * Get Subscription details
     */
    public function getSubscription(string $subscriptionId): Response;

    /**
     * Get Subscription details
     */
    public function createSubscription(Plan $plan, $user = null): array;

    /**
     * Swap subscription plan
     */
    public function swapSubscription(Subscription $subscription, Plan $plan): Response;

    /**
     * Update subscription
     */
    public function updateSubscription(Subscription $subscription, ?Plan $plan = null): array;

    /**
     * Cancel Subscription
     */
    public function cancelSubscription(Subscription $subscription): Response;

    /**
     * Get webhook
     */
    public function webhook(Request $request);
}
