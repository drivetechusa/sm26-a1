<x-mail::message>
# Enrollment Successful

Dear {{$student->firstname}},

{{$message}}


Thank you,<br>
{{ config('app.school_name') }}
</x-mail::message>
