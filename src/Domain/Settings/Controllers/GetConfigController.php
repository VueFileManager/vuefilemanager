<?php
namespace Domain\Settings\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Domain\Settings\Actions\GetConfigAction;

class GetConfigController extends Controller
{
    public function __construct(
        public GetConfigAction $getConfigAction,
    ) {
    }

    public function __invoke(): JsonResponse
    {
        return response()->json(($this->getConfigAction)());
    }
}
