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
$controller_path = 'App\Http\Controllers';


//reporter
Route::group(
    [
        'prefix' => 'reporter',
        'middleware'=>'guest:corporate',


    ], function(){
        $controller_path = 'App\Http\Controllers';


// authentication
Route::get('/login', $controller_path . '\reporter\authentications\LoginController@index')->name('reporter_login');
Route::post('/store_login', $controller_path . '\reporter\authentications\LoginController@login')->name('reporter_store_login');


Route::get('/register', $controller_path . '\reporter\authentications\RegisterController@index')->name('reporter_register');
Route::post('/store-register', $controller_path . '\reporter\authentications\RegisterController@register')->name('reporter_store-register');

Route::get('/forgot-password', $controller_path . '\reporter\authentications\ForgotPasswordController@forgot_password')->name('reporter_forgot_password');
Route::post('/check_email_or_phone', $controller_path . '\reporter\authentications\ForgotPasswordController@check_email_or_phone')->name('reporter_check_email_or_phone');

Route::get('/verification_code/{phon_or_email}', $controller_path . '\reporter\authentications\ForgotPasswordController@verification_code')->name('reporter_verification_code');
Route::post('/check_verification_code', $controller_path . '\reporter\authentications\ForgotPasswordController@check_verification_code')->name('reporter_check_verification_code');

Route::post('/resend_code-password', $controller_path . '\reporter\authentications\ForgotPasswordController@resend')->name('reporter_resend_code_password');


Route::get('/reset-password', $controller_path . '\reporter\authentications\ForgotPasswordController@reset_password')->name('reporter_reset_password');
Route::post('/update_password', $controller_path . '\reporter\authentications\ForgotPasswordController@update_password')->name('reporter_update_password');


Route::get('/reporter_reload-captcha', $controller_path . '\reporter\authentications\RegisterController@reloadCaptcha')->name('reporter_capatch_reload');


});

//end reporter not authenticated


// start reporter _blocked and deleted
Route::group(
    [
        'prefix' => 'reporter',

    ], function(){
        $controller_path = 'App\Http\Controllers';

// deleted_reporter
Route::get('/reporter_deleted', $controller_path . '\reporter\authentications\LoginController@reporter_deleted')->name('reporter_deleted');

// activate_reporter
Route::get('/reporter_activate', $controller_path . '\reporter\authentications\LoginController@reporter_activate')->name('reporter_activate');

    });

//end reporter _blocked and deleted




