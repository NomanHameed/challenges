<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Services\StravaWebhookService;
use Illuminate\Http\Response;

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
/*
Route::get('/', function () {
    return view('frontend/welcome');
});
*/

Route::get('/badge-share/{id}', 'TestController@show')->name('test.show');
Route::get('/challenge-badge-share/{id}', 'TestController@view')->name('test.view');
Route::post('/updateBadgeSeenStatus', 'Frontend\AdminController@updateBadgeSeenStatus')->name('frontend.updateBadgeSeenStatus');

Route::get('/', 'Frontend\AdminController@home')->name('frontend.home');
Route::get('signup', 'Frontend\AdminController@userRegister')->name('frontend.userRegister');
Route::get('forgot_password', 'Frontend\AdminController@forgot_password')->name('frontend.forgot_password');
Route::get('dashboard', 'Frontend\AdminController@dashboard')->name('frontend.dashboard');
Route::get('my_challenge', 'Frontend\AdminController@my_challenge')->name('frontend.my_challenge');
Route::get('challenge_list', 'Frontend\AdminController@challenge_list')->name('frontend.challenge_list');
Route::get('challenge_details/{id}', 'Frontend\AdminController@challenge_details')->name('frontend.challenge_details');
Route::post('sendMonthlyLogs', 'Frontend\AdminController@sendMonthlyLogs')->name('frontend.sendMonthlyLogs');
Route::get('profile', 'Frontend\AdminController@profile')->name('frontend.profile');
Route::put('profile/{id}', 'Frontend\AdminController@profileUpdate')->name('frontend.profileUpdate');
Route::put('uploadProfileFormSubmit/{id}', 'Frontend\AdminController@uploadProfileFormSubmit')->name('frontend.uploadProfileFormSubmit');
Route::put('changePassword/{id}', 'Frontend\AdminController@changePassword')->name('frontend.changePassword');
Route::get('fullcalender', 'Frontend\AdminController@fullcalender')->name('frontend.fullcalender');
Route::post('add_challenge_log', 'Frontend\AdminController@add_challenge_log')->name('frontend.add_challenge_log');
Route::put('update_challenge_log', 'Frontend\AdminController@update_challenge_log')->name('frontend.update_challenge_log');
Route::delete('delete_challenge_log', 'Frontend\AdminController@delete_challenge_log')->name('frontend.delete_challenge_log');
Route::post('frontend/register', 'Frontend\AdminController@register')->name('frontend.register');
Route::post('frontend/login', 'Frontend\AdminController@login')->name('frontend.login');
Route::post('frontend/logout', 'Frontend\AdminController@logout')->name('frontend.logout');
Route::get('manageDevice', 'Frontend\AdminController@manageDevice')->name('frontend.manageDevice');
Route::get('achievement', 'Frontend\AdminController@TrophyCase')->name('frontend.Achievement');
Route::get('stravaAuth', 'Frontend\AdminController@stravaAuth')->name('frontend.stravaAuth');
Route::get('getToken', 'Frontend\AdminController@getToken')->name('frontend.getToken');
Route::get('Disconnect', 'Frontend\AdminController@Disconnect')->name('frontend.Disconnect');
Route::get('refreshTest', 'Frontend\AdminController@refreshTest')->name('frontend.refreshTest');

Route::get('/resetPassword', 'Frontend\AdminController@resetPassword')->name('admin.resetPassword');
Route::post('/resetMail', 'Frontend\AdminController@resetMail')->name('admin.resetMail');  
Route::get('find/{token}', 'Frontend\AdminController@find')->name('admin.find');
Route::POST('/reset', 'Frontend\AdminController@reset')->name('admin.reset');
Route::POST('/resetPassword', 'Frontend\AdminController@resetPassword')->name('admin.resetPassword');
Route::get('/resetPasswordExpire', 'Frontend\AdminController@resetPasswordExpire')->name('admin.resetPasswordExpire');
Route::get('/resetPasswordSuccessful', 'Frontend\AdminController@resetPasswordSuccessful')->name('admin.resetPasswordSuccessful');
Route::get('/stravaConnect', 'Frontend\AdminController@stravaConnect')->name('admin.stravaConnect');
Route::get('/MapMyRunConnect', 'Frontend\AdminController@MapMyRunConnect')->name('admin.MapMyRunConnect');
Route::get('MapMyRunDisconnect', 'Frontend\AdminController@MapMyRunDisconnect')->name('admin.MapMyRunDisconnect');

