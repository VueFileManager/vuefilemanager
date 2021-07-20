<?php


namespace Domain\Invoices\Controllers;


use App\Http\Controllers\Controller;
use Domain\Invoices\Resources\InvoiceCollection;
use Illuminate\Support\Facades\Auth;

class UserInvoicesController extends Controller
{
    /**
     * Get user invoices
     */
    public function __invoke(): InvoiceCollection
    {
        return new InvoiceCollection(
            Auth::user()->invoices()
        );
    }
}