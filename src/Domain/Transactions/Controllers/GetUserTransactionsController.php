<?php
namespace Domain\Transactions\Controllers;

use App\Users\Models\User;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Domain\Transactions\Resources\TransactionCollection;

class GetUserTransactionsController extends Controller
{
    public function __invoke(User $user): JsonResponse
    {
        $transactions = $user
            ->transactions()
            ->sortable(['created_at' => 'desc'])
            ->paginate(20);

        return response()->json(new TransactionCollection($transactions));
    }
}
