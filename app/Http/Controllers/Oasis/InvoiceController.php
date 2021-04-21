<?php

namespace App\Http\Controllers\Oasis;

use App\Http\Controllers\Controller;
use App\Http\Resources\Oasis\OasisInvoiceCollection;
use Auth;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * @return mixed
     */
    public function get_all_regular_invoices()
    {
        return response(new OasisInvoiceCollection(Auth::user()->regularInvoices), 200);
    }

    /**
     * @return mixed
     */
    public function get_all_advance_invoices()
    {
        return response(new OasisInvoiceCollection(Auth::user()->advanceInvoices), 200);
    }
}
