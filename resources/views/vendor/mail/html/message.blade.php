<?php
use App\Setting;

$app_name = strval(Setting::where('name', 'app_title')->pluck('value')[0]);

?>

@component('mail::layout')
{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => config('app.url')])
{{ $app_name ? $app_name : 'VueFileManager' }}
@endcomponent
@endslot

{{-- Body --}}
{{ $slot }}

{{-- Subcopy --}}
@isset($subcopy)
@slot('subcopy')
@component('mail::subcopy')
{{ $subcopy }}
@endcomponent
@endslot
@endisset

{{-- Footer --}}
@slot('footer')
@component('mail::footer')
© {{ date('Y') }} {{ $app_name ? $app_name : 'VueFileManager' }}. @lang('All rights reserved.')
@endcomponent
@endslot
@endcomponent
