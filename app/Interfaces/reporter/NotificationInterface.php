<?php

namespace App\Interfaces\reporter;


interface  NotificationInterface
{



public function notification();


public function get_read_notifications($request);
 


public function get_unread_notifications($request);



public function get_all_notifications($request);



public function read_notification($request);


public function delete_notification($request);




}
