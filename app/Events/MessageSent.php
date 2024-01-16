<?php

namespace App\Events;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Reporter\Reporter;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
 
public $sender;
public $message;
public $conversation;
public $receiver;

public $receiver_type;

    public function __construct($sender,Message $message,Conversation $conversation,$receiver)
    {

        $this->sender= $sender;
        $this->message= $message;
        $this->conversation= $conversation;
        $this->receiver=$receiver;    
        $this->receiver_type=$message['receiver_type'];
    }

  
    public function broadcastWith( )
    {
        //dd($this->receiver_type);

        if($this->receiver_type=='reporter'){
            $receiver_id=$this->receiver->id;

        }else{
            $receiver_id=$this->receiver['id'];
        }
        return [
             'sender_id'=>$this->sender->id,
             'message_id'=>$this->message['id'],
             'conversation_id'=>$this->conversation['id'],
             'receiver_id'=>$receiver_id,
             'receiver_type'=>$this->message['receiver_type'],
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
            return  new PrivateChannel('reporter_chat.' .$this->receiver->id);

        }elseif($this->receiver_type=='admin'){
            return  new PrivateChannel('admin_chat.' .$this->receiver['id']);

        }else{
            return  new PrivateChannel('corporate_chat.' .$this->receiver['id']);

        }
    }
}
