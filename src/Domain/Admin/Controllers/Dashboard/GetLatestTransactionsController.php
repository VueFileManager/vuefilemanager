<?php
namespace Domain\Admin\Controllers\Dashboard;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use VueFileManager\Subscription\Domain\Transactions\Models\Transaction;
use VueFileManager\Subscription\Domain\Transactions\Resources\TransactionCollection;

class GetLatestTransactionsController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $transactions = Transaction::sortable([
            'created_at' => 'desc',
        ])
            ->take(5)
            ->get();

        return response()->json(new TransactionCollection($transactions));
    }
}
