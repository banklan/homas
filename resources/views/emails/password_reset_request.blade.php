@component('mail::message')
# Password Reset Request!

Dear <strong>{{ $user->fullname }}</strong>,
You are seeing this email because we received a password reset request from your email, for your account at www.hozulinks.com.<br>
If you did not make this request, just ignore this email.
If the request was made by you, Kindly click the link below.


@component('mail::button', ['url' => 'http://localhost:8000/password-reset?token='.$recovery->token])
Reset Link
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
