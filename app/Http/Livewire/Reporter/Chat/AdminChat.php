<?php

namespace App\Http\Livewire\Reporter\Chat;

use App\Models\Admin\Admin;
use App\Models\Conversation;
use App\Models\Corporate\Corporate;
use App\Models\Message;
use GuzzleHttp\Promise\Create;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use PhpParser\Node\Stmt\Global_;

class   AdminChat extends Component
{
    public $admins;
    public $receiver_id;
    public $message= 'hello how are you ';


    public function checkconversation($receiverId)
    {

        $this->receiver_id=$receiverId;
       // dd($this->receiver_id);


        $checkedConversation = Conversation::where(function ($query) {
            $query->where('receiver_id', '=', $this->receiver_id)
                  ->orWhere('sender_id', '=', $this->receiver_id);
        })->where(function ($query) {
            $query->where('sender_id', '=', Auth::guard('reporter')->user()->id)
                  ->orWhere('receiver_id', '=',  Auth::guard('reporter')->user()->id);
        })
        ->where(function ($query) {
            $query->where('sender_type', 'admin')
                  ->orWhere('receiver_type', 'admin');
        })
        ->where(function ($query) {
            $query->where('sender_type', 'reporter')
                  ->orWhere('receiver_type', 'reporter');
        })->get();


        if (count($checkedConversation) == 0) {


            $createdConversation= Conversation::create(['receiver_id'=>$receiverId,'sender_id'=>auth()->user('reporter')->id,'sender_type'=>'reporter','receiver_type'=>'admin','conversation_type'=>'reporter_admin']);
          /// conversation created

            $createdMessage= Message::create(['conversation_id'=>$createdConversation->id,'sender_id'=>auth('reporter')->user()->id,'receiver_id'=>$receiverId,'body'=>$this->message,'sender_type'=>'reporter','receiver_type'=>'admin']);


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


        $this->admins = Admin::all();

        return view('livewire.reporter.chat.admin-chat')->layout('layouts.reporter.app');
    }
}
