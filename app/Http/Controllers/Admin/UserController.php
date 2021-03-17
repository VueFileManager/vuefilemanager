<?php

namespace App\Http\Controllers\Admin;

use App\Models\File;
use App\Models\Folder;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ChangeRoleRequest;
use App\Http\Requests\Admin\ChangeStorageCapacityRequest;
use App\Http\Requests\Admin\CreateUserByAdmin;
use App\Http\Requests\Admin\DeleteUserRequest;
use App\Http\Resources\InvoiceCollection;
use App\Http\Resources\UsersCollection;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserStorageResource;
use App\Http\Resources\UserSubscription;
use App\Services\StripeService;
use App\Models\Share;
use App\Models\User;
use App\Models\UserSettings;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Storage;

class UserController extends Controller
{
    public function __construct(StripeService $stripe)
    {
        $this->stripe = $stripe;
    }

    /**
     * Get user details
     *
     * @param User $user
     * @return UserResource
     */
    public function details(User $user)
    {
        return new UserResource(
            $user
        );
    }

    /**
     * Get user storage details
     *
     * @param User $user
     * @return UserStorageResource
     */
    public function storage(User $user)
    {
        return new UserStorageResource(
            $user
        );
    }

    /**
     * Get user storage details
     *
     * @param User $user
     * @return InvoiceCollection
     */
    public function invoices(User $user)
    {
        return new InvoiceCollection(
            $this
                ->stripe
                ->getUserInvoices($user)
        );
    }

    /**
     * Get user subscription details
     *
     * @param User $user
     * @return UserSubscription|Application|ResponseFactory|Response
     */
    public function subscription(User $user)
    {
        if (!$user->stripeId() || !$user->subscription('main')) {
            return response("User doesn't have any subscription.", 404);
        }

        return new UserSubscription(
            $user
        );
    }

    /**
     * Get all users
     *
     * @return UsersCollection
     */
    public function users()
    {
        return new UsersCollection(
            User::sortable(['created_at', 'DESC'])
                ->paginate(20)
        );
    }

    /**
     * Change user role
     *
     * @param ChangeRoleRequest $request
     * @param User $user
     * @return UserResource
     */
    public function change_role(ChangeRoleRequest $request, User $user)
    {
        // Demo preview
        if (is_demo_account('howdy@hi5ve.digial')) {
            return new UserResource($user);
        }

        // Update user role
        $user->role = $request->input('attributes.role');
        $user->save();

        return new UserResource(
            $user
        );
    }

    /**
     * Change user storage capacity
     *
     * @param ChangeStorageCapacityRequest $request
     * @param User $user
     * @return UserStorageResource
     */
    public function change_storage_capacity(ChangeStorageCapacityRequest $request, User $user)
    {
        $user
            ->settings()
            ->update(
                $request->input('attributes')
            );

        return new UserStorageResource(
            $user
        );
    }

    /**
     * Send user password reset link
     *
     * @param User $user
     * @return ResponseFactory|Response
     */
    public function reset_password(User $user)
    {
        // Demo preview
        if (is_demo()) {
            return response('Done!', 204);
        }

        // Get password token
        $token = Password::getRepository()
            ->create($user);

        // Send user email
        $user->sendPasswordResetNotification($token);

        return response('Done!', 204);
    }

    /**
     * Create new user by admin
     *
     * @param CreateUserByAdmin $request
     * @return UserResource|Application|ResponseFactory|Response
     */
    public function create_user(CreateUserByAdmin $request)
    {
        // Create user
        $user = User::forceCreate([
            'role'     => $request->role,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        UserSettings::unguard();

        $user
            ->settings()
            ->create([
                'name'             => $request->name,
                'avatar'           => store_avatar($request, 'avatar'),
                'storage_capacity' => $request->storage_capacity,
            ]);

        UserSettings::reguard();

        return response(new UserResource($user), 201);
    }

    /**
     * Delete user with all user data
     *
     * @param DeleteUserRequest $request
     * @param User $user
     * @return ResponseFactory|Response
     * @throws \Exception
     */
    public function delete_user(DeleteUserRequest $request, User $user)
    {
        if (is_demo()) {
            return response('Done!', 204);
        }

        if ($user->subscribed('main')) {
            abort(202, "You can\'t delete this account while user have active subscription.");
        }

        if ($user->id === Auth::id()) {
            abort(406, "You can\'t delete your account");
        }

        if ($user->settings->name !== $request->name) {
            abort(403, "The name you typed is wrong!");
        }

        $user->delete();

        return response('Done!', 204);
    }
}
