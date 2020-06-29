<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>{{ config('vuefilemanager.app_name') }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;300;400;600;700;900&display=swap"
          rel="stylesheet">
    <link href="{{ env('APP_ENV') !== 'local' ? asset('css/invoice.css') : mix('css/invoice.css') }}?v={{ get_version() }}" rel="stylesheet">
</head>
<body>
<div id="toolbar-wrapper">
    <button class="button" onclick="window.print();">
        <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
             stroke="currentColor"
             stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer">
            <polyline points="6 9 6 2 18 2 18 9"></polyline>
            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
            <rect x="6" y="14" width="12" height="8"></rect>
        </svg>
        <span class="label">Print Document</span>
    </button>
</div>
<div id="invoice-wrapper">
    <header class="invoice-header">
        <div class="logo">
            <img src="/assets/images/vuefilemanager-horizontal-logo.svg" alt="VueFileManager">
        </div>
        <div class="title">
            <h1>Invoice</h1>
        </div>
    </header>
    <section class="invoice-subject">
        <ul class="list">
            <li class="list-item">
                <b>Date:</b>
                <span>{{ format_date($invoice->date()) }}</span>
            </li>
            <li class="list-item">
                <b>Product:</b>
                <span>Subscription</span>
            </li>
            <li class="list-item">
                <b>Invoice Number:</b>
                <span>{{ $invoice->number }}</span>
            </li>
        </ul>
    </section>
    <div class="invoice-partners">
        <div class="partner">
            <h2 class="partner-title">Seller:</h2>
            <ul class="list">
                @isset($settings->billing_vat_number)
                    <li class="list-item">
                        <b>VAT number:</b>
                        <span>{{ $settings->billing_vat_number }}</span>
                    </li>
                @endisset

                @isset($settings->billing_name)
                    <li class="list-item">
                        <b>Name:</b>
                        <span>{{ $settings->billing_name }}</span>
                    </li>
                @endisset

                @isset($settings->billing_phone_number)
                    <li class="list-item">
                        <b>Phone:</b>
                        <span>{{ $settings->billing_phone_number }}</span>
                    </li>
                @endisset
            </ul>
            <ul class="list">
                @isset($settings->billing_address)
                    <li class="list-item">
                        <b>Address:</b>
                        <span>{{ $settings->billing_address }}</span>
                    </li>
                @endisset

                @isset($settings->billing_city)
                    <li class="list-item">
                        <b>City:</b>
                        <span>{{ $settings->billing_city }}</span>
                    </li>
                @endisset

                @isset($settings->billing_state)
                    <li class="list-item">
                        <b>State:</b>
                        <span>{{ $settings->billing_state }}</span>
                    </li>
                @endisset

                @isset($settings->billing_postal_code)
                    <li class="list-item">
                        <b>Postal code:</b>
                        <span>{{ $settings->billing_postal_code }}</span>
                    </li>
                @endisset

                @isset($settings->billing_country)
                    <li class="list-item">
                        <b>Country:</b>
                        <span>{{ $settings->billing_country }}</span>
                    </li>
                @endisset
            </ul>
        </div>
        <div class="partner">
            <h2 class="partner-title">Client:</h2>
            <ul class="list">

                @isset($invoice->customer_name)
                    <li class="list-item">
                        <b>Name:</b>
                        <span>{{ $invoice->customer_name }}</span>
                    </li>
                @endisset

                @isset($invoice->customer_phone)
                    <li class="list-item">
                        <b>Phone:</b>
                        <span>{{ $invoice->customer_phone }}</span>
                    </li>
                @endisset
            </ul>
            <ul class="list">
                @isset($invoice->customer_address['line1'])
                    <li class="list-item">
                        <b>Address:</b>
                        <span>{{ $invoice->customer_address['line1'] }}</span>
                    </li>
                @endisset

                @isset($invoice->customer_address['city'])
                    <li class="list-item">
                        <b>City:</b>
                        <span>{{ $invoice->customer_address['city'] }}</span>
                    </li>
                @endisset

                @isset($invoice->customer_address['state'])
                    <li class="list-item">
                        <b>State:</b>
                        <span>{{ $invoice->customer_address['state'] }}</span>
                    </li>
                @endisset

                @isset($invoice->customer_address['postal_code'])
                    <li class="list-item">
                        <b>Postal code:</b>
                        <span>{{ $invoice->customer_address['postal_code'] }}</span>
                    </li>
                @endisset

                @isset($invoice->customer_address['country'])
                    <li class="list-item">
                        <b>Country:</b>
                        <span>{{ $invoice->customer_address['country'] }}</span>
                    </li>
                @endisset
            </ul>
        </div>
    </div>
    <div class="invoice-order">
        <table class="table">
            <thead class="table-header">
            <tr>
                <td>Description</td>
                <td>Date</td>
                <td>Amount</td>
            </tr>
            </thead>
            <tbody class="table-body">
                <tr>
                    <td>{{ $invoice->subscriptions()[0]->description }}</td>
                    <td>{{ $invoice->subscriptions()[0]->type }}</td>
                    <td>{{ \Laravel\Cashier\Cashier::formatAmount($invoice->subscriptions()[0]->amount) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="invoice-summary">
        <b>Total {{ $invoice->total() }}</b>
    </div>
</div>
</body>
</html>
