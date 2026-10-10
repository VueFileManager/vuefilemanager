<?php
namespace Tests\Helpers;

use Stripe\WebhookSignature;

trait StripeTestHelpers
{
    /**
     * Generate Stripe signature for test purpose
     */
    private function generateTestSignature(array $payload): string
    {
        $timestamp = \time();
        $scheme = WebhookSignature::EXPECTED_SCHEME;

        $signedPayload = $timestamp . '.' . json_encode($payload);
        $signature = \hash_hmac('sha256', $signedPayload, config('subscription.credentials.stripe.webhook_key'));

        return "t={$timestamp},{$scheme}={$signature}";
    }
}
