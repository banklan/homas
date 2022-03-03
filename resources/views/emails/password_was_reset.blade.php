@component('mail::message')
# Your password has been reset!

Dear <strong>{{ $user->fullname }}</strong>,
Your password has been reset successfully. <br>
Thank you for using hozulinks.com.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
