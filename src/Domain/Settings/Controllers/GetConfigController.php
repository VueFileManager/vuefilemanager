<?php

namespace Domain\Settings\Controllers;

use Domain\Settings\Actions\GetConfigAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;


class GetConfigController extends Controller
{
    public function __construct(
        public GetConfigAction $getConfigAction,
    ) {}

    public function __invoke(): JsonResponse
    {
        return response()->json(($this->getConfigAction)());
    }
}