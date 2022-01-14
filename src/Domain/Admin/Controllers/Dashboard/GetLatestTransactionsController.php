<?php
namespace Domain\Admin\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use VueFileManager\Subscription\Domain\Transactions\Models\Transaction;
use VueFileManager\Subscription\Domain\Transactions\Resources\TransactionCollection;

class GetLatestTransactionsController extends Controller
{
    public function __invoke(): TransactionCollection
    {
        $transactions = Transaction::sortable([
            'created_at' => 'desc',
        ])
            ->take(5)
            ->get();

        return new TransactionCollection($transactions);
    }
}
