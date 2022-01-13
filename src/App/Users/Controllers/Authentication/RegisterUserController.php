<?php
namespace App\Users\Controllers\Authentication;

use App\Users\DTO\CreateUserData;
use App\Http\Controllers\Controller;
use App\Users\Actions\CreateNewUserAction;
use App\Users\Requests\RegisterUserRequest;

class RegisterUserController extends Controller
{
    public function __construct(
        public CreateNewUserAction $createNewUser,
    ) {
    }
    
    public function __invoke(RegisterUserRequest $request)
    {
        // Check if account registration is enabled
        if (! intval(get_settings('registration'))) {
            return response([
                'type'    => 'error',
                'message' => 'User registration is not allowed',
            ], 401);
        }

        // Map registration data
        $data = CreateUserData::fromRequest($request);

        // Register user
        ($this->createNewUser)($data);

        return response('User successfully registered.', 201);
    }
}
