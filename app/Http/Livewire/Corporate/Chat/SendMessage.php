<?php

namespace App\Http\Livewire\corporate\Chat;

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


        $this->createdMessage = Message::create([
            'conversation_id' => $this->selectedConversation->id,
            'sender_id' => auth()->user('corporate')->id,
            'receiver_id' => $this->receiverInstance['id'],
            'sender_type' => "corporate",
            'receiver_type' => 'admin',
            'body' => $this->body,

        ]);

        $name=Auth::guard('corporate')->user()->company_name;
        $image=Auth::guard('corporate')->user()->image;
        $body=$this->body;
        $time=$this->createdMessage->created_at->diffForHumans();
        $receiver_id=$this->receiverInstance['id'];
        event(new NotificationMessage($body,$name,$time,$image,$receiver_id,'admin'));


        $this->selectedConversation->last_time_message = $this->createdMessage->created_at;
        $this->selectedConversation->save();
        $this->emitTo('corporate.chat.chatbox', 'pushMessage', $this->createdMessage->id);


        //reshresh coversation list
        $this->emitTo('corporate.chat.chat-list', 'refresh');
        $this->reset('body');

        $this->emitSelf('dispatchMessageSent');
        // dd($this->body);
        # code..
    }



    public function dispatchMessageSent()
    {

       broadcast(new MessageSent(Auth()->user('corporate'), $this->createdMessage, $this->selectedConversation, $this->receiverInstance));
        # code...
    }
    public function render()
    {
        return view('livewire.corporate.chat.send-message')->layout('layouts.corporate.app');
    }
}
