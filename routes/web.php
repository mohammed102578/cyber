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
Route::group(
    [
        'middleware'=>'guest:web',


    ], function(){
        $controller_path = 'App\Http\Controllers';


//index
Route::get('/', $controller_path . '\web\IndexController@index')->name('index');
Route::get('/blog', $controller_path . '\web\BlogController@index')->name('blog');
Route::get('/blog-details/{id}', $controller_path . '\web\BlogController@details')->name('blog_details');
Route::get('/blog-category/{id}', $controller_path . '\web\BlogController@category')->name('blog_categories');
Route::get('/blog-tag/{id}', $controller_path . '\web\BlogController@tag')->name('blog_tags');
Route::post('/store_comment', $controller_path . '\web\BlogController@store_comment')->name('store_comment');


//services
Route::get('/services', $controller_path . '\web\ServiceController@index')->name('service');
Route::get('/bug_bounty', $controller_path . '\web\ServiceController@bug_bounty')->name('service_bug_bounty');
Route::get('/vulenrability_disclousre_policy', $controller_path . '\web\ServiceController@vulenrability_disclousre_policy')->name('service_vulenrability_disclousre_policy');
Route::get('/penetest_as_a_service', $controller_path . '\web\ServiceController@penetest_as_a_service')->name('service_penetest_as_a_service');


//contact && request_ademo

Route::get('/contact', $controller_path . '\web\ContactController@index')->name('contact');
Route::post('/contact_store', $controller_path . '\web\ContactController@store')->name('contact_store');
Route::get('/reqest_ademo', $controller_path . '\web\ContactController@request_ademo')->name('request_ademo');


//privacy
Route::get('/privacy', $controller_path . '\web\PrivacyController@index')->name('privacy');
Route::get('/privacy-ploicy', $controller_path . '\web\PrivacyController@privacy_ploicy')->name('privacy_ploicy');
Route::get('/code-of-conduct', $controller_path . '\web\PrivacyController@code_of_conduct')->name('code_of_conduct');
Route::get('/disclosure-privacy', $controller_path . '\web\PrivacyController@disclosure_privacy')->name('disclosure_privacy');

//program
Route::get('/programs', $controller_path . '\web\ProgramController@program')->name('web_program');
Route::get('/load-program-more-data', $controller_path . '\web\ProgramController@loadMoreData')->name('web_program_loadmore');
Route::get('/program/{id}', $controller_path . '\web\ProgramController@show')->name('web_show_program');

//docs
Route::get('/reporter_docs', $controller_path . '\web\DocsController@docs')->name('reporter_docs');

//overview
Route::get('/reporter_overview', $controller_path . '\web\DocsController@overview')->name('reporter_overview');


//leaderboard

Route::match(array('GET','POST'),'/leaderboard', $controller_path . '\web\LeaderboardController@leaderboard')->name('web_leaderboard');


//Hacktivity
Route::match(array('GET','POST'),'/hacktivity', $controller_path . '\web\HacktivityController@hacktivity')->name('web_hacktivity');
Route::get('/load-hacktivity-more-data', $controller_path . '\web\HacktivityController@loadMoreData')->name('web_hacktivity_loadmore');

//hackingSd
Route::get('/about', $controller_path . '\web\IndexController@about')->name('about');
Route::get('/terms', $controller_path . '\web\IndexController@terms')->name('terms');
Route::get('/team', $controller_path . '\web\IndexController@team')->name('team');
Route::get('/testimonial', $controller_path . '\web\IndexController@testimonial')->name('testimonial');


});
?>