Route::get('StravaSubscription', 'Frontend\StravaController@StravaSubscription')->name('admin.StravaSubscription');
Route::get('StravaView', 'Frontend\StravaController@StravaView')->name('admin.StravaView');

Route::match(['get', 'post'], '/webhook/strava', 'Frontend\AdminController@webhook');

Route::get('MapMyRunAuth', 'Frontend\MapMyRunController@MapMyRunAuth')->name('admin.MapMyRunAuth');
Route::post('MapMyRunAuth', 'Frontend\MapMyRunController@MapMyRunGetAuthData')->name('admin.MapMyRunGetAuthData');
Route::get('MapMyRunRedirect', 'Frontend\MapMyRunController@MapMyRunRedirect')->name('admin.MapMyRunRedirect');
Route::get('MapMyRunSubscribe', 'Frontend\MapMyRunController@MapMyRunSubscribe')->name('admin.MapMyRunSubscribe');
Route::get('MapMyRun_updateRedirectURL', 'Frontend\MapMyRunController@MapMyRun_updateRedirectURL')->name('admin.MapMyRun_updateRedirectURL');

Route::get('MapMyRunGetAuthData', 'Frontend\MapMyRunController@MapMyRunGetAuthData')->name('admin.MapMyRunGetAuthData');
Route::get('MapMyRunRedirect', 'Frontend\MapMyRunController@MapMyRunRedirect')->name('admin.MapMyRunRedirect');
Route::post('MapMyRunSubscribePost', 'Frontend\MapMyRunController@MapMyRunSubscribe')->name('admin.MapMyRunSubscribe');
Route::get('MapMyRunSubscribe', 'Frontend\MapMyRunController@MapMyRunSubscribeGet')->name('admin.MapMyRunSubscribeGet');
Route::get('MapMyRun_updateRedirectURL', 'Frontend\MapMyRunController@MapMyRun_updateRedirectURL')->name('admin.MapMyRun_updateRedirectURL');

// Fitbit Routes
Route::get('/fitbitConnect', 'Frontend\FitbitController@fitbitConnect')->name('admin.fitbitConnect');
Route::get('/fitbitAuth', 'Frontend\FitbitController@fitbitAuth')->name('frontend.fitbitAuth');
Route::get('/getFitbitToken', 'Frontend\FitbitController@getFitbitToken')->name('frontend.getFitbitToken');
Route::get('/fitbitDisconnect', 'Frontend\FitbitController@fitbitDisconnect')->name('frontend.fitbitDisconnect');
Route::match(['get', 'post'], '/fitbit-notifications', 'Frontend\FitbitController@fitbitNotifications')->name('frontend.fitbitNotifications');

// Garmin Routes
Route::get('/garminConnect', 'Frontend\GarminController@garminConnect')->name('admin.garminConnect');
Route::get('/garminAuth', 'Frontend\GarminController@garminAuth')->name('frontend.garminAuth');
Route::get('/garmin-callback', 'Frontend\GarminController@garminCallback')->name('frontend.garminCallback');
Route::get('/garminDisconnect', 'Frontend\GarminController@garminDisconnect')->name('frontend.garminDisconnect');
Route::match(['get', 'post'], '/garmin-notifications', 'Frontend\GarminController@garminNotifications')->name('frontend.garminNotifications');

Route::get('/webhook', function (Request $request) {
    $mode = $request->query('hub_mode'); // hub.mode
    $token = $request->query('hub_verify_token'); // hub.verify_token
    $challenge = $request->query('hub_challenge'); // hub.challenge

    if($mode && $token && $challenge){
        return app(StravaWebhookService::class)->validate($mode, $token, $challenge);
    }
    
    return 0;
});

