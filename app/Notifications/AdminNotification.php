<?php

namespace App\Notifications;

use App\Events\ReporterEventsNotification;
use App\Models\Admin\Admin;
use App\Models\Reporter\Reporter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminNotification extends Notification implements ShouldBroadcast
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
  
    public $notification;


   

    public function __construct($notification)
    {
        $this->notification = $notification;
       

    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['broadcast'];
    }

   

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return BroadcastMessage
     */
        public function toBroadcast($notifiable): BroadcastMessage
        {
            return new BroadcastMessage([
                'data' => "$this->notification"
            ]);
        }


 


}
