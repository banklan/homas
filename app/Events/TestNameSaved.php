<?php

namespace App\Events;

use App\PushNotif;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Symfony\Contracts\EventDispatcher\Event;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TestNameSaved extends Event implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notif;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(PushNotif $notif)
    {
        $this->notif = $notif;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // return new PrivateChannel('notif');
        return new Channel('notif');
    }

    // public function broadcastAs(){
    //     return 'name.created';
    // }
}
