<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">

    <title>
        {{ __t('invoice_title') }}
    </title>

    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;300;400;600;700;800;900&display=swap" rel="stylesheet">
    <link href="{{ mix('css/tailwind.css') }}?v={{ get_version() }}" rel="stylesheet" type="text/css">

    <style>
        * {
            outline: 0;
            margin: 0;
            padding: 0;
            font-family: 'Nunito', sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            box-sizing: border-box;
            -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
            font-size: 15px;
            text-decoration: none;
            color: #1B2539;
        }
    </style>

    @include('vuefilemanager.others.color-template')
</head>
<body class="bg-light-background">
    <div class="rounded-xl max-w-3xl mx-auto my-5 print:hidden">
        <button class="flex items-center bg-white py-1 px-2 rounded-lg" onclick="window.print();">

            <svg class="transform scale-75" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="6 9 6 2 18 2 18 9"></polyline>
                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                <rect x="6" y="14" width="12" height="8"></rect>
            </svg>

            <span class="font-bold ml-2 text-sm">
                {{ __t('print_button') }}
            </span>
        </button>
    </div>
    <div class="bg-white rounded-xl max-w-3xl mx-auto print:p-0 p-10">

        <!--Invoice Header-->
        <header class="flex justify-between items-start pb-4 mb-4 border-b dark:border-opacity-5 border-light border-dashed">
            <div>
                @if(isset($settings->app_logo_horizontal))
                    <img src="{{ url($settings->app_logo_horizontal) }}" alt="{{ $settings->app_title ?? 'VueFileManager' }}" class="h-8">
                @else
                    <h1 class="text-2xl font-bold">
                        {{ $settings->app_title ?? 'VueFileManager' }}
                    </h1>
                @endif

                <b class="text-gray-800 text-sm">
                    {{ $settings->contact_email }}
                </b>
            </div>

            <div class="text-right">
                <h1 class="text-2xl font-bold">
                    {{ __t('invoice_title') }}
                </h1>
                <b class="text-gray-800 text-sm">
                    Number: {{ $invoice->id }}
                </b>
            </div>
        </header>

        <!-- Invoice partners -->
        <div class="flex justify-between items-start space-x-8 pb-6 mb-6 border-b dark:border-opacity-5 border-light border-dashed">
            <!-- Seller -->
            <div>
                <div class="mb-3">
                    <b class="text-theme text-base font-bold mb-1 block">
                        {{ __t('seller') }}:
                    </b>

                    @isset($settings->billing_name)
                        <span class="font-bold">
                            {{ $settings->billing_name }}
                        </span>
                    @endisset
                </div>

                <div class="mb-3">
                    <b class="text-theme text-base font-bold mb-1 block">
                        Head Office:
                    </b>

                    @isset($settings->billing_address)
                        <span class="font-bold block">
                            {{ $settings->billing_address }}
                        </span>
                    @endisset

                    @isset($settings->billing_city)
                        <span class="font-bold block">
                            {{ $settings->billing_city }}
                        </span>
                    @endisset

                    @isset($settings->billing_state)
                        <span class="font-bold block">
                            {{ $settings->billing_state }}
                        </span>
                    @endisset

                    @isset($settings->billing_postal_code)
                        <span class="font-bold block">
                            {{ $settings->billing_postal_code }}
                        </span>
                    @endisset

                    @isset($settings->billing_country)
                        <span class="font-bold block">
                            {{ $settings->billing_country }}
                        </span>
                    @endisset

                    @isset($settings->billing_phone_number)
                        <span class="font-bold block">
                            {{ $settings->billing_phone_number }}
                        </span>
                    @endisset
                </div>

                <div>
                    <b class="text-theme text-base font-bold mb-1 block">
                        Billing Information:
                    </b>

                    @isset($settings->billing_vat_number)
                        <span class="font-bold block">
                            VAT ID: {{ $settings->billing_vat_number }}
                        </span>
                    @endisset

                    {{--@if(isset($vat))
                        <span class="text-base">
                            {{ $vat }}
                        </span>
                    @endif--}}
                </div>
            </div>

            <!--Client-->
            <div>
                <div class="bg-light-background rounded-xl p-4" style="width: 360px">
                    <b class="text-theme text-base font-bold mb-1.5 block">
                        Client:
                    </b>

                    @isset($invoice->user->settings->name)
                        <span class="font-bold block">
                            {{ $invoice->user->settings->name }}
                        </span>
                    @endisset

                    @isset($invoice->user->settings->address)
                        <span class="font-bold block">
                            {{ $invoice->user->settings->address }}
                        </span>
                    @endisset

                    @isset($invoice->user->settings->postal_code)
                        <span class="font-bold inline-block">
                            {{ $invoice->user->settings->postal_code }}
                        </span>
                    @endisset

                    @isset($invoice->user->settings->city)
                        <span class="font-bold inline-block">
                            {{ $invoice->user->settings->city }}
                        </span>,
                    @endisset

                    @isset($invoice->user->settings->state)
                        <span class="font-bold inline-block">
                            {{ $invoice->user->settings->state }}
                        </span>
                    @endisset

                    @isset($invoice->user->settings->country)
                        <span class="font-bold inline-block">
                            {{ $invoice->user->settings->country }}
                        </span>
                    @endisset

                    @isset($invoice->user->settings->phone_number)
                        <span class="font-bold block">
                            {{ $invoice->user->settings->phone_number }}
                        </span>
                    @endisset
                </div>
                <div class="font-bold block text-right pt-3">
                    Date of issue: {{ format_date($invoice->created_at) }}
                </div>
            </div>
        </div>

        <!-- Invoice Items -->
        <div class="pb-6 mb-6 border-b dark:border-opacity-5 border-light border-dashed">

            <!--Items-->
            <table class="w-full mb-12">
                <thead>
                    <tr>
                        <td class="pb-5">
                            <span class="text-theme dark:text-gray-500 text-gray-400 text-xs font-bold">
                                Description
                            </span>
                        </td>
                        <td class="pb-5">
                            <span class="text-theme dark:text-gray-500 text-gray-400 text-xs font-bold">
                                Period
                            </span>
                        </td>
                        <td class="pb-5">
                            <span class="text-theme dark:text-gray-500 text-gray-400 text-xs font-bold">
                                Usage
                            </span>
                        </td>
                        <td class="pb-5 text-right">
                            <span class="text-theme dark:text-gray-500 text-gray-400 text-xs font-bold">
                                Amount
                            </span>
                        </td>
                    </tr>
                </thead>

                @if($invoice->metadata)
                    <tbody>
                        @foreach($invoice->metadata as $item)
                            <tr class="border-b dark:border-opacity-5 border-light border-dashed whitespace-nowrap {{ $loop->index % 2 ? 'bg-light-background' : '' }}">
                                <td class="py-1.5 px-3">
                                    <span class="text-sm font-bold">
                                        {{ __t($item['feature']) }}
                                    </span>
                                </td>
                                <td class="py-1.5">
                                    <span class="text-sm font-bold">
                                        {{ $invoice->note }}
                                    </span>
                                </td>
                                <td class="py-1.5">
                                    <span class="text-sm font-bold">
                                        {{ $item['usage'] }}
                                    </span>
                                </td>
                                <td class="py-1.5 px-3 text-right">
                                    <span class="text-sm font-bold">
                                        {{ $item['cost'] }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                @else
                    <tr class="whitespace-nowrap">
                        <td class="py-1.5 pr-3">
                            <span class="text-sm font-bold">
                                Subscription
                            </span>
                        </td>
                        <td class="py-1.5">
                            <span class="text-sm font-bold">
                                {{ $invoice->note }}
                            </span>
                        </td>
                        <td class="py-1.5">
                            <span class="text-sm font-bold">
                                1
                            </span>
                        </td>
                        <td class="py-1.5 pl-3 text-right">
                            <span class="text-sm font-bold">
                                {{ format_currency($invoice->amount, $invoice->currency) }}
                            </span>
                        </td>
                    </tr>
                @endif
            </table>

            <!-- Invoice Total -->
            <div class="text-right">
                <b class="text-2xl font-extrabold">
                    {{ __t('total') }} {{ format_currency($invoice->amount, $invoice->currency) }}
                </b>
            </div>
        </div>

        <!-- Invoice Items -->
        <footer class="text-center">
            <p class="font-bold">
                Generated by <a href="{{ url('/') }}" target="_blank" class="font-bold text-theme underline">{{ $settings->app_title }}</a>
            </p>
        </footer>
    </div>
</body>
</html>
