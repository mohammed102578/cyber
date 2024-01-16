<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageRead implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
 
    public $conversation_id;
    public $receiver_id;

    public $receiver_type;

    public function __construct($conversation_id,$receiver_id,$receiver_type)
    {

        $this->conversation_id= $conversation_id;
        $this->receiver_id= $receiver_id;
        $this->receiver_type= $receiver_type;

        //
    }

    public function  broadcastWith()
    {

         return [

            'conversation_id'=>$this->conversation_id,
            'receiver_id'=> $this->receiver_id,
         ];
        # code..
    }
    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {

        if($this->receiver_type=='reporter'){
            return  new PrivateChannel('reporter_chat.' .$this->receiver_id);

        }elseif($this->receiver_type=='admin'){
            return  new PrivateChannel('admin_chat.' .$this->receiver_id);

        }else{
            return  new PrivateChannel('corporate_chat.' .$this->receiver_id);

        }
    }
}
