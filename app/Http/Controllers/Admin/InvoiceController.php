<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\InvoiceCollection;
use App\Http\Resources\InvoiceResource;
use App\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Get all invoices
     *
     * @return InvoiceCollection
     */
    public function index()
    {
        return new InvoiceCollection(
            Invoice::all()
        );
    }

    /**
     * Get single invoice by $token
     * @param $token
     * @return InvoiceResource
     */
    public function show($token)
    {
        $invoice = Invoice::where('token', $token)->firstOrFail();

        return view('vuefilemanager.invoice')
            ->with('invoice', $invoice);
    }
}
