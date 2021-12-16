<?php
namespace App\Users\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Users\Actions\CreateNewUserAction;
use App\Users\Requests\RegisterUserRequest;

class RegisterAuthenticationController extends Controller
{
    public function __construct(
        public CreateNewUserAction $createNewUser,
    ) {
    }
    
  public function __invoke(RegisterUserRequest $request)
  {
      ($this->createNewUser)($request);
  }
}   
