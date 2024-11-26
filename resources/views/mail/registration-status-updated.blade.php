<x-mail::message>
# Registration Status Update

Dear {{ $registration->name }},

<x-mail::panel>
Your registration state has been updated to: **{{ $registration->status }}**.
</x-mail::panel>

Contest Name: {{$registration->contest->name}}
<x-mail::button :url="route('contests.registration.myRegistration',$registration->contest)">
View Registration Details
</x-mail::button>
Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
