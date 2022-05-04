<?php
namespace Domain\SetupWizard\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class PingAPIController extends Controller
{
    public function __invoke(): JsonResponse
    {
        return response()->json([
            'type'    => 'success',
            'message' => 'pong',
        ]);
    }
}
