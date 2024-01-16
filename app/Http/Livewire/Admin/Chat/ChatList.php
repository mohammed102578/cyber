<?php

namespace App\Http\Livewire\Admin\Chat;

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


     public function chatUserSelected($conversation_id,$user_id)
     {

      $this->selectedConversation= Conversation::find($conversation_id);
      //dd($this->selectedConversation);



      if($this->selectedConversation->sender_type =='reporter' || $this->selectedConversation->receiver_type=='reporter'){

        $receiverInstance= Reporter::find($user_id);

    }else{
        $receiverInstance= Corporate::find($user_id);

    }


            $this->emitTo('admin.chat.chatbox','loadConversation', $this->selectedConversation,$receiverInstance);
            $this->emitTo('admin.chat.send-message','updateSendMessage',$this->selectedConversation,$receiverInstance);

         # code...
     }


    // public function getChatUserInstance(Conversation $conversation, $user_id)
    // {

    //    // dd($request);
    //     //get selected conversation

    //     if ($conversation->conversation_type == 'admin_reporter') {
    //         $this->receiverInstance = Reporter::firstWhere('id', $conversation->receiver_id);
    //         # code...
    //     } elseif($conversation->conversation_type == 'admin_corporate') {
    //         $this->receiverInstance = Corporate::firstWhere('id', $conversation->receiver_id);
    //     }elseif($conversation->conversation_type == 'corporate_admin') {
    //         $this->receiverInstance = Corporate::firstWhere('id', $conversation->sender_id);
    //     }elseif($conversation->conversation_type == 'reporter_admin') {
    //         $this->receiverInstance = Reporter::firstWhere('id', $conversation->sender_id);
    //     }

    //     if (isset($request)) {

    //         return $this->receiverInstance->$request;

    //         # code...
    //     }
    // }





    public function mount()
    {


        $sender_admin_to_corporate = DB::table('conversations')
        ->where('conversations.receiver_type', 'corporate')
        ->where('conversations.sender_type', 'admin')
        ->orderBy('conversations.id', 'DESC')
        ->leftJoin('corporates', 'corporates.id', '=', 'conversations.receiver_id')
        ->leftJoin('messages', function ($join) {
            $join->on('conversations.id', '=', 'messages.conversation_id')
                 ->whereRaw('messages.created_at = (SELECT MAX(created_at) FROM messages WHERE conversation_id = conversations.id)');
        })
     ->select('conversations.*', 'corporates.company_name as name','corporates.image', 'corporates.last_seen_at', 'corporates.id as user_id', 'corporates.email', 'messages.body as message', 'messages.created_at as message_created_at')
     ->get();

      $sender_corporate = DB::table('conversations')
     ->where('conversations.sender_type', 'corporate')
     ->where('conversations.receiver_type', 'admin')
     ->orderBy('conversations.id', 'DESC')
     ->leftJoin('corporates', 'corporates.id', '=', 'conversations.sender_id')
     ->leftJoin('messages', function ($join) {
        $join->on('conversations.id', '=', 'messages.conversation_id')
             ->whereRaw('messages.created_at = (SELECT MAX(created_at) FROM messages WHERE conversation_id = conversations.id)');
    })
     ->select('conversations.*', 'corporates.company_name as name','corporates.image', 'corporates.last_seen_at', 'corporates.id as user_id', 'corporates.email', 'messages.body as message', 'messages.created_at as message_created_at')
     ->get();



     $sender_admin_to_reporter = DB::table('conversations')
     ->where('conversations.receiver_type', 'reporter')
     ->where('conversations.sender_type', 'admin')
     ->orderBy('conversations.id', 'DESC')
     ->leftJoin('reporters', 'reporters.id', '=', 'conversations.receiver_id')
     ->leftJoin('messages', function ($join) {
        $join->on('conversations.id', '=', 'messages.conversation_id')
             ->whereRaw('messages.created_at = (SELECT MAX(created_at) FROM messages WHERE conversation_id = conversations.id)');
    })
     ->select('conversations.*', 'reporters.first_name as name', 'reporters.image', 'reporters.last_seen_at', 'reporters.id as user_id', 'reporters.email',  'messages.body as message', 'messages.created_at as message_created_at')
     ->get();

      $sender_reporter = DB::table('conversations')
     ->where('conversations.sender_type', 'reporter')
     ->where('conversations.receiver_type', 'admin')
     ->orderBy('conversations.id', 'DESC')
     ->leftJoin('reporters', 'reporters.id', '=', 'conversations.sender_id')
     ->leftJoin('messages', function ($join) {
        $join->on('conversations.id', '=', 'messages.conversation_id')
             ->whereRaw('messages.created_at = (SELECT MAX(created_at) FROM messages WHERE conversation_id = conversations.id)');
    })
     ->select('conversations.*', 'reporters.first_name as name', 'reporters.image', 'reporters.last_seen_at', 'reporters.id as user_id', 'reporters.email',  'messages.body as message', 'messages.created_at as message_created_at')
     ->get();





 //merge_to_object semi_private and  public in one object all
  $conversations = $sender_corporate->merge($sender_admin_to_corporate)
 ->merge($sender_admin_to_reporter)->merge($sender_reporter);

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


        # code...
    }

    public function render()
    {

        return view('livewire.admin.chat.chat-list')->layout('layouts.admin.app');
    }
}
