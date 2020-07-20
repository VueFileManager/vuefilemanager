<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>@lang('vuefilemanager.invoice_title')</title>

    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;300;400;600;700;900&display=swap"
          rel="stylesheet">
    <link href="{{ env('APP_ENV') !== 'local' ? asset('css/invoice.css') : mix('css/invoice.css') }}?v={{ get_version() }}"
          rel="stylesheet">

    <style>
        .table td {
            padding: 8px;
            line-height: 20px;
            text-align: left;
            vertical-align: top;
        }
    </style>
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
        <span class="label">@lang('vuefilemanager.print_button')</span>
    </button>
</div>
<div id="invoice-wrapper">
    <header class="invoice-header">
        <div class="logo">
            @if(isset($settings->app_logo_horizontal))
                <img src="{{ url($settings->app_logo_horizontal) }}" alt="{{ $settings->app_title ?? 'VueFileManager' }}">
            @else
                <h1>{{ $settings->app_title ?? 'VueFileManager' }}</h1>
            @endif
        </div>
        <div class="title">
            <h1>@lang('vuefilemanager.invoice_title')</h1>
        </div>
    </header>
    <section class="invoice-subject">
        <ul class="list">
            <li class="list-item">
                <b>@lang('vuefilemanager.date'):</b>
                <span>{{ format_date($invoice->date()) }}</span>
            </li>
            <li class="list-item">
                <b>@lang('vuefilemanager.product'):</b>
                <span>@lang('vuefilemanager.subscription')</span>
            </li>
            <li class="list-item">
                <b>@lang('vuefilemanager.invoice_number'):</b>
                <span>{{ $invoice->number }}</span>
            </li>
        </ul>
    </section>
    <div class="invoice-partners">
        <div class="partner">
            <h2 class="partner-title">@lang('vuefilemanager.seller'):</h2>
            <ul class="list">
                @isset($settings->billing_vat_number)
                    <li class="list-item">
                        <b>@lang('vuefilemanager.seller_vat'):</b>
                        <span>{{ $settings->billing_vat_number }}</span>
                    </li>
                @endisset

                @isset($settings->billing_name)
                    <li class="list-item">
                        <b>@lang('vuefilemanager.seller_name'):</b>
                        <span>{{ $settings->billing_name }}</span>
                    </li>
                @endisset

                @isset($settings->billing_phone_number)
                    <li class="list-item">
                        <b>@lang('vuefilemanager.seller_phone'):</b>
                        <span>{{ $settings->billing_phone_number }}</span>
                    </li>
                @endisset
            </ul>
            <ul class="list">
                @isset($settings->billing_address)
                    <li class="list-item">
                        <b>@lang('vuefilemanager.address'):</b>
                        <span>{{ $settings->billing_address }}</span>
                    </li>
                @endisset

                @isset($settings->billing_city)
                    <li class="list-item">
                        <b>@lang('vuefilemanager.city'):</b>
                        <span>{{ $settings->billing_city }}</span>
                    </li>
                @endisset

                @isset($settings->billing_state)
                    <li class="list-item">
                        <b>@lang('vuefilemanager.state'):</b>
                        <span>{{ $settings->billing_state }}</span>
                    </li>
                @endisset

                @isset($settings->billing_postal_code)
                    <li class="list-item">
                        <b>@lang('vuefilemanager.postal_code'):</b>
                        <span>{{ $settings->billing_postal_code }}</span>
                    </li>
                @endisset

                @isset($settings->billing_country)
                    <li class="list-item">
                        <b>@lang('vuefilemanager.country'):</b>
                        <span>{{ $settings->billing_country }}</span>
                    </li>
                @endisset
            </ul>
        </div>
        <div class="partner">
            <h2 class="partner-title">@lang('vuefilemanager.client'):</h2>
            <ul class="list">

                @isset($invoice->customer_name)
                    <li class="list-item">
                        <b>@lang('vuefilemanager.name'):</b>
                        <span>{{ $invoice->customer_name }}</span>
                    </li>
                @endisset

                @isset($invoice->customer_phone)
                    <li class="list-item">
                        <b>@lang('vuefilemanager.phone'):</b>
                        <span>{{ $invoice->customer_phone }}</span>
                    </li>
                @endisset
            </ul>
            <ul class="list">
                @isset($invoice->customer_address['line1'])
                    <li class="list-item">
                        <b>@lang('vuefilemanager.address'):</b>
                        <span>{{ $invoice->customer_address['line1'] }}</span>
                    </li>
                @endisset

                @isset($invoice->customer_address['city'])
                    <li class="list-item">
                        <b>@lang('vuefilemanager.city'):</b>
                        <span>{{ $invoice->customer_address['city'] }}</span>
                    </li>
                @endisset

                @isset($invoice->customer_address['state'])
                    <li class="list-item">
                        <b>@lang('vuefilemanager.state'):</b>
                        <span>{{ $invoice->customer_address['state'] }}</span>
                    </li>
                @endisset

                @isset($invoice->customer_address['postal_code'])
                    <li class="list-item">
                        <b>@lang('vuefilemanager.postal_code'):</b>
                        <span>{{ $invoice->customer_address['postal_code'] }}</span>
                    </li>
                @endisset

                @isset($invoice->customer_address['country'])
                    <li class="list-item">
                        <b>@lang('vuefilemanager.country'):</b>
                        <span>{{ $invoice->customer_address['country'] }}</span>
                    </li>
                @endisset
            </ul>
        </div>
    </div>
    <div class="invoice-order">
        <table class="table" width="100%" class="table" border="0">
            <thead class="table-header">
            <tr>
                <td>@lang('vuefilemanager.col_description')</td>
                <td>@lang('vuefilemanager.col_date')</td>
                <td>@lang('vuefilemanager.col_amount')</td>
            </tr>
            </thead>
            <tbody class="table-body">
                @foreach($invoice->invoiceItems() as $item)
                    <tr>
                        <td colspan="2">{{ $item->description }}</td>
                        <td>{{ $item->total() }}</td>
                    </tr>
                @endforeach

                @foreach($invoice->subscriptions() as $subscription)
                    <tr>
                        <td>@lang('vuefilemanager.subscription') ({{ $subscription->quantity }})</td>
                        <td>{{ $subscription->startDateAsCarbon()->formatLocalized('%d. %B. %Y') }} -
                            {{ $subscription->endDateAsCarbon()->formatLocalized('%d. %B. %Y') }}</td>
                        <td>{{ $subscription->total() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="invoice-summary">
        <b>@lang('vuefilemanager.total') {{ $invoice->total() }}</b>
    </div>
</div>
</body>
</html>
