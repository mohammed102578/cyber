<?php

namespace App\Events;


use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
 
class NotificationMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
 
public $body;
public $name;
public $time;
public $image;
public $receiver_type;

public $receiver_id;

    public function __construct($body,$name,$time,$image,$receiver_id,$receiver_type)
    {

        $this->body= $body;
        $this->name= $name;
        $this->time= $time;
        $this->image=$image;    
        $this->receiver_type=$receiver_type;
        $this->receiver_id=$receiver_id;

    }

  
    public function broadcastWith( )
    {

        if($this->receiver_type=='reporter'){
            $link="reporter_chat";

        }elseif($this->receiver_type=='corporate'){
            $link="corporate_chat";
        }else{
            $link="admin_chat";
        }
        return [
             'body'=>$this->body,
             'name'=>$this->name,
             'time'=>$this->time,
             'image'=>$this->image,
             'receiver_type'=>$this->receiver_type,
             'link'=>$link
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
            return  new PrivateChannel('reporter_notification_message.' .$this->receiver_id);

        }elseif($this->receiver_type=='admin'){
            return  new PrivateChannel('admin_notification_message.' .$this->receiver_id);

        }else{
            return  new PrivateChannel('corporate_notification_message.' .$this->receiver_id);

        }
    }
}
