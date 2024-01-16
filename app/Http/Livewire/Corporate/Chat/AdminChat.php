<?php

namespace App\Http\Livewire\Corporate\Chat;

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
            $query->where('sender_id', '=', Auth::guard('corporate')->user()->id)
                  ->orWhere('receiver_id', '=',  Auth::guard('corporate')->user()->id);
        })
        ->where(function ($query) {
            $query->where('sender_type', 'admin')
                  ->orWhere('receiver_type', 'admin');
        })
        ->where(function ($query) {
            $query->where('sender_type', 'corporate')
                  ->orWhere('receiver_type', 'corporate');
        })->get();
       // dd(count($checkedConversation));

        if (count($checkedConversation) == 0) {


            $createdConversation= Conversation::create(['receiver_id'=>$receiverId,'sender_id'=>auth()->user('corporate')->id,'sender_type'=>'corporate','receiver_type'=>'admin','conversation_type'=>'corporate_admin']);
          /// conversation created

            $createdMessage= Message::create(['conversation_id'=>$createdConversation->id,'sender_id'=>auth('corporate')->user()->id,'receiver_id'=>$receiverId,'body'=>$this->message,'sender_type'=>'corporate','receiver_type'=>'corporate']);


        $createdConversation->last_time_message= $createdMessage->created_at;
        $createdConversation->save();


return redirect()->route('corporate_chat');



        } else if (count($checkedConversation) >= 1) {

            return redirect()->route('corporate_chat')->with('info','Already have Conversation');

        }
        # code...
    }
    public function render()
    {


        $this->admins = Admin::all();

        return view('livewire.corporate.chat.admin-chat')->layout('layouts.corporate.app');
    }
}
