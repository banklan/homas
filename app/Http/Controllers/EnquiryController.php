<?php

namespace App\Http\Controllers;

use App\Enquiry;
use App\Mail\EnquiryMail;
use App\Mail\EnquirySent;
use App\AdminNotification;
use App\Events\EnquiryReceived;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EnquiryController extends Controller
{
    public function sendEnquiry(Request $request){
        $this->validate($request, [
            'enquiry.fullname' => 'required|min:5|max:70',
            'enquiry.organization' => 'required|min:3|max:100',
            'enquiry.position' => 'min:2|max:30',
            'enquiry.email' => 'required|email',
            'enquiry.phone' => 'required|max:14',
            'enquiry.subject' => 'required|min:3|max:100',
            'enquiry.message' => 'required|min:5|max:500',
        ]);

        $enquiry = new Enquiry;
        $enquiry->fullname = $request->enquiry['fullname'];
        $enquiry->organization = $request->enquiry['organization'];
        $enquiry->position = $request->enquiry['position'];
        $enquiry->email = $request->enquiry['email'];
        $enquiry->phone = $request->enquiry['phone'];
        $enquiry->subject = $request->enquiry['subject'];
        $enquiry->message = $request->enquiry['message'];
        $enquiry->is_read = false;
        $enquiry->save();

        $enquiry->fresh();

        $notif = new AdminNotification;
        $notif->model = 'Enquiry';
        $notif->is_read = false;
        $notif->save();


        //fire event to notify admin
        event(new EnquiryReceived($enquiry));

        Mail::to($enquiry->email)->send(new EnquirySent($enquiry));

        //send mail with enquiry to homas
        Mail::to('philiafinancing@gmail.com')->send(new EnquiryMail($enquiry));

        return response()->json($enquiry, 200);
    }
}
