<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="Pragma" content="no-cache">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700&display=swap" rel="stylesheet">

    @if(is_route('invoice-debug'))
        <link rel="stylesheet" href="{{ mix('css/oasis-invoice.css') }}">

        <style>
            body {
                padding: 25px;
            }
        </style>
    @else
        <link rel="stylesheet" href="{{ public_path('css/oasis-invoice.css') }}">

        <style>
            .page-break {
                page-break-after: always;
                page-break-inside: avoid;
            }
        </style>
    @endif

    <title>{{ __t('in.invoice') }}</title>
</head>
<body>

    <div class="{{ count($invoice->items) > 8 ? 'page-break' : '' }}">
        {{--Invoice header--}}
        <header class="invoice-header">
            <div class="row">
                <div class="col-left">

                    @if($user->invoiceProfile->logo)
                        <img class="logo" src="{{ base64_from_storage_image($user->invoiceProfile->logo) }}">
                    @else
                        <h1>{{ $user->invoiceProfile->company }}</h1>
                    @endif

                    <b class="email">{{ $user->invoiceProfile->email }}</b>
                    <b class="phone">{{ $user->invoiceProfile->phone }}</b>
                </div>
                <div class="col-right align-right">
                    @if($invoice->invoice_type === 'regular-invoice')
                        <h1>{{ __t('in.invoice') }} - {{ __t('in.doc.taxable_doc') }}</h1>
                    @endif
                    @if($invoice->invoice_type === 'advance-invoice')
                        <h1>{{ __t('in.invoice') }} - {{ __t('in.doc.advance_doc') }}</h1>
                    @endif
                    <h2>{{ __t('in.doc.number') }}: {{ $invoice->invoice_number }}</h2>
                    <h4>{{ __t('in.doc.variable_symbol') }}: {{ $invoice->variable_number }}</h4>
                </div>
            </div>
        </header>

        <!--Supplier-->
        <section>
            <div class="supplier">
                <div class="box" style="width: 500px;">
                    <h3>{{ __t('in.doc.customer') }}:</h3>
                    <p>{{ $invoice->client['name'] }}</p>
                    <p>{{ $invoice->client['address'] }}, {{ $invoice->client['city'] }}</p>
                    <p>{{ $invoice->client['postal_code'] }} {{ $invoice->client['country'] }}</p>

                    <div class="single-row">
                        <span>
                            @isset($invoice->client['ico'])
                                <span class="highlight">{{ __t('in_editor.ico') }}</span>: {{ $invoice->client['ico'] }}
                            @endisset
                            @isset($invoice->client['dic'])
                                <span class="highlight">{{ __t('in_editor.dic') }}</span>: {{ $invoice->client['dic'] }}
                            @endisset
                            @isset($invoice->client['ic_dph'])
                                <span class="highlight">{{ __t('in_editor.ic_dph') }}</span>: {{ $invoice->client['ic_dph'] }}
                            @endisset
                        </span>
                    </div>
                </div>
                <div class="dates">
                    <p>{{ __t('in.doc.date_of_issue') }}: {{ format_date($invoice->created_at, '%d. %B %Y') }}</p>
                    <p>{{ __t('in.doc.date_of_delivery') }}: {{ format_date($invoice->delivery_at, '%d. %B %Y') }}</p>
                    <p>{{ __t('in.doc.due_date') }}: {{ format_date($invoice->due_at, '%d. %B %Y') }}</p>
                </div>
            </div>

            <div class="content-box">
                <h3>{{ __t('in.doc.supplier') }}:</h3>
                <p style="padding-bottom: 0">{{ $invoice->user['company'] }}</p>
                <small>{{ $invoice->user['registration_notes'] }}</small>
            </div>

            <div class="content-box">
                <h3>{{ __t('in.doc.headquarters') }}:</h3>
                <p>{{ $invoice->user['address'] }} {{ $invoice->user['city'] }}</p>
                <p>{{ $invoice->user['postal_code'] }}, {{ $invoice->user['country'] }}</p>
            </div>

            <div class="content-box" style="padding-bottom: 0px">
                <h3>{{ __t('user_settings.title_billing') }}:</h3>

                @isset($invoice->user['ico'])
                    <p>{{ __t('in_editor.ico') }}: {{ $invoice->user['ico'] }}</p>
                @endisset
                @isset($invoice->user['dic'])
                    <p>{{ __t('in_editor.dic') }}: {{ $invoice->user['dic'] }}</p>
                @endisset
                @isset($invoice->user['ic_dph'])
                    <p>{{ __t('in_editor.ic_dph') }}: {{ $invoice->user['ic_dph'] }}</p>
                @endisset

                <p>{{ $invoice->user['bank'] }}</p>
                <p>{{ __t('in.form.iban') }}: {{ $invoice->user['iban'] }}, {{ __t('in.form.swift_code') }}: {{ $invoice->user['swift'] }}</p>
            </div>
        </section>

        {{--Special info--}}
        <div class="special-wrapper">
            <div class="special-item">
                <div class="padding">
                    <b>{{ __t('in.doc.bank_account_number') }}:</b>
                    <span>{{ $invoice->user['iban'] }}</span>
                </div>
            </div>
            <div class="special-item">
                <div class="padding">
                    <b>{{ __t('in.doc.variable_symbol') }}:</b>
                    <span>{{ $invoice->variable_number }}</span>
                </div>
            </div>
            <div class="special-item">
                <div class="padding">
                    <b>{{ __t('in.doc.due_date') }}:</b>
                    <span>{{ format_date($invoice->due_at, '%d. %h. %Y') }}</span>
                </div>
            </div>
            <div class="special-item">
                <div class="padding">
                    <b>{{ __t('in.doc.sum_to_pay') }}:</b>
                    <span>{{ format_to_currency($invoice->total_net) }}</span>
                </div>
            </div>
        </div>

        {{--Items table--}}
        <table class="table">
            <thead>
                <tr class="table-row">
                    <td class="table-cell">
                        <span>{{ __t('in.doc.item.name') }}</span>
                    </td>
                    <td class="table-cell">
                        <span>{{ __t('in.doc.item.amount') }}</span>
                    </td>
                    <td class="table-cell">
                        <span>{{ __t('in.doc.item.price_per_unit') }}</span>
                    </td>
                    <td class="table-cell">
                        <span>{{ __t('in.doc.item.total') }}</span>
                    </td>
                    @if($invoice->user['ic_dph'])
                        <td class="table-cell">
                            <span>{{ __t('in.doc.item.vat_rate') }}</span>
                        </td>
                        <td class="table-cell">
                            <span>{{ __t('in.doc.item.vat') }}</span>
                        </td>
                        <td class="table-cell">
                            <span>{{ __t('in.doc.item.total_with_vat') }}</span>
                        </td>
                    @endif
                </tr>
            </thead>

            <tbody>
                @foreach($invoice->items as $item)
                    <tr class="table-row">
                        <td class="table-cell">
                            <span style="word-break: break-word">{{ $item['description'] }}</span>
                        </td>
                        <td class="table-cell">
                            <span>{{ $item['amount'] }}</span>
                        </td>
                        <td class="table-cell">
                            <span>{{ format_to_currency($item['price']) }}</span>
                        </td>

                        <td class="table-cell">
                            <span>{{ format_to_currency($item['price'] * $item['amount']) }}</span>
                        </td>

                        @if($invoice->user['ic_dph'])
                            <td class="table-cell">
                                <span>{{ $item['tax_rate'] }} %</span>
                            </td>
                        @endif

                        @if($invoice->user['ic_dph'])
                            <td class="table-cell">
                                <span>{{ format_to_currency(invoice_item_only_tax_price($item)) }}</span>
                            </td>
                            <td class="table-cell">
                                <span>{{ format_to_currency(invoice_item_with_tax_price($item)) }}</span>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="page-break">
        {{--Item Summary--}}
        <ul class="summary">

            @if($invoice->discount_type)
                <li class="row" style="padding-bottom: 8px">
                    <span>{{ __t('in.doc.discount') }}:</span>
                    <span>-{{ $invoice->discount_type === 'percent' ? $invoice->discount_rate . '%' : format_to_currency($invoice->discount_rate) }}</span>
                </li>
            @endif

            {{--VAT Base--}}
            @if($invoice->user['ic_dph'])
                <div style="padding-bottom: 8px">
                    @foreach(invoice_tax_base($invoice) as $item)
                        <li class="row">
                            <span>{{ __t('in.doc.vat_base') }} {{ $item['rate'] }}%: </span>
                            <span>{{ format_to_currency($item['total']) }}</span>
                        </li>
                    @endforeach
                </div>
            @endif

            {{--VAT Summary--}}
            @if($invoice->user['ic_dph'])
                <div style="padding-bottom: 8px">
                    @foreach(invoice_tax_summary($invoice) as $item)
                        <li class="row">
                            <span>{{ __t('in.doc.item.vat') }} {{ $item['rate'] }}%: </span>
                            <span>{{ format_to_currency($item['total']) }}</span>
                        </li>
                    @endforeach
                </div>
            @endif

            <li class="row">
                <b>{{ __t('in.doc.sum_to_pay') }}:</b>
                <b>{{ format_to_currency(invoice_total($invoice)) }}</b>
            </li>
        </ul>

        <!--Notes-->
        <div class="notes">
            <p>{{ __t('in.doc.thanks') }}</p>
        </div>

        {{--Invoice author--}}
        <div class="invoice-author">
            <div class="tax-note">
                @if(! $invoice->user['ic_dph'])
                    <p>{{ __t('in.doc.not_vat_payer') }}</p>
                @endif
            </div>
            <div class="sign">
                @if(is_route('invoice-debug') && $user->invoiceProfile->stamp)
                    <img src="/{{ $user->invoiceProfile->stamp }}">
                @endif

                @if(! is_route('invoice-debug') && $user->invoiceProfile->stamp)
                    <img src="{{ base64_from_storage_image($user->invoiceProfile->stamp) }}">
                @endif

                <span class="highlight">{{ __t('in.doc.creator') }}:</span> {{ $invoice->user['author'] }}
            </div>
        </div>

        {{--Invoice Footer--}}
        <footer class="invoice-footer">
            <p>{!! __t('in.doc.created_by_app', ['app_name' => 'OasisDrive.cz', 'url' => 'https://oasisdrive.cz']) !!}</p>
        </footer>
    </div>
</body>
</html>
