<?php

use Illuminate\Support\Facades\Route;



$controller_path = 'App\Http\Controllers';


//admin
Route::group(
    [
        'prefix' => 'admin',
        'middleware'=>'guest:admin',


    ], function(){
        $controller_path = 'App\Http\Controllers';


// authentication
Route::get('/login', $controller_path . '\admin\authentications\LoginController@index')->name('admin_login');
Route::post('/store_login', $controller_path . '\admin\authentications\LoginController@login')->name('admin_store_login');



});

//end admin






//admin   authenticated
Route::group(
    [
        'prefix' => 'admin',
        'middleware'=>['auth:admin','last_seen:admin']

    ], function(){
        $controller_path = 'App\Http\Controllers';

        $livewire='App\Http\Livewire\Admin\Chat\\';
// authentication
Route::get('/admin_logout', $controller_path . '\admin\authentications\LoginController@logout')->name('admin_logout');


//dashboard
Route::get('/dashboard', $controller_path . '\admin\DashboardController@dashboard')->name('admin_dashboard');




//profile
Route::get('/profile', $controller_path . '\admin\ProfileController@profile')->name('admin_profile');
Route::patch('/update_profile', $controller_path . '\admin\ProfileController@update_profile')->name('admin_update_profile');
Route::get('/reporter_profile/{id}', $controller_path . '\admin\ProfileController@reporter_profile')->name('admin_reporter_profile');
Route::get('/corporate_profile/{id}', $controller_path . '\admin\ProfileController@corporate_profile')->name('admin_corporate_profile');

//setting
Route::get('/setting', $controller_path . '\admin\ProfileController@setting')->name('admin_setting');

//account & security
Route::get('/account', $controller_path . '\admin\AccountController@account')->name('admin_account');
Route::get('/security', $controller_path . '\admin\AccountController@security')->name('admin_security');
Route::patch('/update_account', $controller_path . '\admin\AccountController@update')->name('admin_update_account');
Route::patch('/change_email', $controller_path . '\admin\AccountController@change_email')->name('admin_change_email');
Route::patch('/change_phone', $controller_path . '\admin\AccountController@change_phone')->name('admin_change_phone');
Route::patch('/change_password', $controller_path . '\admin\AccountController@change_password')->name('admin_change_password');



//corporate
Route::get('/corporates', $controller_path . '\admin\CorporateController@index')->name('admin_corporates');
Route::get('/get_corporates', $controller_path . '\admin\CorporateController@get_corporates')->name('get_admin_corporates');
Route::post('/soft_delete_corporate', $controller_path . '\admin\CorporateController@soft_delete')->name('soft_delete_admin_corporate');
Route::post('/block_corporate', $controller_path . '\admin\CorporateController@block')->name('block_admin_corporate');
Route::get('/edit_corporate', $controller_path . '\admin\CorporateController@edit')->name('edit_admin_corporate');
Route::post('/update_corporate', $controller_path . '\admin\CorporateController@update')->name('update_admin_corporate');

#soft_delete
Route::get('/trash_corporates', $controller_path . '\admin\CorporateController@trash_corporates')->name('admin_trash_corporates');
Route::get('/get_trash_corporates', $controller_path . '\admin\CorporateController@get_trash_corporates')->name('get_admin_trash_corporates');
Route::post('/delete_corporate', $controller_path . '\admin\CorporateController@destroy')->name('delete_admin_corporate');
Route::post('/restore_corporate', $controller_path . '\admin\CorporateController@restore')->name('restore_admin_corporate');






//reporter
Route::get('/reporters', $controller_path . '\admin\ReporterController@index')->name('admin_reporters');
Route::get('/get_reporters', $controller_path . '\admin\ReporterController@get_reporters')->name('get_admin_reporters');
Route::post('/soft_delete_reporter', $controller_path . '\admin\ReporterController@soft_delete')->name('soft_delete_admin_reporter');
Route::post('/block_reporter', $controller_path . '\admin\ReporterController@block')->name('block_admin_reporter');
Route::get('/edit_reporter', $controller_path . '\admin\ReporterController@edit')->name('edit_admin_reporter');
Route::post('/update_reporter', $controller_path . '\admin\ReporterController@update')->name('update_admin_reporter');
#soft_delete
Route::get('/trash_reporters', $controller_path . '\admin\ReporterController@trash_reporters')->name('admin_trash_reporters');
Route::get('/get_trash_reporters', $controller_path . '\admin\ReporterController@get_trash_reporters')->name('get_admin_trash_reporters');
Route::post('/delete_reporter', $controller_path . '\admin\ReporterController@destroy')->name('delete_admin_reporter');
Route::post('/restore_reporter', $controller_path . '\admin\ReporterController@restore')->name('restore_admin_reporter');







//reports
Route::get('/reports', $controller_path . '\admin\ReportController@reports')->name('admin_reports');
Route::get('/show_report/{id}', $controller_path . '\admin\ReportController@show_report')->name('show_admin_report');
Route::get('/get_reports', $controller_path . '\admin\ReportController@get_reports')->name('get_admin_reports');
Route::post('/delete_report', $controller_path . '\admin\ReportController@destroy')->name('delete_admin_report');
Route::get('/edit_report', $controller_path . '\admin\ReportController@edit')->name('edit_admin_report');
Route::post('/update_report', $controller_path . '\admin\ReportController@update')->name('update_admin_report');
Route::patch('/update_hacktivity_report', $controller_path . '\admin\ReportController@hacktivity')->name('update_hacktivity_admin_report');
Route::patch('/update_status_report', $controller_path . '\admin\ReportController@status')->name('update_status_admin_report');
Route::get('/get_report_status', $controller_path . '\admin\ReportController@get_status')->name('get_status_admin_report');





//email
Route::get('/emails', $controller_path . '\admin\E_mailController@email')->name('admin_email');
Route::get('/get_corporate_emails', $controller_path . '\admin\E_mailController@get_corporate_emails')->name('admin_get_email_corporate');
Route::get('/get_reporter_emails', $controller_path . '\admin\E_mailController@get_reporter_emails')->name('admin_get_email_reporter');
Route::get('/get_show_email', $controller_path . '\admin\E_mailController@show_email')->name('admin_show_email');
Route::post('/store_emails', $controller_path . '\admin\E_mailController@store')->name('admin_store_email');
Route::delete('/delete_email', $controller_path . '\admin\E_mailController@destroy')->name('admin_delete_email');






//programs
Route::get('/programs', $controller_path . '\admin\ProgramController@index')->name('admin_programs');
Route::get('/get_programs', $controller_path . '\admin\ProgramController@get_program')->name('get_admin_programs');
Route::post('/delete_program', $controller_path . '\admin\ProgramController@destroy')->name('delete_admin_program');
Route::patch('/update_status_program', $controller_path . '\admin\ProgramController@status')->name('update_status_admin_program');
Route::get('/get_program_status', $controller_path . '\admin\ProgramController@get_status')->name('get_status_admin_program');
Route::get('/show_program/{id}', $controller_path . '\admin\ProgramController@show')->name('admin_show_program');
Route::get('/edit_program/{id}', $controller_path . '\admin\ProgramController@edit')->name('admin_edit_program');
Route::Post('/update_program', $controller_path . '\admin\ProgramController@update')->name('admin_update_program');
Route::get('/submit_program/{id}', $controller_path . '\admin\ProgramController@submit')->name('admin_submit_program');
Route::get('/unsubmit_programs', $controller_path . '\admin\ProgramController@unsubmit_program')->name('admin_unsubmit_programs');
Route::get('/get_unsubmit_programs', $controller_path . '\admin\ProgramController@get_unsubmit_program')->name('get_admin_unsubmit_programs');






//reporter_invoices
Route::get('/all_invoices', $controller_path . '\admin\ReporterInvoiceController@all_invoices')->name('admin_all_invoices');
Route::get('/paid_invoices', $controller_path . '\admin\ReporterInvoiceController@index')->name('admin_paid_invoices');
Route::get('/get_paid_invoices', $controller_path . '\admin\ReporterInvoiceController@get_paid_invoices')->name('admin_get_paid_invoices');
Route::get('/create_invoice', $controller_path . '\admin\ReporterInvoiceController@create')->name('admin_create_invoice');
Route::post('/store_invoice', $controller_path . '\admin\ReporterInvoiceController@store')->name('admin_store_invoice');
Route::get('/edit_invoice', $controller_path . '\admin\ReporterInvoiceController@edit')->name('admin_edit_invoice');
Route::post('/update_invoice', $controller_path . '\admin\ReporterInvoiceController@update')->name('admin_update_invoice');
Route::post('/delete_invoice', $controller_path . '\admin\ReporterInvoiceController@destroy')->name('admin_delete_invoice');
Route::post('/print_invoice', $controller_path . '\admin\ReporterInvoiceController@print')->name('admin_print_invoice');

//leaderboard

Route::match(array('GET','POST'),'/leaderboard', $controller_path . '\admin\LeaderboardController@leaderboard')->name('admin_leaderboard');


//corporate_invoices
Route::get('/corporate_paid_invoices', $controller_path . '\admin\CorporateInvoiceController@index')->name('admin_corporate_paid_invoices');
Route::get('/corporate_get_paid_invoices', $controller_path . '\admin\CorporateInvoiceController@get_paid_invoices')->name('admin_corporate_get_paid_invoices');
Route::post('/corporate_check_invoice', $controller_path . '\admin\CorporateInvoiceController@check_invoice')->name('admin_corporate_check_invoice');
Route::post('/corporate_delete_invoice', $controller_path . '\admin\CorporateInvoiceController@destroy')->name('admin_corporate_delete_invoice');
Route::post('/corporate_print_invoice', $controller_path . '\admin\CorporateInvoiceController@print_invoice')->name('admin_corporate_print_invoice');

//payment_method
Route::post('/payment_methods', $controller_path . '\admin\PaymentMethodController@store')->name('admin_payment_method_store');
Route::get('/payment_method_edit', $controller_path . '\admin\PaymentMethodController@edit')->name('edit_admin_payment_method');
Route::patch('/payment_method_update', $controller_path . '\admin\PaymentMethodController@update')->name('update_admin_payment_method');
Route::delete('/payment_method_delete', $controller_path . '\admin\PaymentMethodController@destroy')->name('delete_admin_payment_method');


//all_reports_accept

Route::get('/reporter_accept_reports', $controller_path . '\admin\AllReportsAcceptController@index')->name('admin_reporter_accept_reports');
Route::get('/reporter_accept_reports_get', $controller_path . '\admin\AllReportsAcceptController@get_accept_reports')->name('admin_reporter_accept_reports_get');
Route::post('/paid_report', $controller_path . '\admin\AllReportsAcceptController@paid_report')->name('admin_paid_report');

//chat
Route::get('/reporters_chat',$livewire . ReportersChat::class)->name('reporters_chat');
Route::get('/corporates_chat',$livewire . CorporatesChat::class)->name('corporates_chat');

Route::get('/chat',$livewire . Main::class)->name('admin_chat');


//vulnerability
Route::get('/vulnerabilities', $controller_path . '\admin\VulnerabilityController@index')->name('admin_vulnerabilities');
Route::get('/get_vulenrabilities', $controller_path . '\admin\VulnerabilityController@get_vulnerabilities')->name('get_admin_vulnerabilities');
Route::post('/create_vulnerability', $controller_path . '\admin\VulnerabilityController@store')->name('create_admin_vulnerability');
Route::patch('/update_vulnerability', $controller_path . '\admin\VulnerabilityController@update')->name('update_admin_vulnerability');
Route::get('/edit_vulnerability', $controller_path . '\admin\VulnerabilityController@edit')->name('edit_admin_vulnerability');
Route::delete('/delete_admin_vulnerability', $controller_path . '\admin\VulnerabilityController@destroy')->name('delete_admin_vulnerability');


//belong_vulnerability
Route::get('/belong_vulnerabilities/{id}', $controller_path . '\admin\BelongVulnerabilityController@index')->name('admin_belong_vulnerabilities');
Route::get('/get_belong_vulenrabilities/{id}', $controller_path . '\admin\BelongVulnerabilityController@get_vulnerabilities')->name('get_admin_belong_vulnerabilities');
Route::post('/create_belong_vulnerability', $controller_path . '\admin\BelongVulnerabilityController@store')->name('create_admin_belong_vulnerability');
Route::patch('/update_belong_vulnerability', $controller_path . '\admin\BelongVulnerabilityController@update')->name('update_admin_belong_vulnerability');
Route::get('/edit_belong_vulnerability', $controller_path . '\admin\BelongVulnerabilityController@edit')->name('edit_admin_belong_vulnerability');
Route::delete('/delete_admin_belong_vulnerability', $controller_path . '\admin\BelongVulnerabilityController@destroy')->name('delete_admin_belong_vulnerability');


//belong_belong_vulnerability
Route::get('/belong_belong_vulnerabilities/{id}', $controller_path . '\admin\BelongBelongVulnerabilityController@index')->name('admin_belong_belong_vulnerabilities');
Route::get('/get_belong_belong_vulenrabilities/{id}', $controller_path . '\admin\BelongBelongVulnerabilityController@get_vulnerabilities')->name('get_admin_belong_belong_vulnerabilities');
Route::post('/create_belong_belong_vulnerability', $controller_path . '\admin\BelongBelongVulnerabilityController@store')->name('create_admin_belong_belong_vulnerability');
Route::patch('/update_belong_belong_vulnerability', $controller_path . '\admin\BelongBelongVulnerabilityController@update')->name('update_admin_belong_belong_vulnerability');
Route::get('/edit_belong_belong_vulnerability', $controller_path . '\admin\BelongBelongVulnerabilityController@edit')->name('edit_admin_belong_belong_vulnerability');
Route::delete('/delete_admin_belong_belong_vulnerability', $controller_path . '\admin\BelongBelongVulnerabilityController@destroy')->name('delete_admin_belong_belong_vulnerability');



//hobby
Route::get('/hobbies', $controller_path . '\admin\HobbyController@index')->name('admin_hobbies');
Route::get('/get_hobbies', $controller_path . '\admin\HobbyController@get_hobbies')->name('get_admin_hobbies');
Route::post('/create_hobby', $controller_path . '\admin\HobbyController@store')->name('create_admin_hobby');
Route::patch('/update_hobby', $controller_path . '\admin\HobbyController@update')->name('update_admin_hobby');
Route::get('/edit_hobby', $controller_path . '\admin\HobbyController@edit')->name('edit_admin_hobby');
Route::delete('/delete_admin_hobby', $controller_path . '\admin\HobbyController@destroy')->name('delete_admin_hobby');



//type_target
Route::get('/type_targets', $controller_path . '\admin\TypeTargetController@index')->name('admin_targets');
Route::get('/get_type_targets', $controller_path . '\admin\TypeTargetController@get_type_targets')->name('get_admin_type_targets');
Route::post('/create_target', $controller_path . '\admin\TypeTargetController@store')->name('create_admin_target');
Route::patch('/update_target', $controller_path . '\admin\TypeTargetController@update')->name('update_admin_target');
Route::get('/edit_target', $controller_path . '\admin\TypeTargetController@edit')->name('edit_admin_target');
Route::delete('/delete_admin_target', $controller_path . '\admin\TypeTargetController@destroy')->name('delete_admin_target');



//report_chat
Route::post('/create_reportchat', $controller_path . '\admin\ReportChatController@store')->name('create_admin_report_chat');




//Notification
Route::get('/notifications', $controller_path . '\admin\NotificationController@notification')->name('admin_notifications');
Route::get('/get_read_notifications',$controller_path . '\admin\NotificationController@get_read_notifications')->name('admin_get_read_notification');
Route::get('/get_unread_notifications',$controller_path . '\admin\NotificationController@get_unread_notifications')->name('admin_get_unread_notification');
Route::get('/get_all_notifications',$controller_path . '\admin\NotificationController@get_all_notifications')->name('admin_get_all_notification');
Route::delete('/delete_notification', $controller_path . '\admin\NotificationController@delete_notification')->name('admin_delete_notification');
Route::post('/read_notification', $controller_path . '\admin\NotificationController@read_notification')->name('read_admin_notification');
Route::post('/create_notification', $controller_path . '\admin\NotificationController@store')->name('admin_create_notification');


//rewards
Route::get('/reporter_reward', $controller_path . '\admin\RewardController@reporter_reward')->name('admin_reporter_rewards');
Route::get('/corporate_reward', $controller_path . '\admin\RewardController@corporate_reward')->name('admin_corporate_rewards');


 //vertical_json
 Route::get('/search-vertical', $controller_path . '\admin\SearchJsonController@search_vertical')->name('admin_search_vertical');





//faq
Route::get('/faqs', $controller_path . '\admin\FaqController@index')->name('admin_faqs');
Route::get('/get_faqs', $controller_path . '\admin\FaqController@get_faqs')->name('get_admin_faqs');
Route::post('/create_faq', $controller_path . '\admin\FaqController@store')->name('create_admin_faq');
Route::patch('/update_faq', $controller_path . '\admin\FaqController@update')->name('update_admin_faq');
Route::get('/edit_faq', $controller_path . '\admin\FaqController@edit')->name('edit_admin_faq');
Route::delete('/delete_admin_faq', $controller_path . '\admin\FaqController@destroy')->name('delete_admin_faq');



//team
Route::get('/teams', $controller_path . '\admin\TeamController@index')->name('admin_teams');
Route::get('/get_teams', $controller_path . '\admin\TeamController@get_teams')->name('get_admin_teams');
Route::post('/create_team', $controller_path . '\admin\TeamController@store')->name('create_admin_team');
Route::post('/update_team', $controller_path . '\admin\TeamController@update')->name('update_admin_team');
Route::get('/edit_team', $controller_path . '\admin\TeamController@edit')->name('edit_admin_team');
Route::delete('/delete_admin_team', $controller_path . '\admin\TeamController@destroy')->name('delete_admin_team');



//blog
Route::get('/blogs', $controller_path . '\admin\BlogController@index')->name('admin_blogs');
Route::get('/get_blogs', $controller_path . '\admin\BlogController@get_blogs')->name('get_admin_blogs');
Route::post('/create_blog', $controller_path . '\admin\BlogController@store')->name('create_admin_blog');
Route::post('/update_blog', $controller_path . '\admin\BlogController@update')->name('update_admin_blog');
Route::post('/archive_blog', $controller_path . '\admin\BlogController@archive')->name('archive_blog');
Route::get('/edit_blog/{id}', $controller_path . '\admin\BlogController@edit')->name('edit_admin_blog');
Route::get('/delete_admin_blog/{id}', $controller_path . '\admin\BlogController@destroy')->name('delete_admin_blog');

//commentBlog
Route::get('/comments/{id}', $controller_path . '\admin\CommentBlogController@index')->name('admin_comments');
Route::get('/get_comments/{id}', $controller_path . '\admin\CommentBlogController@get_comments')->name('get_admin_comments');
Route::delete('/delete_admin_comment', $controller_path . '\admin\CommentBlogController@destroy')->name('delete_admin_comment');


//platform
Route::get('/platform', $controller_path . '\admin\PlatformSettingController@index')->name('platform_index');
Route::post('/store_update_platform', $controller_path . '\admin\PlatformSettingController@store_update')->name('create_or_update_platform');

//privacy
Route::get('/privacy', $controller_path . '\admin\PrivacyController@index')->name('admin_privacy');
Route::post('/store_update_privacy', $controller_path . '\admin\PrivacyController@store_update')->name('admin_update_store_privacy');

//term
Route::get('/term', $controller_path . '\admin\TermController@index')->name('admin_term');
Route::post('/store_update_term', $controller_path . '\admin\TermController@store_update')->name('admin_update_store_term');

//contact
Route::get('/contacts', $controller_path . '\admin\ContactController@index')->name('admin_contact');
Route::get('/get_contacts', $controller_path . '\admin\ContactController@get_contacts')->name('get_admin_contacts');
Route::post('/contacts_answer', $controller_path . '\admin\ContactController@contacts_answer')->name('admin_contacts_answer');
Route::post('/delete_contacts_answer', $controller_path . '\admin\ContactController@destroy')->name('admin_contacts_answer_delete');



//test
Route::get('/test', $controller_path . '\admin\TestController@test')->name('admin_test');




});

//end admin   authenticated





?>



