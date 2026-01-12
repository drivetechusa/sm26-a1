<x-mail::message>
# Scheduled Lessons

In the panel below you will find all upcoming lessons that we have scheduled for you.

<x-mail::panel>
## Scheduled Lessons
@foreach($lessons as $lesson)
**Date:** {{$lesson->start_time->format('l, M jS \a\t g:iA')}} <br>
@endforeach
</x-mail::panel>

Thanks,<br>
{{ config('app.school_name') }}
</x-mail::message>
