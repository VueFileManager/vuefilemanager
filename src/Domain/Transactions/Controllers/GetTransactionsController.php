<?php
namespace Domain\Transactions\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Domain\Transactions\Resources\TransactionCollection;

class GetTransactionsController extends Controller
{
    public function __invoke(): TransactionCollection
    {
        $transactions = Auth::user()
            ->transactions()
            ->sortable(['created_at' => 'desc'])
            ->paginate(15);

        return new TransactionCollection($transactions);
    }
}
