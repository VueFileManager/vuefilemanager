<?php
namespace VueFileManager\Subscription\Domain\Plans\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use VueFileManager\Subscription\Domain\Plans\Actions\SynchronizePlansAction;

class SynchronizePlansController extends Controller
{
    public function __construct(
        public SynchronizePlansAction $synchronizePlans,
    ) {
    }

    public function __invoke(): JsonResponse
    {
        // Run synchronization
        $errorMessages = ($this->synchronizePlans)();

        if (empty($errorMessages)) {
            return response()->json([
                'type'    => 'success',
                'message' => 'Plans was successfully synchronized',
            ]);
        }

        return response()->json([
            'type'    => 'error',
            'title'   => "Plans can't be synchronized",
            'message' => implode(', ', $errorMessages),
        ], 500);
    }
}