//reporter   authenticated
Route::group(
    [
        'prefix' => 'reporter',
        'middleware'=>['auth:reporter','activate_reporter:reporter','reporter_verification:reporter','last_seen:reporter'],

    ], function(){
        $controller_path = 'App\Http\Controllers';
        $livewire='App\Http\Livewire\Reporter\Chat\\';


// logout
Route::get('/reporter_logout', $controller_path . '\reporter\authentications\LoginController@logout')->name('reporter_logout');

//dashboard
Route::get('/dashboard', $controller_path . '\reporter\DashboardController@dashboard')->name('reporter_dashboard');

//leaderboard

Route::match(array('GET','POST'),'/leaderboard', $controller_path . '\reporter\LeaderboardController@leaderboard')->name('reporter_leaderboard');

//Hacktivity
Route::match(array('GET','POST'),'/hacktivity', $controller_path . '\reporter\HacktivityController@hacktivity')->name('reporter_hacktivity');
Route::get('/load-hacktivity-more-data', $controller_path . '\reporter\HacktivityController@loadMoreData')->name('reporter_hacktivity_loadmore');


//reward

Route::match(array('GET','POST'),'/reward', $controller_path . '\reporter\RewardController@reward')->name('reporter_reward');



//profile
Route::get('/profile', $controller_path . '\reporter\ProfileController@profile')->name('reporter_profile');
Route::get('/reporter_profile/{id}', $controller_path . '\reporter\ProfileController@reporter_profile')->name('another_reporter_profile');
Route::patch('/update_profile', $controller_path . '\reporter\ProfileController@update_profile')->name('reporter_update_profile');

//setting
Route::get('/setting', $controller_path . '\reporter\ProfileController@setting')->name('reporter_setting');

//account
Route::get('/account', $controller_path . '\reporter\AccountController@index')->name('reporter_account');
Route::Post('/store_account', $controller_path . '\reporter\AccountController@update')->name('reporter_store_account');
Route::Post('/change_email', $controller_path . '\reporter\AccountController@change_email')->name('reporter_change_email');
Route::Post('/change_phone', $controller_path . '\reporter\AccountController@change_phone')->name('reporter_change_phone');
Route::Post('/change_password', $controller_path . '\reporter\AccountController@change_password')->name('reporter_change_password');


//security
Route::get('/security', $controller_path . '\reporter\SecurityController@security')->name('reporter_security');
Route::delete('/delete_account', $controller_path . '\reporter\SecurityController@delete_account')->name('reporter_delete_account');


//FAQ
Route::get('/faq', $controller_path . '\reporter\GeneralController@faq')->name('reporter_faq');




//invoices
Route::get('/all_invoices', $controller_path . '\reporter\InvoiceController@all_invoices')->name('reporter_all_invoices');
Route::get('/invoices', $controller_path . '\reporter\InvoiceController@index')->name('reporter_invoices');
Route::get('/get_invoices', $controller_path . '\reporter\InvoiceController@get_invoices')->name('reporter_get_invoices');


//paymentmethod
Route::post('/payment_method', $controller_path . '\reporter\PaymentMethodController@store')->name('payment_method_store');
Route::get('/payment_method_edit', $controller_path . '\reporter\PaymentMethodController@edit')->name('edit_reporter_payment_method');
Route::patch('/payment_method_update', $controller_path . '\reporter\PaymentMethodController@update')->name('update_reporter_payment_method');
Route::delete('/payment_method_delete', $controller_path . '\reporter\PaymentMethodController@destroy')->name('delete_reporter_payment_method');



//all_reports_accept

Route::get('/reporter_accept_reports', $controller_path . '\reporter\AllReportsAcceptController@index')->name('reporter_accepted_reports');
Route::get('/reporter_accept_reports_get', $controller_path . '\reporter\AllReportsAcceptController@get_accepted_reports')->name('reporter_accepted_reports_get');

//Notification
Route::get('/notification', $controller_path . '\reporter\NotificationController@notification')->name('reporter_notification');
Route::get('/get_read_notification',$controller_path . '\reporter\NotificationController@get_read_notifications')->name('reporter_get_read_notification');
Route::get('/get_unread_notification',$controller_path . '\reporter\NotificationController@get_unread_notifications')->name('reporter_get_unread_notification');
Route::get('/get_all_notification',$controller_path . '\reporter\NotificationController@get_all_notifications')->name('reporter_get_all_notification');
Route::delete('/delete_notification', $controller_path . '\reporter\NotificationController@delete_notification')->name('delete_reporter_notification');
Route::post('/read_notification', $controller_path . '\reporter\NotificationController@read_notification')->name('read_reporter_notification');



//Connection
Route::post('/store_connection', $controller_path . '\reporter\ConnectionController@store')->name('store_reporter_connection');
Route::get('/delete_connection/{id}', $controller_path . '\reporter\ConnectionController@destroy')->name('delete_reporter_connection');


//program
Route::get('/programs', $controller_path . '\reporter\ProgramController@index')->name('reporter_programs');
Route::get('/program/{id}', $controller_path . '\reporter\ProgramController@show')->name('reporter_program');
Route::post('/request_join', $controller_path . '\reporter\ProgramController@request_join')->name('reporter_programs_request_join');
Route::get('/load-more-data', $controller_path . '\reporter\ProgramController@loadMoreData')->name('reporter_program_loadmore');




//Report
Route::get('/reports', $controller_path . '\reporter\ReportController@index')->name('reporter_reports');
Route::get('/get_reports', $controller_path . '\reporter\ReportController@get_reports')->name('get_reporter_reports');
Route::get('/show_report/{id}', $controller_path . '\reporter\ReportController@show')->name('show_reporter_report');
Route::get('/report/{id}', $controller_path . '\reporter\ReportController@create')->name('reporter_report');
Route::post('/store_report', $controller_path . '\reporter\ReportController@store')->name('reporter_store_report');
Route::get('/edit_report/{id}', $controller_path . '\reporter\ReportController@edit')->name('edit_reporter_report');
Route::post('/update_report', $controller_path . '\reporter\ReportController@update')->name('reporter_update_report');
Route::post('/delete_report', $controller_path . '\reporter\ReportController@destroy')->name('delete_reporter_report');
Route::get('/belong_vulnerability/{id}', $controller_path . '\reporter\ReportController@belong_vulnerability')->name('belong_vulnerability');
Route::get('/belong_belong_vulnerability/{id}', $controller_path . '\reporter\ReportController@belong_belong_vulnerability')->name('belong_belong_vulnerability');






//Report_image
Route::get('/report_images/{id}', $controller_path . '\reporter\ReportImageController@images')->name('reporter_images');
Route::get('/get_images/{id}', $controller_path . '\reporter\ReportImageController@get_images')->name('get_report_images');
Route::post('/add_image', $controller_path . '\reporter\ReportImageController@add_image')->name('reporter_add_image');
Route::post('/delete_image', $controller_path . '\reporter\ReportImageController@destroy')->name('delete_report_image');




//chat
Route::get('/reporters_chat',$livewire . ReportersChat::class)->name('reporter_reporters_chat');
Route::get('/admin_chat',$livewire . AdminChat::class)->name('reporter_admin_chat');

Route::get('/chats',$livewire . Main::class)->name('reporter_chat');



//reporter_chat
Route::post('/create_reportchat', $controller_path . '\reporter\ReportChatController@store')->name('create_reporter_report_chat');



 //vertical_json
 Route::get('/search-vertical', $controller_path . '\reporter\SearchJsonController@search_vertical')->name('reporter_search_vertical');




});

//reporter verification
Route::group(
    [
        'prefix' => 'reporter',
        'middleware'=>['auth:reporter','activate_reporter:reporter'],

    ], function(){
        $controller_path = 'App\Http\Controllers';


//reporter_verification_account
Route::get('/verification_account', $controller_path . '\reporter\VerificationController@index')->name('reporter_verification');
Route::post('/store_code', $controller_path . '\reporter\VerificationController@store')->name('reporter_store_verification');
Route::post('/resend_code', $controller_path . '\reporter\VerificationController@resend')->name('reporter_resend_code');





});
