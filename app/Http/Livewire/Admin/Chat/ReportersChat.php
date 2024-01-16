<?php

namespace App\Http\Livewire\Admin\Chat;

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


    public function checkconversation($receiverId)
    {

        Session::put('receiver_id',$receiverId);
        $checkedConversation = Conversation::where(function ($query) {
            $query->where('receiver_id', '=', Session::get('receiver_id'))
                  ->orWhere('sender_id', '=', Session::get('receiver_id'));
        })->where(function ($query) {
            $query->where('sender_type', 'reporter')
                  ->orWhere('receiver_type', 'reporter');
        })->get();
        

        if (count($checkedConversation) == 0) {

     // dd(no conversation);

            $createdConversation= Conversation::create(['receiver_id'=>$receiverId,'sender_id'=>auth()->user('admin')->id,'sender_type'=>'admin','receiver_type'=>'reporter','conversation_type'=>'admin_reporter']);
          /// conversation created 

            $createdMessage= Message::create(['conversation_id'=>$createdConversation->id,'sender_id'=>auth('admin')->user()->id,'receiver_id'=>$receiverId,'body'=>$this->message,'sender_type'=>'admin','receiver_type'=>'reporter']);


        $createdConversation->last_time_message= $createdMessage->created_at;
        $createdConversation->save();
        return redirect()->route('admin_chat');




        } else if (count($checkedConversation) >= 1) {

            return redirect()->route('admin_chat')->with('info','Already have Conversation');

        }
        # code...
    }
    public function render()
    {


        $this->reporters = Reporter::all();

        return view('livewire.admin.chat.reporter-chat')->layout('layouts.admin.app');
    }
}
