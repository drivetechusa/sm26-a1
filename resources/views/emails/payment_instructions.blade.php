<x-mail::message>
# Payment Instructions

<x-mail::panel>
**Student:** {{$student->firstname}} {{$student->lastname}}<br>
**Username:** {{$student->username}}<br>
**Birthdate:** {{$student->dob->format('m/d/Y')}}
</x-mail::panel>

    Please use the above information to make any payments online. Username is case-sensitive
    and the formatting for the birthdate is required. After logging in, you will be presented with
    your balance. You may pay any portion of that balance. When your balance is paid in full, we
    will remove your account from “HOLD” status which will allow you to schedule your lessons.

    1. Enter your Username and Birthdate and click "Find my records"
    2. The left side of the next screen will display your balance due and the information we have on file for your records to make sure it is you and you didn't make a typographical error.
    3. Enter your credit card information. You can change any of the information on the screen. Your information from your file is pre-filled for your convenience.
    4. Enter the amount you wish to pay.
    5. Click the "Make Payment" button
    6. Wait until you receive an email from us showing that your account has been updated. Please allow us time to update your records. This will take longer if you submit your payment outside of our regular office hours.


<x-mail::button :url="'https://a1drivingschoolsc.com/make_payment'">
Make a Payment
</x-mail::button>

Thanks,<br>
{{ config('app.school_name') }}
</x-mail::message>
