@component('mail::message')
# Thank you for signing up at homas!

Dear **{{ $user->first_name }}**,<br>
We are delighted to have you join us at homas.com. We hope you enjoy your experience with us as we help you take your services to your clients.<br>
To be able to log in, you need to verify your email address. kindly click on the link below to do so. <br>

@component('mail::button', ['url' => 'http://localhost:8000/email-confirmation?token='.$conf->token])
Verify Your Email
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
