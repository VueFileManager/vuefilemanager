<?php
namespace Domain\Sharing\Controllers;

use Domain\Sharing\Models\Share;
use Domain\Folders\Models\Folder;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Domain\Sharing\Resources\ShareResource;
use Domain\Sharing\Requests\UpdateShareRequest;
use Domain\Sharing\Requests\RevokeSharesRequest;

class ShareController extends Controller
{
    /**
     * Get shared record
     */
    public function show(
        Share $share,
    ): JsonResponse {
        return response()->json(new ShareResource($share));
    }

    /**
     * Update sharing
     */
    public function update(
        UpdateShareRequest $request,
        Share $share,
    ): JsonResponse {
        $item = get_item($share->type, $share->item_id);

        // If sharing folder, check permission attribute
        if ($item instanceof Folder && $request->missing('permission')) {
            return response()->json([
                'type'    => 'error',
                'message' => 'The permission field for folder is required.',
            ], 422);
        }

        $share->update([
            'expire_in'    => $request->input('expiration') ?? null,
            'permission'   => $request->input('permission'),
            'is_protected' => $request->boolean('protected'),
            'password'     => $request->has('password')
                ? bcrypt($request->input('password'))
                : $share->password,
        ]);

        // Return shared record
        return response()->json(new ShareResource($share));
    }

    /**
     * Delete sharing item
     */
    public function destroy(
        RevokeSharesRequest $request
    ): JsonResponse {
        foreach ($request->input('tokens') as $token) {
            // Delete share record
            $record = Share::where('token', $token)
                ->where('user_id', Auth::id())
                ->first();

            $record?->delete();
        }

        return response()->json([
            'type'    => 'success',
            'message' => 'The share links was revoked successfully.',
        ]);
    }
}
