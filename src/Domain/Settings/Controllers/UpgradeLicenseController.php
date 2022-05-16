<?php
namespace Domain\Settings\Controllers;

use DB;
use Artisan;
use Illuminate\Http\JsonResponse;
use Domain\Settings\Models\Setting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Domain\Localization\Models\Language;
use Domain\Settings\Requests\UpgradeLicenseRequest;

class UpgradeLicenseController extends Controller
{
    public function __invoke(
        UpgradeLicenseRequest $request
    ): JsonResponse {
        // Verify purchase code
        $response = Http::get("https://verify.vuefilemanager.com/api/verify-code/{$request->input('purchaseCode')}");

        if ($response->successful() && $response->body() === 'b6896a44017217c36f4a6fdc56699728') {
            // Store default settings for extended version
            collect([
                [
                    'name'  => 'license',
                    'value' => 'extended',
                ],
                [
                    'name'  => 'purchase_code',
                    'value' => $request->input('purchaseCode'),
                ],
                [
                    'name'  => 'section_pricing_content',
                    'value' => 1,
                ],
                [
                    'name'  => 'paypal_payment_description',
                    'value' => 'Available PayPal Credit, Debit or Credit Card.',
                ],
                [
                    'name'  => 'paystack_payment_description',
                    'value' => 'Available Bank Account, USSD, Mobile Money, Apple Pay.',
                ],
                [
                    'name'  => 'stripe_payment_description',
                    'value' => 'Available credit card or Apple Pay.',
                ],
                [
                    'name'  => 'allowed_registration_bonus',
                    'value' => 0,
                ],
                [
                    'name'  => 'registration_bonus_amount',
                    'value' => 0,
                ],
                [
                    'name'  => 'pricing_title',
                    'value' => 'Pick the <span class="text-theme">Best Plan</span> For Your Needs',
                ],
                [
                    'name'  => 'pricing_description',
                    'value' => 'Your private cloud storage software build on Laravel & Vue.js. No limits & no monthly fees. Truly freedom.',
                ],
            ])->each(function ($col) {
                Setting::updateOrCreate([
                    'name' => $col['name'],
                ], [
                    'value' => $col['value'],
                ]);
            });

            // Seed translations for extended version
            Language::all()
                ->each(function ($lang) {
                    $translations = collect(
                        config('language-translations.extended')
                    )
                        ->map(fn ($value, $key) => [
                            'lang'  => $lang->locale,
                            'value' => $value,
                            'key'   => $key,
                        ])->toArray();

                    $chunks = array_chunk($translations, 100);

                    foreach ($chunks as $chunk) {
                        DB::table('language_translations')
                            ->insert($chunk);
                    }
                });

            // Clear config and cache
            Artisan::call('config:clear');
            Artisan::call('cache:clear');

            return response()->json([
                'status'  => 'success',
                'message' => 'Your license was successfully upgraded',
            ], 201);
        }

        return response()->json([
            'status'  => 'error',
            'message' => 'Purchase code is invalid or is not Extended License.',
        ], 400);
    }
}
