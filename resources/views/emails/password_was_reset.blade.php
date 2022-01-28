@component('mail::message')
# Your password has been reset!

Dear <strong>{{ $user->fullname }}</strong>,
Your password has been reset successfully. <br>
Thank you for using homas.com.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
