<?php

namespace App\Http\Livewire\Reporter\Chat;

use App\Events\MessageSent;
use App\Events\NotificationMessage;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Reporter\Reporter;
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
    $receiver_type='admin';

}

        $this->createdMessage = Message::create([
            'conversation_id' => $this->selectedConversation->id,
            'sender_id' => auth()->user('reporter')->id,
            'receiver_id' => $this->receiverInstance['id'],
            'sender_type' => "reporter",
            'receiver_type' => $receiver_type,
            'body' => $this->body,

        ]);

        $name=Auth::guard('reporter')->user()->first_name." ".Auth::guard('reporter')->user()->last_name;
        $image=Auth::guard('reporter')->user()->image;
        $body=$this->body;
        $time=$this->createdMessage->created_at->diffForHumans();
        $receiver_id=$this->receiverInstance['id'];
        event(new NotificationMessage($body,$name,$time,$image,$receiver_id,$receiver_type));

        $this->selectedConversation->last_time_message = $this->createdMessage->created_at;
        $this->selectedConversation->save();
        $this->emitTo('reporter.chat.chatbox', 'pushMessage', $this->createdMessage->id);


        //reshresh coversation list
        $this->emitTo('reporter.chat.chat-list', 'refresh');
        $this->reset('body');

        $this->emitSelf('dispatchMessageSent');
        // dd($this->body);
        # code..
    }



    public function dispatchMessageSent()
    {
//dd(auth()->user('reporter'));
       broadcast(new MessageSent(auth()->user('reporter'), $this->createdMessage, $this->selectedConversation, $this->receiverInstance));
        # code...
    }
    public function render()
    {
        return view('livewire.reporter.chat.send-message')->layout('layouts.reporter.app');
    }
}
