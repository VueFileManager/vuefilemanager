<?php
namespace VueFileManager\Subscription\Domain\Transactions\Controllers;

use Illuminate\Routing\Controller;
use VueFileManager\Subscription\Domain\Transactions\Resources\TransactionCollection;

class GetTransactionsController extends Controller
{
    public function __invoke(): TransactionCollection
    {
        $transactions = auth()->user()
            ->transactions()
            ->sortable(['created_at' => 'desc'])
            ->paginate(20);

        return new TransactionCollection($transactions);
    }
}
