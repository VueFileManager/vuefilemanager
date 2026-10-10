<?php
namespace VueFileManager\Subscription\Support\Webhooks;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use VueFileManager\Subscription\Support\EngineManager;

class WebhooksController extends Controller
{
    public function __invoke(Request $request, $driver)
    {
        Log::info($request->all());

        resolve(EngineManager::class)
            ->driver($driver)
            ->webhook($request);
    }
}
