@component('mail::message')
# Enquiry on homas!

An enquiry has been received on homas.com, sent by {{ $enquiry->fullname }}.<br/>
<p style="font-size: 16px;"><strong>Sender: </strong> {{ $enquiry->fullname }}</p>
<p style="font-size: 16px;"><strong>Organization: </strong> {{ $enquiry->organization }}</p>
<p style="font-size: 16px;"><strong>Position: </strong> {{ $enquiry->position }}</p>
<p style="font-size: 16px;"><strong>Email: </strong> {{ $enquiry->email }}</p>
<p style="font-size: 16px;"><strong>Phone: </strong> {{ $enquiry->phone }}</p>
<p style="font-size: 16px;"><strong>Subject: </strong> {{ $enquiry->subject }}</p>
<p style="font-size: 16px;"><strong>Enquiry:</strong> </p>
<p style="font-size: 16px; padding: 10px">{{ $enquiry->message }}</p>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
