<?php


namespace App\Services\Oasis;


use App\Models\Oasis\Client;
use App\Models\Oasis\Invoice;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Factories\Sequence;

class OasisDevService
{
    /**
     * @return Application|Factory|View
     */
    public function get_invoice_view()
    {
        return view('oasis.invoices.invoice')
            ->with('invoice', Invoice::first())
            ->with('user', User::whereEmail('howdy@hi5ve.digital')->first());
    }
}