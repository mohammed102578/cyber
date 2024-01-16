<?php

namespace App\Events;

use Carbon\Carbon;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Str;

class CorporateEventsNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $notification_id;
    public $title;
    public $body;
    public $created_at;
    public $sender_image;

   

    public function __construct($data = [])
    {
        $this->notification_id = $data['notification_id'];
        $this->title = $data['title'];
        $this->body = $data['body'];
        $this->created_at =  $data['created_at'];
        $this->sender_image = $data['sender_image'];

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        //return new channel('new-notification')
        return ['all-notification'];
    }

}
