<?php

namespace App\Events;

use App\Models\Conversation;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReportMessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
 
public $receiver_id;
public $message;

public $image;

public $receiver_type;

    public function __construct($message,$receiver_id,$receiver_type,$image)
    {

        $this->receiver_id= $receiver_id;
        $this->message= $message;

        $this->image= $image;

        $this->receiver_type=$receiver_type;
    }

  
    public function broadcastWith( )
    {
       

        return [
             'message_body'=>$this->message->body,
             'image'=>$this->image,
             'message_date'=>$this->message->created_at->format('m: i a'),
             'receiver_id'=>$this->receiver_id,
             'receiver_type'=>$this->receiver_type,

        ];
        # code...
    }
  

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
      
        if($this->receiver_type=='reporter'){
            return  new PrivateChannel('reporter_report_chat.' .$this->receiver_id);

        }else{
            return  new PrivateChannel('admin_report_chat.' .$this->receiver_id);

        }
    }
}
