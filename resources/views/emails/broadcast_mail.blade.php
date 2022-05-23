@component('mail::message')
Dear {{ $data['name'] }}

A new post has been shared



@component('mail::panel')
    Title: {{ $data['title'] }}<br><br>
{{ $data['body'] }}
@endcomponent


Thanks,<br>
{{ config('app.name') }}
@endcomponent
