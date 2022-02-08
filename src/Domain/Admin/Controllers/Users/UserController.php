<?php
namespace Domain\Admin\Controllers\Users;

use App\Users\Models\User;
use Illuminate\Http\Response;
use App\Users\DTO\CreateUserData;
use App\Http\Controllers\Controller;
use App\Users\Resources\UserResource;
use App\Users\Resources\UsersCollection;
use App\Users\Actions\CreateNewUserAction;
use Domain\Admin\Requests\CreateUserByAdmin;

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
    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }

    /**
     * Create new user by admin
     */
    public function store(CreateUserByAdmin $request): Response
    {
        // Map user data
        $data = CreateUserData::fromArray([
            'name'     => $request->input('name'),
            'email'    => $request->input('email'),
            'password' => $request->input('password'),
            'avatar'   => store_avatar($request, 'avatar'),
        ]);

        // Register user
        $user = ($this->createNewUser)($data);

        // Update user data
        $user->email_verified_at = now();
        $user->role = $request->input('role');

        $user->save();

        return response(new UserResource($user), 201);
    }
}
