@component('mail::message')
#{{ __('mails.user.confirm.title') }}, {{ $username }}!

{{ __('mails.user.confirm.body') }}

@component('mail::button', ['url' => $link])
{{ __('mails.user.confirm.button') }}
@endcomponent

@component('mail::subcopy')
{{ __('mails.user.confirm.footer') }} [{{ $link }}]({{ $link }})
@endcomponent

{{ __('mails.signature') }}<br>
{{ __('mails.subsignature') }}
@endcomponent