Route::post('/webhook', 'Frontend\StravaController@WebhookPostData')->withoutMiddleware(VerifyCsrfToken::class);

Auth::routes();
Route::get('/admin', 'AdminController@index')->name('admin');
Route::get('/home', 'AdminController@index')->name('admin');

//Social Login
Route::get('auth/google', 'Auth\GoogleController@redirectToGoogle');
Route::get('auth/google/callback', 'Auth\GoogleController@handleGoogleCallback');

//Route for admin profile edit
Route::get('admin/profile', 'Admin\ProfileController@index')->name('profile');
Route::post('admin/profile', 'Admin\ProfileController@update')->name('profile');

//Challanges
Route::post('/admin/challenges/add_locations', 'Admin\ChallengeController@addLocations')->name('addlocation');
Route::post('/admin/challenges/add_milestones', 'Admin\ChallengeController@addMilestones')->name('addmilestone');
Route::put('/admin/challenges/edit_milestones', 'Admin\ChallengeController@editMilestones')->name('editmilestone');
Route::resource('/admin/challenges', 'Admin\ChallengeController', ['as'=>'admin']);

Route::resource('/admin/users', 'Admin\UsersController', ['as'=>'admin']);
Route::resource('/admin/pages', 'Admin\PageController', ['as'=>'admin']);
Route::resource('/admin/products', 'Admin\ProductController', ['as'=>'admin']);
Route::resource('/admin/faqs', 'Admin\FaqController', ['as'=>'admin']);
Route::resource('/admin/badges', 'Admin\BadgeController', ['as'=>'admin']);

Route::post('/admin/assignChallengeToUser/{id}', 'Admin\UsersController@assignChallengeToUser')->name('admin.assignChallengeToUser');
Route::post('/admin/unassignChallengeToUser/{id}', 'Admin\UsersController@unassignChallengeToUser')->name('admin.unassignChallengeToUser');

//Payment Route

Route::resource('/admin/payments', 'Admin\PaymentController', ['as'=>'admin']);
Route::resource('/admin/participations', 'Admin\ParticipationController', ['as'=>'admin']);
Route::get('admin/challengesLog/{challenge_id}/{user_id}', 'Admin\ParticipationController@challengesLog')->name('admin.participation.challengesLog');
Route::post('admin/add_challenge_log/{challenge_id}/{user_id}', 'Admin\ParticipationController@add_challenge_log')->name('admin.participation.add_challenge_log');
Route::put('admin/update_challenge_log/{user_id}', 'Admin\ParticipationController@update_challenge_log')->name('admin.participation.update_challenge_log');
Route::delete('admin/delete_challenge_log', 'Admin\ParticipationController@delete_challenge_log')->name('admin.participation.delete_challenge_log');
Route::resource('/admin/orders', 'Admin\OrderController', ['as'=>'admin']);

Route::post('admin/transferOwnership','Admin\ParticipationController@transferOwnership')->name('transferOwnership');

//Route for Ajax
Route::post('admin/ajax/get_us_state','Admin\AjaxController@get_us_state');
Route::post('admin/ajax/get_us_cities','Admin\AjaxController@get_us_cities');

//Route for Ajax
Route::post('admin/ajax/get_us_state_profile','Admin\AjaxController@get_us_state_profile');
Route::post('admin/ajax/get_us_cities_profile','Admin\AjaxController@get_us_cities_profile');

Route::post('admin/ajax/attach_product','Admin\AjaxController@attach_product');
Route::post('admin/ajax/unattach_product','Admin\AjaxController@unattach_product');

Route::post('admin/ajax/get_challenge_milestone','Admin\AjaxController@getChallengeMilestone');
Route::post('admin/ajax/delete_challenge_milestone','Admin\AjaxController@deleteChallengeMilestone');

Route::post('admin/ajax/view_order_log','Admin\AjaxController@viewOrderLog');
Route::post('admin/ajax/auto_complete_user','Admin\AjaxController@autoCompleteUser');


Route::get('admin/settings', 'Admin\SettingController@index')->name('settings');
Route::post('admin/settings', 'Admin\SettingController@update')->name('settings');



