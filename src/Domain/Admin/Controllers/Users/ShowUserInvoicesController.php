<?php

namespace Domain\Admin\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Users\Models\User;
use Domain\Invoices\Resources\InvoiceCollection;
use Domain\Subscriptions\Services\StripeService;

class ShowUserInvoicesController extends Controller
{
    public function __construct(
        private StripeService $stripe
    ) {}

    /**
     * Get user storage details
     */
    public function __invoke(User $user): InvoiceCollection
    {
        $invoices = $this->stripe->getUserInvoices($user);

        return new InvoiceCollection($invoices);
    }
}
