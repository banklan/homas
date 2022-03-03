@component('mail::message')
# An admin has logged in.

This serves to notify you that someone just logged into an admin account with the email {{ $admin->email }}, date & time {{ $now }}.
You can choose to take appropriate action if needed or ignore.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
