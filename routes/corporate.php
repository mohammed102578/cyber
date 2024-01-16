<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/










//corporate not  authenticated
Route::group(
    [
        'prefix' => 'corporate',
        'middleware'=>'guest:corporate',


    ], function(){
        $controller_path = 'App\Http\Controllers';


// authentication
Route::get('/login', $controller_path . '\corporate\authentications\LoginController@index')->name('corporate_login');
Route::post('/store_login', $controller_path . '\corporate\authentications\LoginController@login')->name('corporate_store_login');

Route::get('/register', $controller_path . '\corporate\authentications\RegisterController@index')->name('corporate_register');
Route::post('/store_register', $controller_path . '\corporate\authentications\RegisterController@register')->name('corporate_store_register');


Route::get('/forgot-password', $controller_path . '\corporate\authentications\ForgotPasswordController@forgot_password')->name('corporate_forgot_password');
Route::post('/check_emai', $controller_path . '\corporate\authentications\ForgotPasswordController@check_email')->name('corporate_check_email');

Route::get('/verification_code/{email}', $controller_path . '\corporate\authentications\ForgotPasswordController@verification_code')->name('corporate_verification_code');
Route::post('/check_verification_code', $controller_path . '\corporate\authentications\ForgotPasswordController@check_verification_code')->name('corporate_check_verification_code');

Route::post('/resend_code-password', $controller_path . '\corporate\authentications\ForgotPasswordController@resend')->name('corporate_resend_code_password');


Route::get('/reset-password', $controller_path . '\corporate\authentications\ForgotPasswordController@reset_password')->name('corporate_reset_password');
Route::post('/update_password', $controller_path . '\corporate\authentications\ForgotPasswordController@update_password')->name('corporate_update_password');


Route::get('/corporate_reload-captcha', $controller_path . '\corporate\authentications\RegisterController@reloadCaptcha')->name('corporate_capatch_reload');


});

//end corporate   authenticated




// start corporate _blocked and deleted
Route::group(
    [
        'prefix' => 'corporate',

    ], function(){
        $controller_path = 'App\Http\Controllers';

// deleted_corporate
Route::get('/corporate_deleted', $controller_path . '\corporate\authentications\LoginController@corporate_deleted')->name('corporate_deleted');

// activate_corporate
Route::get('/corporate_activate', $controller_path . '\corporate\authentications\LoginController@corporate_activate')->name('corporate_activate');

    });

//end corporate _blocked and deleted







