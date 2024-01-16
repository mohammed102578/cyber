<?php

namespace App\Http\Livewire\Admin\Chat;

use App\Events\MessageSent;
use App\Events\NotificationMessage;
use App\Models\Conversation;
use App\Models\Corporate\Corporate;
use App\Models\Message;
use App\Models\Reporter\Reporter;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SendMessage extends Component
{
    public $selectedConversation;
    public $receiverInstance;
    public $body;
    public $createdMessage;
    protected $listeners = ['updateSendMessage', 'dispatchMessageSent','resetComponent'];


    public function resetComponent()
    {

  $this->selectedConversation= null;
  $this->receiverInstance= null;

        # code...
    }



    function updateSendMessage(Conversation $conversation,  $receiver)
    {

        // dd($conversation,$receiver);
        $this->selectedConversation = $conversation;
        $this->receiverInstance = $receiver;
        # code...
    }




    public function sendMessage()
    {

        if ($this->body == null) {
            return null;
        }
if(isset($this->receiverInstance['first_name'])){
    $receiver_type='reporter';
    $this->receiverInstance=Reporter::find($this->receiverInstance['id']);

}else{
    $this->receiverInstance=Corporate::find($this->receiverInstance['id']);
    $receiver_type='corporate';


}

        $this->createdMessage = Message::create([
            'conversation_id' => $this->selectedConversation->id,
            'sender_id' => auth()->user('admin')->id,
            'receiver_id' => $this->receiverInstance['id'],
            'sender_type' => "admin",
            'receiver_type' => $receiver_type,
            'body' => $this->body,

        ]);

        $name=Auth::guard('admin')->user()->name;
        $image=Auth::guard('admin')->user()->image;
        $body=$this->body;
        $time=$this->createdMessage->created_at->diffForHumans();
        $receiver_id=$this->receiverInstance['id'];
        event(new NotificationMessage($body,$name,$time,$image,$receiver_id,$receiver_type));


        $this->selectedConversation->last_time_message = $this->createdMessage->created_at;
        $this->selectedConversation->save();
        $this->emitTo('admin.chat.chatbox', 'pushMessage', $this->createdMessage->id);


        //reshresh coversation list
        $this->emitTo('admin.chat.chat-list', 'refresh');
        $this->reset('body');

        $this->emitSelf('dispatchMessageSent');
        //dd($this->receiverInstance);
        # code..
    }



    public function dispatchMessageSent()
    {

//dd( $this->receiverInstance);
      broadcast(new MessageSent(Auth()->user('admin'),$this->createdMessage, $this->selectedConversation, $this->receiverInstance));
        # code...
    }
    public function render()
    {
        return view('livewire.admin.chat.send-message')->layout('layouts.admin.app');
    }
}
