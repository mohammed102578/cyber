<?php

use App\Models\Admin\Admin;
use App\Models\Corporate\Corporate;
use App\Models\Notification;
use App\Models\Reporter\Reporter;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/
Broadcast::channel('App.Models.Reporter.Reporter.{id}', function ($reporter, $id) {
    return (int) $reporter->id === (int) $id;
},['guards' => ['reporter']]);


Broadcast::channel('App.Models.Corporate.Corporate.{id}', function ($corporate, $id) {
    return (int) $corporate->id === (int) $id;
},['guards' => ['corporate']]);


Broadcast::channel('App.Models.Admin.Admin.{id}', function ($admin, $id) {
    return (int) $admin->id === (int) $id;
},['guards' => ['admin']]);


//chat
Broadcast::channel('reporter_chat.{id}', function ($reporter, $id) {
    return (int) $reporter->id === (int) $id;
},['guards' => ['reporter']]);


Broadcast::channel('admin_chat.{id}', function ($admin, $id) {
    return (int) $admin->id === (int) $id;
},['guards' => ['admin']]);


Broadcast::channel('corporate_chat.{id}', function ($corporate, $id) {
    return (int) $corporate->id === (int) $id;
},['guards' => ['corporate']]);


//report_chat
Broadcast::channel('reporter_report_chat.{id}', function ($reporter, $id) {
    return (int) $reporter->id === (int) $id;
},['guards' => ['reporter']]);


Broadcast::channel('admin_report_chat.{id}', function ($admin, $id) {
    return (int) $admin->id === (int) $id;
},['guards' => ['admin']]);



//notification message
Broadcast::channel('reporter_notification_message.{id}', function ($reporter, $id) {
    return (int) $reporter->id === (int) $id;
},['guards' => ['reporter']]);


Broadcast::channel('admin_notification_message.{id}', function ($admin, $id) {
    return (int) $admin->id === (int) $id;
},['guards' => ['admin']]);


Broadcast::channel('corporate_notification_message.{id}', function ($corporate, $id) {
    return (int) $corporate->id === (int) $id;
},['guards' => ['corporate']]);
