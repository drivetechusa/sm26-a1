<x-mail::message>
# Scheduling Instructions

The following information is needed for accessing the scheduling website. <strong>Your password is case-sensitive</strong>.

Username:  <strong>{{ $student->username }}</strong>

Password: <strong>{{ $password }}</strong>

If you have any problems, just give us a call.  We'd be happy to help!

<x-mail::button :url="'https://lads.drivetechusa.com/templates/lads_scheduling_instructions.pdf'">
Download Instructions
</x-mail::button>
<br>
<x-mail::button :url="'https://lads.drivetimes4u.com/student/login'">
    Goto DriveTimes4U Website
</x-mail::button>

@if($student->pickup_location_id)
    ### Your lessons will be picked up and dropped off at the following location:

    ## {{ $student->pickup_location->name }}<br/>
    ### {{ $student->pickup_location->address }}

@else
    ### You will be picked up at home for your lessons unless you call and make other arrangements.
@endif

Thanks,<br>
{{ config('app.school_name') }}
</x-mail::message>
