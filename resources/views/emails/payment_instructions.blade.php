<x-mail::message>
# Payment Instructions

<x-mail::panel>
**Student:** {{$student->firstname}} {{$student->lastname}}<br>
**Username:** {{$student->username}}<br>
{{--**Birthdate:** {{$student->dob->format('m/d/Y')}}--}}
</x-mail::panel>

Using the student username ( {{$student->username}} ), please login to your account.  An email with a
one-time passcode will be sent to all emails on the account. However, the code must be entered in the device
that initiated the request.  All other accounts can safely disregard the email.

Enter that code to be taken to the dashboard for your account.  In the menu on the left, you will see "Make Payment".
Click that button to be taken to the payment form.

You may pay any portion of that balance. When your balance is paid in full, we
will remove your account from “HOLD” status which will allow you to schedule your lessons.

<x-mail::button :url="'https://alordashley.com/login'">
Login at Website
</x-mail::button>

Thanks,<br>
{{ config('app.school_name') }}
</x-mail::message>
