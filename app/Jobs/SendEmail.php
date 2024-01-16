<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $details;
    public function __construct($details)
    {
        $this->details=$details;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $data['title']=$this->details['title'];
        $data['body']=$this->details['body'];
        $data['email']=$this->details['email'];
        $data['name']=$this->details['name'];

        Mail::send('content.admin.include.email.send_email',['data'=>$data],function($message) use ($data){

         $message->to($data['email'],$data['name'])->subject($data['title']);
        });
    }


    public function failed($exception)
    {
        $exception->getMessage();
        // etc...
    }

}
