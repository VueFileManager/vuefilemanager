<?php
namespace Domain\Invoices\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Domain\Invoices\Resources\InvoiceCollection;

class UserProfileInvoiceController extends Controller
{
    /**
     * Get user invoices
     */
    public function __invoke(): InvoiceCollection
    {
        $user = Auth::user()->invoices();

        return new InvoiceCollection($user);
    }
}
