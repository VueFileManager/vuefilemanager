<?php
namespace Domain\Transactions\Controllers;

use App\Users\Models\User;
use App\Http\Controllers\Controller;
use Domain\Transactions\Resources\TransactionCollection;

class GetUserTransactionsController extends Controller
{
    public function __invoke(User $user): TransactionCollection
    {
        $transactions = $user
            ->transactions()
            ->sortable(['created_at' => 'desc'])
            ->paginate(20);

        return new TransactionCollection($transactions);
    }
}
