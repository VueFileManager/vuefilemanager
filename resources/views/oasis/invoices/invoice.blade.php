<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="Pragma" content="no-cache">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;900&display=swap" rel="stylesheet">

    @if(is_route('invoice-debug'))
        <link rel="stylesheet" href="{{ mix('css/oasis-invoice.css') }}">

        <style>
            body {
                padding: 25px;
            }
        </style>
    @else
        <link rel="stylesheet" href="{{ public_path('css/oasis-invoice.css') }}">
    @endif

    <title>Invoice</title>
</head>
<body>
    {{--Invoice header--}}
    <header class="invoice-header">
        <div class="row">
            <div class="col-left">

                {{--TODO: klientske logo--}}
                <img class="logo" src="{{ get_storage_path('system/5YDehSGh-vuefilemanager-horizontal-logo.svg') }}">

                <b class="email">{{ $user->email }}</b>
                <b class="phone">{{ $user->settings->phone_number }}</b>
            </div>
            <div class="col-right align-right">
                @if($invoice->invoice_type === 'regular-invoice')
                    <h1>Faktúra - daňový doklad</h1>
                @endif
                @if($invoice->invoice_type === 'advance-invoice')
                    <h1>Faktúra - zálohový doklad</h1>
                @endif
                <h2>Číslo: {{ $invoice->invoice_number }}</h2>
                <h4>Variabilný symbol: {{ $invoice->variable_number }}</h4>
            </div>
        </div>
    </header>

    <!--Supplier-->
    <section>
        <div class="supplier">
            <div class="box">
                <h3>Odberateľ:</h3>
                <p>{{ $invoice->client['name'] }}</p>
                <p>{{ $invoice->client['address'] }}, {{ $invoice->client['city'] }}</p>
                <p>{{ $invoice->client['postal_code'] }} {{ $invoice->client['country'] }}</p>

                <div class="single-row">
                    <span>
                        @isset($invoice->client['ico'])
                            <span class="highlight">IČO</span>: {{ $invoice->client['ico'] }}
                        @endisset
                        @isset($invoice->client['dic'])
                            <span class="highlight">DIČ</span>: {{ $invoice->client['dic'] }}
                        @endisset
                        @isset($invoice->client['ic_dph'])
                            <span class="highlight">IC DPH</span>: {{ $invoice->client['ic_dph'] }}
                        @endisset
                    </span>
                </div>
            </div>
            <div class="dates">
                <p>Dátum vystavenia: {{ format_date($invoice->created_at, '%d. %B %Y') }}</p>
                <p>Dátum dodania: {{ format_date($invoice->delivery_at, '%d. %B %Y') }}</p>
                <p>Dátum splatnosti: {{ format_date($invoice->due_at, '%d. %B %Y') }}</p>
            </div>
        </div>

        <div class="content-box">
            <h3>Dodávateľ:</h3>
            <p>{{ $user->settings->name }}</p>
            <small>{{ $user->settings->registration_notes }}</small>
        </div>

        <div class="content-box">
            <h3>Sídlo:</h3>
            <p>{{ $user->settings->address }} {{ $user->settings->city }}</p>
            <p>{{ $user->settings->postal_code }}, {{ $user->settings->country }}</p>
        </div>

        <div class="content-box">
            <h3>Faktúračné údaje:</h3>

            @isset($user->settings->ico)
                <p>IČO: {{ $user->settings->ico }}</p>
            @endisset
            @isset($user->settings->dic)
                <p>DIČ: {{ $user->settings->dic }}</p>
            @endisset
            @isset($invoice->supplier_ic_dph)
                <p>IČ DPH: {{ $user->settings->ic_dph }}</p>
            @endisset

            <p>{{ $user->settings->bank_name }}</p>
            <p>IBAN: {{ $user->settings->iban }}, BIC kód/SWIFT: {{ $user->settings->swift }}</p>
        </div>
    </section>

    {{--Special info--}}
    <div class="special-wrapper">
        <div class="special-item">
            <div class="padding">
                <b>Číslo účtu:</b>
                <span>{{ $user->settings->iban }}</span>
            </div>
        </div>
        <div class="special-item">
            <div class="padding">
                <b>Variabilný symbol:</b>
                <span>{{ $invoice->variable_number }}</span>
            </div>
        </div>
        <div class="special-item">
            <div class="padding">
                <b>Dátum splatnosti:</b>
                <span>{{ format_date($invoice->due_at, '%d. %h. %Y') }}</span>
            </div>
        </div>
        <div class="special-item">
            <div class="padding">
                <b>Suma na úhradu:</b>
                <span>{{ format_to_currency($invoice->total_net) }}</span>
            </div>
        </div>
    </div>

    {{--Items table--}}
    <table class="table">
        <thead>
            <tr class="table-row">
                <td class="table-cell">
                    <span>Názov produktu</span>
                </td>
                <td class="table-cell">
                    <span>Množstvo</span>
                </td>
                <td class="table-cell">
                    <span>J. Cena</span>
                </td>
                <td class="table-cell">
                    <span>Celkom</span>
                </td>

                {{--TODO: zmenit dph z klienta na usera--}}
                @if($invoice->client['ic_dph'])
                    <td class="table-cell">
                        <span>Sadzba DPH</span>
                    </td>
                @endif
                @if($invoice->client['ic_dph'])
                    <td class="table-cell">
                        <span>DPH</span>
                    </td>
                    <td class="table-cell">
                        <span>Celkom s DPH</span>
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

                    @if($invoice->client['ic_dph'])
                        <td class="table-cell">
                            <span>{{ $item['tax_rate'] }} %</span>
                        </td>
                    @endif

                    @if($invoice->client['ic_dph'])
                        <td class="table-cell">
                            <span>{{ invoice_item_only_tax_price($item, true) }}</span>
                        </td>
                        <td class="table-cell">
                            <span>{{ invoice_item_with_tax_price($item, true) }}</span>
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>

    {{--Item Summary--}}
    {{--TODO: doplnit vypis za danove sadzby--}}
    {{--TODO: dokoncit prepocitavanie zlav--}}
    <ul class="summary">

        @if($invoice->discount_type)
            <li class="row">
                <span>Zlava za doklad:</span>
                <!-- -32,64-->
                <span>-{{ invoice_total_discount($invoice, true) }}</span>
            </li>
        @endif

        {{--VAT Payer--}}
        @if($invoice->client['ic_dph'] && ! $invoice->discount_type)
            <li class="row">
                <span>Cena bez DPH:</span>
                <span>{{ format_to_currency($invoice->total_net) }}</span>
            </li>
            <li class="row">
                <span>DPH:</span>
                <span>{{ format_to_currency($invoice->total_tax) }}</span>
            </li>
        @endif

        {{--VAT Payer with Discount--}}
        @if($invoice->client['ic_dph'] && $invoice->discount_type)
            <li class="row">
                <span>Cena bez DPH:</span>
                <span>
                    @if($invoice->discount_type === 'percent')
                        <!--244,80-->
                        {{ format_to_currency($invoice->total_net * ((100 - $invoice->discount_rate) / 100)) }}
                    @endif
                    @if($invoice->discount_type === 'value')
                        <!--263,67-->
                            {{ format_to_currency(($invoice->total_net + invoice_total_tax($invoice)) / ((100 - $invoice->discount_rate) / 100)) }}
                        @endif
                </span>
            </li>
            <li class="row">
                <span>DPH:</span>
                @if($invoice->discount_type === 'percent')
                    <!--48,96-->
                    <span>{{ format_to_currency(invoice_total_tax($invoice) * ((100 - $invoice->discount_rate) / 100)) }}</span>
                @endif
                @if($invoice->discount_type === 'value')
                    <!--52,73-->
                @endif
            </li>
        @endif

        <li class="row">
            <b>Spolu k úhrade:</b>
            @if($invoice->client['ic_dph'])
                <b>{{ format_to_currency(invoice_total_net($invoice) + invoice_total_tax($invoice)) }}</b>
            @else
                <b>{{ format_to_currency(invoice_total_net($invoice)) }}</b>
            @endif
        </li>
    </ul>

    <!--Notes-->
    <div class="notes">
        <p>Ďakujeme, že ste sa rozhodli pre naše služby.</p>
    </div>

    {{--Invoice header--}}
    <div class="invoice-author">
        <div class="tax-note">
            @if(! $invoice->client['ic_dph'])
                <p>Nie sme platci DPH</p>
            @endif
        </div>
        <div class="sign">
            @if(is_route('invoice-debug'))
                <img src="{{ asset('/stamp.png') }}">
            @endif
            {{--<img src="{{ public_path('/stamp.png') }}">--}}
            <span class="highlight">Faktúru vystavil:</span> {{ $invoice->user['name'] }}
        </div>
    </div>

    {{--Invoice Footer--}}
    <footer class="invoice-footer">
        <p>Vygenerované aplikáciou <a href="https://oasisdrive.cz">OasisDrive.cz</a></p>
    </footer>
</body>
</html>
