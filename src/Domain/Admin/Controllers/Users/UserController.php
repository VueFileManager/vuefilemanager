<?php
namespace Domain\Admin\Controllers\Users;

use App\Users\Models\User;
use Illuminate\Http\Response;
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

        // Split username
        $name = split_name($request->input('name'));

        $user
            ->settings()
            ->create([
                'avatar'     => store_avatar($request, 'avatar'),
                'first_name' => $name['first_name'],
                'last_name'  => $name['last_name'],
            ]);

        return response(new UserResource($user), 201);
    }
}
