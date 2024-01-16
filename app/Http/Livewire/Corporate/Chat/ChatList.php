<?php

namespace App\Http\Livewire\Corporate\Chat;

use App\Models\Admin\Admin;
use App\Models\Conversation;
use App\Models\Corporate\Corporate;
use App\Models\Reporter\Reporter;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ChatList extends Component
{


    public $auth_id;
    public $conversations;
    public $receiverInstance;
    public $name;
    public $selectedConversation;
    public $conversation_id;

  protected $listeners= ['chatUserSelected','refresh'=>'$refresh','resetComponent'];



  public function resetComponent()
  {

$this->selectedConversation= null;
$this->receiverInstance= null;

      # code...
  }


     public function chatUserSelected(Conversation $conversation,$receiverId)
     {

     // dd($conversation,$receiverId);
      $this->selectedConversation= $conversation;
      $this->conversation_id=$this->selectedConversation->id;
      if($conversation->sender_type=='admin' || $conversation->receiver_type=='admin'){

        $receiverInstance= Admin::find($receiverId);

    }


            $this->emitTo('corporate.chat.chatbox','loadConversation', $this->selectedConversation,$receiverInstance);
            $this->emitTo('corporate.chat.send-message','updateSendMessage',$this->selectedConversation,$receiverInstance);

         # code...
     }
    public function getChatUserInstance(Conversation $conversation, $request)
    {
        # code...
        //get selected conversation

        if ($conversation->conversation_type == 'corporate_admin') {
            $this->receiverInstance = Admin::firstWhere('id', $conversation->receiver_id);
            # code...
        } elseif($conversation->conversation_type == 'admin_corporate') {
            $this->receiverInstance = Admin::firstWhere('id', $conversation->sender_id);
        }

       // dd($this->receiverInstance);
        if (isset($request)) {

            return $this->receiverInstance->$request;

            # code...
        }
    }





    public function mount()
    {

        $this->auth_id = auth()->user('corporate')->id;



        $sender_admin_to_corporate = DB::table('conversations')
        ->where('conversations.receiver_type', 'corporate')
        ->where('conversations.receiver_id', $this->auth_id)
        ->where('conversations.sender_type', 'admin')
        ->orderBy('conversations.id', 'DESC')
        ->leftJoin('admins', 'admins.id', '=', 'conversations.sender_id')
        ->leftJoin('messages', function ($join) {
            $join->on('conversations.id', '=', 'messages.conversation_id')
                 ->whereRaw('messages.created_at = (SELECT MAX(created_at) FROM messages WHERE conversation_id = conversations.id)');
        })
     ->select('conversations.*', 'admins.name as name','admins.image', 'admins.last_seen_at', 'admins.id as user_id', 'admins.email', 'messages.body as message', 'messages.created_at as message_created_at')
     ->get();

      $sender_corporate = DB::table('conversations')
      ->where('conversations.sender_id', $this->auth_id)
     ->where('conversations.sender_type', 'corporate')
     ->where('conversations.receiver_type', 'admin')
     ->orderBy('conversations.id', 'DESC')
     ->leftJoin('admins', 'admins.id', '=', 'conversations.receiver_id')
     ->leftJoin('messages', function ($join) {
        $join->on('conversations.id', '=', 'messages.conversation_id')
             ->whereRaw('messages.created_at = (SELECT MAX(created_at) FROM messages WHERE conversation_id = conversations.id)');
    })
     ->select('conversations.*', 'admins.name as name','admins.image', 'admins.last_seen_at', 'admins.id as user_id', 'admins.email', 'messages.body as message', 'messages.created_at as message_created_at')
     ->get();

    //merge_to_object semi_private and  public in one object all
    $conversations = $sender_corporate->merge($sender_admin_to_corporate);

    $count= count($conversations);



    // Sort the data by ID
    for ($i = 0; $i < $count; $i++) {
    for ($j = $i + 1; $j < count($conversations); $j++) {
        if ($conversations[$i]->last_time_message < $conversations[$j]->last_time_message) {
            // Swap the elements
            $temp = $conversations[$i];
            $conversations[$i] = $conversations[$j];
            $conversations[$j] = $temp;
        }
    }
    }

    $new=[];
    foreach ($conversations as $conversation) {
    $new[]=  ['id'=>$conversation->id,'sender_id'=>$conversation->sender_id,'last_time_message'=>$conversation->last_time_message,'sender_type'=>$conversation->sender_type,'receiver_type'=>$conversation->receiver_type,
    'conversation_type'=>$conversation->conversation_type,'created_at'=>$conversation->created_at,'updated_at'=>$conversation->updated_at,'name'=>$conversation->name,'email'=>$conversation->email,
    'image'=>$conversation->image,'last_seen_at'=>$conversation->last_seen_at,'user_id'=>$conversation->user_id,'message'=>$conversation->message,'message_created_at'=>$conversation->message_created_at];
    }

$this->conversations= $new;




//dd($this->conversations);

        # code...
    }

    public function render()
    {

        return view('livewire.corporate.chat.chat-list')->layout('layouts.corporate.app');
    }
}
