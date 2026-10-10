<?php
namespace VueFileManager\Subscription\Domain\Plans\Actions;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use VueFileManager\Subscription\Domain\Plans\Models\Plan;
use VueFileManager\Subscription\Domain\Plans\Resources\PlanResource;

class UpdatePlanFixedItemAction
{
    public function __invoke(
        Request $request,
        Plan $plan,
    ): JsonResponse {
        // Get validation rules
        $rules = $plan
            ->fixedFeatures()
            ->pluck('key')
            ->map(fn ($key) => [$key => 'sometimes|string_or_integer'])
            ->collapse()
            ->toArray();

        // Validate data
        $validator = Validator::make($request->all(), $rules);

        // Return errors
        if ($validator->stopOnFirstFailure()->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Update data
        foreach ($request->all() as $key => $value) {
            $plan
                ->fixedFeatures()
                ->where('key', $key)
                ->update([
                    'key'   => $key,
                    'value' => $value,
                ]);
        }

        return response()->json(new PlanResource($plan));
    }
}
