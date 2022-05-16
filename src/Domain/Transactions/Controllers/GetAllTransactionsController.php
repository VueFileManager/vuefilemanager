<?php
namespace Domain\Transactions\Controllers;

use App\Users\Models\User;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Domain\Transactions\Resources\TransactionCollection;
use VueFileManager\Subscription\Domain\Transactions\Models\Transaction;

class GetAllTransactionsController extends Controller
{
    public function __invoke(User $user): JsonResponse
    {
        $transactions = Transaction::with('user')
            ->sortable(['created_at' => 'desc'])
            ->paginate(20);

        return response()->json(new TransactionCollection($transactions));
    }
}
