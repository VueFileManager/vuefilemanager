<?php
namespace Domain\SetupWizard\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class VerifyPurchaseCodeController extends Controller
{
    /**
     * Verify Envato purchase code
     */
    public function __invoke(Request $request): Response
    {
        // Verify purchase code
        $response = Http::get("https://verify.vuefilemanager.com/api/verify-code/{$request->input('purchaseCode')}");

        if ($response->successful()) {
            return response($response->body(), 201);
        }

        return response('Purchase code is invalid.', 400);
    }
}
