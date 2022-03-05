<?php
namespace App\Users\Controllers\Account;

use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Users\Requests\UpdateUserProfileSettingsRequest;

class UpdateProfileSettingsController extends Controller
{
    /**
     * Update user settings
     */
    public function __invoke(UpdateUserProfileSettingsRequest $request): Response
    {
        // Check if is demo
        abort_if(is_demo_account(), 204, 'Done.');

        // Get user
        $user = Auth::user();

        // Update avatar
        if ($request->hasFile('avatar')) {
            $user
                ->settings()
                ->update([
                    'avatar' => store_avatar($request, 'avatar'),
                ]);

            return response('Saved!', 204);
        }

        $user
            ->settings()
            ->update(
                make_single_input($request)
            );

        return response('Saved!', 204);
    }
}
