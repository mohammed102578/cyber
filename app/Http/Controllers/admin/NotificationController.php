<?php

namespace App\Http\Controllers\admin;

use App\Events\CorporateEventsNotification;
use App\Events\ReporterEventsNotification;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\NotificationRequest;
use App\Models\Activity;
use Illuminate\Http\Request;
use App\Models\Admin\Admin;
use App\Models\Corporate\Corporate;
use App\Models\Notification;
use App\Models\Reporter\Reporter;
use App\Notifications\CorporateNotification;
use App\Notifications\ReporterNotification;
use App\Repository\admin\NotificationRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class  NotificationController extends Controller
{

    public $notification;
    public function __construct(NotificationRepository $notification)
    {
        $this->notification = $notification;
    }

public function notification()

{ 
   return $this->notification->notification();
}


public function get_read_notifications(Request $request)
{
    return $this->notification->get_read_notifications($request);

}


public function get_unread_notifications(Request $request)
{
    return $this->notification->get_unread_notifications($request);

}



public function get_all_notifications(Request $request)
{
    return $this->notification->get_all_notifications($request);

}


public function read_notification(Request $request)

{
    return $this->notification->read_notification($request);

}



public function store(NotificationRequest $request)
{
    return $this->notification->store($request);

}



public function delete_notification(Request $request)

{
    return $this->notification->delete_notification($request);

}



}
