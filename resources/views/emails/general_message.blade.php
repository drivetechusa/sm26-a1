<x-mail::message>
# Message from {{ config('app.school_name') }}

<x-mail::panel>
{{ $message }}
</x-mail::panel>


Thanks,<br>
{{ config('app.school_name') }}
</x-mail::message>
