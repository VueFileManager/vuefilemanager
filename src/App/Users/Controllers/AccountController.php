<?php
namespace App\Users\Controllers;

use App\Users\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Users\Resources\UserResource;
use Illuminate\Http\RedirectResponse;
use Support\Demo\Actions\DemoService;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Validator;
use App\Users\Resources\UserStorageResource;
use Domain\Invoices\Resources\InvoiceCollection;
use App\Users\Requests\UpdateUserPasswordRequest;
use Illuminate\Contracts\Routing\ResponseFactory;
use App\Users\Requests\UserCreateAccessTokenRequest;

class AccountController extends Controller
{
    public function __construct(
        public DemoService $demo,
    ) {
    }

    /**
     * Get all user data to frontend
     *
     * @return UserResource
     */
    public function user()
    {
        return new UserResource(
            Auth::user()
        );
    }

    /**
     * Get storage details
     *
     * @return UserStorageResource
     */
    public function storage()
    {
        return new UserStorageResource(
            Auth::user()
        );
    }

    /**
     * Get user invoices
     *
     * @return InvoiceCollection
     */
    public function invoices()
    {
        return new InvoiceCollection(
            Auth::user()->invoices()
        );
    }

    /**
     * Update user settings relationship
     *
     * @param Request $request
     * @return ResponseFactory|\Illuminate\Http\Response
     */
    public function update_user_settings(Request $request)
    {
        // Validate request
        // TODO: pridat validator do requestu
        $validator = Validator::make($request->all(), [
            'avatar' => 'sometimes|file',
            'name'   => 'string',
            'value'  => 'string',
        ]);

        // Return error
        if ($validator->fails()) {
            abort(400, 'Bad input');
        }

        // Get user
        $user = Auth::user();

        // Check if is demo
        abort_if(is_demo_account('howdy@hi5ve.digital'), 204, 'Done.');

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

    /**
     * Change user password
     *
     * @param Request $request
     * @return ResponseFactory|\Illuminate\Http\Response
     */
    public function change_password(UpdateUserPasswordRequest $request)
    {
        // Get user
        $user = Auth::user();

        // Check if is demo
        abort_if(is_demo_account('howdy@hi5ve.digital'), 204, 'Done.');

        // Change and store new password
        $user->password = bcrypt($request->input('password'));
        $user->save();

        return response('Changed!', 204);
    }

    /**
     * Get all user tokens
     */
    public function tokens(): Response
    {
        return response(
            Auth::user()->tokens()->get(),
            200
        );
    }

    public function create_token(UserCreateAccessTokenRequest $request): Response
    {
        // Check if is demo
        abort_if(is_demo_account('howdy@hi5ve.digital'), 201, [
            'name'           => 'token',
            'token'          => Str::random(40),
            'abilities'      => '["*"]',
            'tokenable_id'   => Str::uuid(),
            'updated_at'     => now(),
            'created_at'     => now(),
            'id'             => Str::random(40),
        ]);

        $token = Auth::user()->createToken($request->input('name'));

        return response($token, 201);
    }

    public function revoke_token(PersonalAccessToken $token): Response
    {
        // Check if is demo
        abort_if(is_demo_account('howdy@hi5ve.digital'), 204, 'Deleted!');

        if (Auth::id() !== $token->tokenable_id) {
            return response('Unauthorized', 401);
        }

        $token->delete();

        return response('Deleted!', 204);
    }

    public function email_verification(string $id, Request $request): RedirectResponse | Response
    {
        if (! $request->hasValidSignature()) {
            return response('Invalid or expired url provided.', 401);
        }

        $user = User::find($id);

        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        return redirect()->to('/successfully-verified');
    }

    public function resend_verification_email(Request $request): Response
    {
        $user = User::whereEmail($request->input('email'))->first();

        if ($user->hasVerifiedEmail()) {
            return response('Email was already verified.', 204);
        }

        $user->sendEmailVerificationNotification();

        return response('Email verification link sent to your email', 204);
    }
}
