<?php
namespace Domain\Admin\Controllers\Users;

use App\Users\Models\User;
use App\Users\DTO\CreateUserData;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Users\Resources\UserResource;
use App\Users\Resources\UsersCollection;
use App\Users\Actions\CreateNewUserAction;
use Domain\Admin\Requests\CreateUserByAdmin;
use VueFileManager\Subscription\Domain\Plans\Exceptions\MeteredBillingPlanDoesntExist;

class UserController extends Controller
{
    public function __construct(
        protected CreateNewUserAction $createNewUser,
    ) {
    }

    /**
     * Get all users
     */
    public function index(): UsersCollection
    {
        $users = User::sortable(['created_at', 'DESC'])
            ->paginate(15);

        return new UsersCollection($users);
    }

    /**
     * Get user details
     */
    public function show(User $user): JsonResponse
    {
        return response()->json(new UserResource($user));
    }

    /**
     * Create new user by admin
     */
    public function store(CreateUserByAdmin $request): JsonResponse
    {
        // Map user data
        $data = CreateUserData::fromArray([
            'role'     => $request->input('role'),
            'name'     => $request->input('name'),
            'email'    => $request->input('email'),
            'password' => $request->input('password'),
            'avatar'   => store_avatar($request, 'avatar'),
        ]);

        // Register user
        try {
            $user = ($this->createNewUser)($data);
        } catch (MeteredBillingPlanDoesntExist $e) {
            return response()->json([
                'type'    => 'error',
                'message' => 'User registrations are temporarily disabled',
            ], 409);
        }

        // Update user data
        $user->role = $data->role;
        $user->email_verified_at = now();

        $user->save();

        return response()->json(new UserResource($user), 201);
    }
}
