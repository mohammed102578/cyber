<?php

namespace App\Http\Controllers\reporter;

use App\Http\Controllers\Controller;
use App\Repository\reporter\NotificationRepository;
use Illuminate\Http\Request;


class  NotificationController extends Controller
{
    protected $notification;
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
    return $this->notification->get_read_notifications( $request);

}


public function get_unread_notifications(Request $request)
{
    return $this->notification->get_unread_notifications( $request);

}



public function get_all_notifications(Request $request)
{
    return $this->notification->get_all_notifications( $request);

}


public function read_notification(Request $request)
{
    return $this->notification->read_notification( $request);

}


public function delete_notification(Request $request)

{
    return $this->notification->delete_notification( $request);

}



}
