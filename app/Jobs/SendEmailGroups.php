<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailGroups implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $email_data;
    public $title;
    public $body;
    public function __construct($email_data,$title,$body)
    {
        $this->email_data=$email_data;
        $this->title=$title;
        $this->body=$body;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        foreach($this->email_data as $send_data){
            if(isset($send_data->company_name)){
                $name=$send_data->company_name;

            }else{
                $name=$send_data->first_name." ".$send_data->last_name;
            }
            $data=['title'=>$this->title,'body'=>$this->body,'email'=>
            $send_data->email,'name'=> $name];

            Mail::send('content.admin.include.email.send_email',['data'=>$data],function($message) use ($data){

                $message->to($data['email'],$data['name'])->subject($data['title']);
            });

    }

    }


    public function failed($exception)
    {
        $exception->getMessage();
        // etc...
    }

}
