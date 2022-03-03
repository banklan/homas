@component('mail::message')
# Thank you for dropping a message!

Dear {{ $enquiry->fullname }},<br>
We have received your enquiry at <strong>hozulinks.com</strong> and will get back to you very soon.
Thanks for contacting us.
<br>
{{ config('app.name') }}
@endcomponent
