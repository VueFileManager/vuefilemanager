<?php
namespace VueFileManager\Subscription\Domain\Transactions\Controllers;

use Illuminate\Routing\Controller;
use VueFileManager\Subscription\Domain\Transactions\Resources\TransactionCollection;

class GetUserTransactionsController extends Controller
{
    public function __invoke($id): TransactionCollection
    {
        $transactions = config('auth.providers.users.model')::find($id)
            ->transactions()
            ->sortable(['created_at' => 'desc'])
            ->paginate(20);

        return new TransactionCollection($transactions);
    }
}
