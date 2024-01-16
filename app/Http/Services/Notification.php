<?php
 namespace App\Http\Services;

use App\Models\Admin\Admin;
use App\Models\Corporate\Corporate;
use App\Models\Notification as ModelsNotification;
use App\Models\Reporter\Reporter;
use App\Notifications\AdminNotification;
use App\Notifications\CorporateNotification;
use App\Notifications\ReporterNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;

class Notification
{

   // ...

   public function sendAdminNotification($title,$body,$link,$link_parameter,$guard): void
   {
      $admin=Admin::find(202020);

      if($guard==null){
         $sender_image=$admin->image;

      }else{
         $sender_image=Auth::guard($guard)->user()->image;
      }
    $notification=['title'=>$title,'body'=>$body,'link'=>$link,'link_parameter'=>$link_parameter,'notificationable_id'=>
    202020,'notificationable_type'=>'App\Models\Admin\Admin','sender_image'=>$sender_image];
    $create=ModelsNotification::create($notification);
    $notification=ModelsNotification::find($create->id);
    $notification->time=$notification->created_at->diffForHumans();
    $notification->admin_image=$sender_image;
    $admin->notify((new AdminNotification($notification)));
   }




   public function sendReporterNotification($title,$body,$link,$link_parameter,$reporter_id,$guard)
   {
    $notification=['title'=>$title,'body'=>$body,'link'=>$link,'link_parameter'=>$link_parameter,'notificationable_id'=>
    $reporter_id,'notificationable_type'=>'App\Models\Reporter\Reporter', 'sender_image'=>Auth::guard($guard)->user()->image];
    $create=ModelsNotification::create($notification);
    $notification=ModelsNotification::find($create->id);
    $reporter=Reporter::find($reporter_id);
    $notification->time=$notification->created_at->diffForHumans();
    $notification->reporter_image=Auth::guard($guard)->user()->image;
    $reporter->notify((new ReporterNotification($notification)));
   }



   public function sendCorporateNotification($title,$body,$link,$link_parameter,$corporate_id,$guard)
   {
    $notification=['title'=>$title,'body'=>$body,'link'=>$link,'link_parameter'=>$link_parameter,'notificationable_id'=>
    $corporate_id,'notificationable_type'=>'App\Models\Corporate\Corporate','sender_image'=>Auth::guard($guard)->user()->image];
    $create=ModelsNotification::create($notification);
    $notification=ModelsNotification::find($create->id);
    $corporate=Corporate::find($corporate_id);
    $notification->time=$notification->created_at->diffForHumans();
    $notification->corporate_image=Auth::guard($guard)->user()->image;
    $corporate->notify((new CorporateNotification($notification)));
   }


   }
