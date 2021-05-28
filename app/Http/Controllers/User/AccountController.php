<?php
namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\DemoService;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\InvoiceCollection;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\UserStorageResource;
use Illuminate\Contracts\Routing\ResponseFactory;
use App\Http\Requests\User\UpdateUserPasswordRequest;
use App\Http\Requests\User\UserCreateAccessTokenRequest;
use Laravel\Sanctum\PersonalAccessToken;

class AccountController extends Controller
{
    /**
     * AccountController constructor.
     */
    public function __construct()
    {
        $this->demo = resolve(DemoService::class);
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
            'name' => 'string',
            'value' => 'string',
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
     * 
     * @return Collection
     */
    public function tokens_index()
    {
        return response(
            Auth::user()->tokens()->get(),
            200
        );
    }

    /**
     * Create token
     * 
     * @param Request $request
     * @return Collection
     */
    public function create_token(UserCreateAccessTokenRequest $request)
    {
        return response(
            Auth::user()->createToken($request->input('name')),
            201
        );
    }

     /**
     * Revoke token
     * 
     * @param $id
     * @return  ResponseFactory|\Illuminate\Http\Response
     */
    public function revoke_token(PersonalAccessToken $token)
    {
        if(Auth::user()->id !== $token->tokenable_id) {
            return response('Unauthorized', 401);
        }

        $token->delete();

        return response('Deleted!', 204);
    }

    /**
     * Email verification
     *
     * @param Request $request
     * @param User $user
     * @return ResponseFactory|\Illuminate\Http\Response
     */
    public function email_verify($id, Request $request)
    {
        $user = User::find($id);

        if (!$request->hasValidSignature()) {
            return response("Invalid/Expired url provided.", 401);
        }
        
        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }
    
        return redirect()->to('/successfully-verified');
    }

     /**
     * Resend verification email
     *
     * @return ResponseFactory|\Illuminate\Http\Response
     */
    public function resend_verify_email(Request $request) 
    {
        $user = User::whereEmail($request->input('email'))->first();

        if ($user->hasVerifiedEmail()) {
            return response("Email already verified.", 204);
        }
    
        $user->sendEmailVerificationNotification();
    
        return response("Email verification link sent on your email", 200);
    }
}
