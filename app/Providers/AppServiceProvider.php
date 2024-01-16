<?php

namespace App\Providers;

use App\Models\Admin\Admin;
use App\Models\Admin\PlatformSetting;
use App\Models\Corporate\Corporate;
use App\Models\Message;
use App\Models\Reporter\Reporter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {





      $verticalMenuCorporate = file_get_contents(base_path('resources/menu/verticalMenuCorporate.json'));
      $verticalMenuCorporateData = json_decode($verticalMenuCorporate);

      $verticalMenuReporter = file_get_contents(base_path('resources/menu/verticalMenuReporter.json'));
      $verticalMenuReporterData = json_decode($verticalMenuReporter);


      $verticalMenuAdmin = file_get_contents(base_path('resources/menu/verticalMenuAdmin.json'));
      $verticalMenuAdminData = json_decode($verticalMenuAdmin);

      // Share all menuData to all the views

      View::share(['menuDataCorporate'=>[$verticalMenuCorporateData]]);



//retutn corporate notification
View::composer('layouts.corporate.sections.navbar.navbar',function($view){


  $corporate=Corporate::find(Auth::guard('corporate')->user()->id);
  $corporat_notifications=$corporate->notificationable()->where('read',0)->orderBy('id','desc')->get();



   $corporate_messages =DB::table('messages')
  ->where('receiver_id',$corporate->id)->where('receiver_type','corporate')
  ->where('read',0)->where('sender_type','admin')->orderBy('id','desc')
  ->leftJoin('admins', 'admins.id', '=', 'messages.sender_id')
  ->select('messages.*', 'admins.name as name','admins.image')
  ->get();



  $view->with(['corporat_notifications'=>$corporat_notifications,'corporate_messages'=>$corporate_messages]);
});



//retutn reporter notification
View::composer('layouts.reporter.sections.navbar.navbar',function($view){

  $reporter=Reporter::find(Auth::guard('reporter')->user()->id);
  $reporter_notifications=$reporter->notificationable()->where('read',0)->orderBy('id','desc')->get();


  $retporter_message =DB::table('messages')
  ->where('receiver_id',$reporter->id)->where('receiver_type','reporter')
  ->where('read',0)->where('sender_type','reporter')->orderBy('id','desc')
  ->leftJoin('reporters', 'reporters.id', '=', 'messages.sender_id')
  ->select('messages.*', 'reporters.first_name as name','reporters.image')
  ->get();

   $admin_message =DB::table('messages')
  ->where('receiver_id',$reporter->id)->where('receiver_type','reporter')
  ->where('read',0)->where('sender_type','admin')->orderBy('id','desc')
  ->leftJoin('admins', 'admins.id', '=', 'messages.sender_id')
  ->select('messages.*', 'admins.name as name','admins.image')
  ->get();



  $reporter_messages= $admin_message->merge($retporter_message);


  $view->with(['reporter_notifications'=>$reporter_notifications,'reporter_messages'=>$reporter_messages]);
});



//retutn admin notification
View::composer('layouts.admin.sections.navbar.navbar',function($view){


  $admin=Admin::find(Auth::guard('admin')->user()->id);
  $admin_notifications=$admin->notificationable()->where('read',0)->orderBy('id','desc')->get();

  $retporter_message =DB::table('messages')
  ->where('receiver_id',$admin->id)->where('receiver_type','admin')
  ->where('read',0)->where('sender_type','reporter')->orderBy('id','desc')
  ->leftJoin('reporters', 'reporters.id', '=', 'messages.sender_id')
  ->select('messages.*', 'reporters.first_name as name','reporters.image')
  ->get();

   $corporate_message =DB::table('messages')
  ->where('receiver_id',$admin->id)->where('receiver_type','admin')
  ->where('read',0)->where('sender_type','corporate')->orderBy('id','desc')
  ->leftJoin('corporates', 'corporates.id', '=', 'messages.sender_id')
  ->select('messages.*', 'corporates.company_name as name','corporates.image')
  ->get();
  $admin_messages=$retporter_message->merge($corporate_message);

  $view->with(['admin_notifications'=>$admin_notifications,'admin_messages'=>$admin_messages]);
});







    }
}
