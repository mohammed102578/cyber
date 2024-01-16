<?php

namespace App\Http\Livewire\Reporter\Chat;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Reporter\Report;
use App\Models\Reporter\Reporter;
use GuzzleHttp\Promise\Create;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class reportersChat extends Component
{
    public $reporters;
    public $message= 'hello how are you ';
    public $receiver_id;


    public function checkconversation($receiverId)
    {

        $this->receiver_id=$receiverId;
        $checkedConversation = Conversation::where(function ($query) {
            $query->where('receiver_id', '=', $this->receiver_id)
                  ->orWhere('sender_id', '=', $this->receiver_id);
        })->where(function ($query) {
            $query->where('sender_type', 'reporter')
                  ->orWhere('receiver_type', 'reporter');
        })->where('conversation_type','reporter_reporter')->get();
        
       // dd($checkedConversation );

        if (count($checkedConversation) == 0) {


            $createdConversation= Conversation::create(['receiver_id'=>$receiverId,'sender_id'=>auth()->user('reporter')->id,'sender_type'=>'reporter','receiver_type'=>'reporter','conversation_type'=>'reporter_reporter']);
          /// conversation created 

            $createdMessage= Message::create(['conversation_id'=>$createdConversation->id,'sender_id'=>auth('reporter')->user()->id,'receiver_id'=>$receiverId,'body'=>$this->message,'sender_type'=>'reporter','receiver_type'=>'reporter']);


        $createdConversation->last_time_message= $createdMessage->created_at;
        $createdConversation->save();
        return redirect()->route('reporter_chat');




        } else if (count($checkedConversation) >= 1) {

            return redirect()->route('reporter_chat')->with('info','Already have Conversation');

        }
        # code...
    }
    public function render()
    {


        $this->reporters = Reporter::all();

        return view('livewire.reporter.chat.reporter-chat')->layout('layouts.reporter.app');
    }
}
