<?php
namespace Domain\Admin\Controllers\Dashboard;

use App\Users\Models\User;
use App\Http\Controllers\Controller;
use App\Users\Resources\UsersCollection;

class GetNewbiesController extends Controller
{
    public function __invoke(): UsersCollection
    {
        $users = User::sortable([
            'created_at' => 'desc',
        ])
            ->take(5)
            ->get();

        return new UsersCollection($users);
    }
}
