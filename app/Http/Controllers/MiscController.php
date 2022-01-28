<?php

namespace App\Http\Controllers;

use App\Admin;
use App\PushNotif;
use Illuminate\Http\Request;
use App\Events\TestNameSaved;
use App\Notifications\TestNameNotif;
use App\Notifications\TestNameSaved as NotificationsTestNameSaved;
use Illuminate\Notifications\Notification;

class MiscController extends Controller
{
    public function sendNotification(Request $request){
        $notif = new PushNotif;
        $notif->name = $request->name;
        $notif->save();

        $notif->fresh();

        event(new TestNameSaved($notif));
        $admins = Admin::all();
        // Notification::send($admins, new TestNameNotif($notif));

        return response()->json($notif, 200);
    }
}