//corporate   authenticated
Route::group(
    [
        'prefix' => 'corporate',
        'middleware'=>['auth:corporate','activate_corporate:corporate','corporate_verification:corporate','last_seen:corporate'],


    ], function(){
        $controller_path = 'App\Http\Controllers';
        $livewire='App\Http\Livewire\Corporate\Chat\\';


// logout
Route::get('/corporate_logout', $controller_path . '\corporate\authentications\LoginController@logout')->name('corporate_logout');

//dashboard
Route::get('/dashboard', $controller_path . '\corporate\DashboardController@dashboard')->name('corporate_dashboard');
//profile
Route::get('/profile', $controller_path . '\corporate\ProfileController@profile')->name('corporate_profile');
Route::patch('/update_profile', $controller_path . '\corporate\ProfileController@update_profile')->name('corporate_update_profile');

//setting
Route::get('/setting', $controller_path . '\corporate\ProfileController@setting')->name('corporate_setting');

//account
Route::get('/account', $controller_path . '\corporate\AccountController@index')->name('corporate_account');
Route::Post('/store_account', $controller_path . '\corporate\AccountController@update')->name('corporate_update_account');
Route::Post('/change_email', $controller_path . '\corporate\AccountController@change_email')->name('corporate_change_email');
Route::Post('/change_username', $controller_path . '\corporate\AccountController@change_username')->name('corporate_change_username');
Route::Post('/change_password', $controller_path . '\corporate\AccountController@change_password')->name('corporate_change_password');


//security
Route::get('/security', $controller_path . '\corporate\SecurityController@security')->name('corporate_security');
Route::delete('/delete_account', $controller_path . '\corporate\SecurityController@delete_account')->name('corporate_delete_account');


//FAQ
Route::get('/faq', $controller_path . '\corporate\GeneralController@faq')->name('corporate_faq');



//invoices
Route::get('/all_invoices', $controller_path . '\corporate\InvoiceController@all_invoices')->name('corporate_all_invoices');
Route::get('/invoices', $controller_path . '\corporate\InvoiceController@index')->name('corporate_invoice');
Route::get('/get_invoices', $controller_path . '\corporate\InvoiceController@get_invoices')->name('corporate_get_invoices');
Route::get('/create_invoice', $controller_path . '\corporate\InvoiceController@create')->name('corporate_create_invoice');
Route::post('/store_invoice', $controller_path . '\corporate\InvoiceController@store')->name('corporate_store_invoice');
Route::get('/edit_invoice', $controller_path . '\corporate\InvoiceController@edit')->name('corporate_edit_invoice');
Route::post('/update_invoice', $controller_path . '\corporate\InvoiceController@update')->name('corporate_update_invoice');



//Notification
Route::get('/notification', $controller_path . '\corporate\NotificationController@notification')->name('corporate_notification');
Route::get('/get_read_notification',$controller_path . '\corporate\NotificationController@get_read_notifications')->name('corporate_get_read_notification');
Route::get('/get_unread_notification',$controller_path . '\corporate\NotificationController@get_unread_notifications')->name('corporate_get_unread_notification');
Route::get('/get_all_notification',$controller_path . '\corporate\NotificationController@get_all_notifications')->name('corporate_get_all_notification');
Route::delete('/delete_notification', $controller_path . '\corporate\NotificationController@delete_notification')->name('delete_corporate_notification');
Route::post('/read_notification', $controller_path . '\corporate\NotificationController@read_notification')->name('read_corporate_notification');


//Connection
Route::post('/store_connection', $controller_path . '\corporate\ConnectionController@store')->name('store_corporate_connection');
Route::get('/delete_connection/{id}', $controller_path . '\corporate\ConnectionController@destroy')->name('delete_corporate_connection');




//program
Route::get('/programs', $controller_path . '\corporate\ProgramController@index')->name('corporate_programs');
Route::get('/add_program', $controller_path . '\corporate\ProgramController@create')->name('corporate_add_program');
Route::Post('/store_program', $controller_path . '\corporate\ProgramController@store')->name('corporate_store_program');
Route::get('/edit_program', $controller_path . '\corporate\ProgramController@edit')->name('corporate_edit_program');
Route::Post('/update_program', $controller_path . '\corporate\ProgramController@update')->name('corporate_update_program');
Route::Delete('/delete_program', $controller_path . '\corporate\ProgramController@destroy')->name('corporate_delete_program');
Route::get('/submit_program/{id}', $controller_path . '\corporate\ProgramController@submit')->name('corporate_submit_program');
Route::get('/setting_program/{id}', $controller_path . '\corporate\ProgramController@setting')->name('setting_program');
Route::Post('/program_requirement', $controller_path . '\corporate\ProgramController@program_requirement')->name('corporate_program_requirement');



//visibility program
//semi-private program
Route::get('/get_reporter_semi_private/{id}',  $controller_path . '\corporate\VisibilityProgramController@semi_private_get')->name('get_reporter_semi_private');
Route::post('/update_status_reporter_semi_private', $controller_path . '\corporate\VisibilityProgramController@status')->name('update_status_reporter_semi_private');
Route::Delete('/semi_private_program_delete',  $controller_path . '\corporate\VisibilityProgramController@semi_private_delete')->name('semi_private_program_delete');
//private program
Route::get('/get_reporter_private/{id}',  $controller_path . '\corporate\VisibilityProgramController@private_get')->name('get_reporter_private');
Route::Post('/private_program',  $controller_path . '\corporate\VisibilityProgramController@private_store')->name('private_program_store');
Route::Delete('/private_program_delete',  $controller_path . '\corporate\VisibilityProgramController@private_delete')->name('private_program_delete');
//blocking
Route::get('/get_reporter_blocking/{id}',  $controller_path . '\corporate\VisibilityProgramController@blocking_get')->name('get_reporter_blocking');
Route::Post('/blocking_program',  $controller_path . '\corporate\VisibilityProgramController@blocking_store')->name('blocking_program_store');
Route::Delete('/blocking_program_delete',  $controller_path . '\corporate\VisibilityProgramController@blocking_delete')->name('blocking_program_delete');

//report
Route::get('/get_program_reports/{id}',  $controller_path . '\corporate\VisibilityProgramController@get_reports')->name('get_program_reports');
Route::get('/setting_program/show_report/{id}', $controller_path . '\corporate\VisibilityProgramController@show_report')->name('show_program_report');
Route::post('/update_status_report', $controller_path . '\corporate\VisibilityProgramController@status_report')->name('update_status_corporate_report');
Route::get('/get_report_status', $controller_path . '\corporate\VisibilityProgramController@get_status')->name('get_status_corporate_report');




//all_reports_accept

Route::get('/corporate_accept_reports', $controller_path . '\corporate\AllReportsAcceptController@index')->name('corporate_reporter_accept_reports');
Route::get('/corporate_accept_reports_get', $controller_path . '\corporate\AllReportsAcceptController@accept_reports_get')->name('corporate_reporter_accept_reports_get');
//leaderboard

Route::match(array('GET','POST'),'/leaderboard', $controller_path . '\corporate\LeaderboardController@leaderboard')->name('corporate_leaderboard');

//chat
Route::get('/admin_chat',$livewire . AdminChat::class)->name('corporat_admin_chat');
Route::get('/chats',$livewire . Main::class)->name('corporate_chat');


//reward

Route::match(array('GET','POST'),'/reward', $controller_path . '\corporate\RewardController@reward')->name('corporate_reward');


 //vertical_json
 Route::get('/search-vertical', $controller_path . '\corporate\SearchJsonController@search_vertical')->name('corporate_search_vertical');


});


//corporate verification
Route::group(
    [
        'prefix' => 'corporate',
        'middleware'=>['auth:corporate','activate_corporate:corporate'],

    ], function(){
        $controller_path = 'App\Http\Controllers';


//corporate_verification_account
Route::get('/verification_account', $controller_path . '\corporate\VerificationController@index')->name('corporate_verification');
Route::post('/store_code', $controller_path . '\corporate\VerificationController@store')->name('corporate_store_verification');
Route::post('/resend_code', $controller_path . '\corporate\VerificationController@resend')->name('corporate_resend_code');





});

//end corporate   authenticated



