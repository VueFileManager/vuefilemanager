<?php
namespace Domain\Admin\Controllers\Users;

use App\Users\Models\User;
use Illuminate\Http\Response;
use App\Users\Models\UserSettings;
use App\Http\Controllers\Controller;
use App\Users\Resources\UserResource;
use App\Users\Resources\UsersCollection;
use Domain\Admin\Requests\CreateUserByAdmin;

class UserController extends Controller
{
    /**
     * Get all users
     */
    public function index(): UsersCollection
    {
        $users = User::sortable(['created_at', 'DESC'])
            ->paginate(20);

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
        // Create user
        $user = User::forceCreate([
            'password'          => bcrypt($request->input('password')),
            'role'              => $request->input('role'),
            'email'             => $request->input('email'),
            'email_verified_at' => now(),
        ]);

        UserSettings::unguard();

        $user
            ->settings()
            ->create([
                'storage_capacity' => $request->input('storage_capacity'),
                'avatar'           => store_avatar($request, 'avatar'),
                'name'             => $request->input('name'),
            ]);

        UserSettings::reguard();

        return response(new UserResource($user), 201);
    }
}
