<?php
namespace Domain\Sharing\Controllers;

use Domain\Sharing\Models\Share;
use Domain\Folders\Models\Folder;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Domain\Sharing\Resources\ShareResource;
use Domain\Sharing\Actions\SendViaEmailAction;
use Domain\Sharing\Requests\CreateShareRequest;

class ShareItemController extends Controller
{
    public function __construct(
        public SendViaEmailAction $sendLinkToEmailAction,
    ) {
    }

    /**
     * Generate file share link
     */
    public function __invoke(
        CreateShareRequest $request,
    ): JsonResponse {
        $item = get_item($request->input('type'), $request->input('id'));

        // Check if item is currently shared
        if ($item->shared()->exists()) {
            return response()->json([
                'type'    => 'error',
                'message' => 'The item is currently shared.',
            ], 422);
        }

        // If sharing folder, check permission attribute
        if ($item instanceof Folder && $request->missing('permission')) {
            return response()->json([
                'type'    => 'error',
                'message' => 'The permission field for folder is required.',
            ], 422);
        }

        $this->authorize('owner', $item);

        $shared = Share::create([
            'password'     => $request->has('password') ? bcrypt($request->input('password')) : null,
            'type'         => $request->input('type') === 'folder' ? 'folder' : 'file',
            'is_protected' => $request->input('isPassword') ?? false,
            'permission'   => $request->input('permission') ?? null,
            'expire_in'    => $request->input('expiration') ?? null,
            'item_id'      => $item->id,
            'user_id'      => auth()->id(),
        ]);

        // Send shared link via email
        if ($request->has('emails')) {
            ($this->sendLinkToEmailAction)->onQueue()->execute(
                emails: $request->input('emails'),
                token: $shared->token,
                user: $shared->user,
            );
        }

        // Return created shared record
        return response()->json(new ShareResource($shared), 201);
    }
}
