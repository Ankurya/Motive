<?php

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

Route::get('/clear', function() {
   
    //$exitCode = Artisan::call('view:clear');
    
    // $exitCode = Artisan::call('log:clear');
    
    // return what you want
    return 'cleared';
});

Route::get('/cache', function() {
    $exitCode = Artisan::call('cache:clear');
    return 'cache cleared';
});

Route::get('/route', function() {
    $exitCode = Artisan::call('route:clear');
    return 'route cleared';
});

Route::get('/config', function() {
    $exitCode = Artisan::call('config:clear');
    return 'config cleared';
});

Route::get('/view', function() {
    $exitCode = Artisan::call('view:clear');
    return 'view cleared';
});

Route::get('/link', function() {
    $exitCode = Artisan::call('storage:link');
    return 'link cleared';
});


Route::get('/link', function() {
    $exitCode = Artisan::call('storage:link');
});

Route::get('listen', function() {
    return Artisan::call('queue:listen');
});


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('pageNotFound404', function () {
    return view('pageNotFound404');
});

Route::get('/home', 'HomeController@index')->name('home');

Route::match(['Get','Post'],'user/verify/{token}','HomeController@verify');
Route::match(['Get','Post'],'user/reset-password/{token}','HomeController@resetPassword');

################################# ADMIN PANEL ##########################################

Route::group(['middleware' => 'auth:web'],function(){

	Route::get('admin/logout','AdminController@logout');
	Route::get('admin/dashboard','AdminController@dashboard');
	
	// User Management
	Route::get('admin/user-management','AdminController@userManagement');
	Route::get('deleteUser/{id}','AdminController@deleteUser');
	Route::get('admin/user-view/{id}','AdminController@viewUser');
	Route::match(['Get','Post'],'admin/user-edit/{id}','AdminController@editUserDetail');
	
	// Data Mangement
	Route::get('admin/data-management','AdminController@dataManagement');
	Route::get('admin/view-data/{id}','AdminController@eachUserDataManagement');
	Route::get('admin/user-view-data/{id}','AdminController@eachData');
	Route::match(['Get','Post'],'admin/send-push-message','AdminController@sendPushMessage');
	
	// 02-07-2018
	
	Route::match(['Get','Post'],'admin/maintenance','AdminController@maintenance');
	Route::match(['Get','Post'],'admin/maintenance_update','AdminController@maintenance_update');
	Route::match(['Get','Post'],'admin/maintenance_status','AdminController@maintenance_status');
});
  
// Route::group(['middleware' => 'guest'],function(){
	Route::match(['Get','Post'],'/','HomeController@login');
	Route::match(['Get','Post'],'/admin','HomeController@login');
	Route::match(['Get','Post'],'admin/login','HomeController@login');
	Route::match(['Get','Post'],'change-password/{token}','HomeController@changePassword');
	Route::match(['Get','Post'],'admin/forgot-password','HomeController@forgotPassword');
	Route::match(['Get','Post'],'admin/reset-password/{token}','HomeController@resetPassword');
	
	
	Route::match(['Get','Post'],'user/reset-password/{token}','HomeController@resetPassword');
	Route::match(['Get','Post'],'user/verify/{token}','HomeController@verify');
	Route::match(['Get','Post'],'private-policy','HomeController@private_policy');
	Route::match(['Get','Post'],'terms-conditions','HomeController@terms_conditions'); 

	Route::match(['Get','Post'],'get-in-touch','HomeController@getInTouch');
	Route::match(['Get','Post'],'faq','HomeController@faq');  
// });

/*gurmeet roots*/
    Route::any('forgot-password','HomeController@forgotPassword');
	Route::any('admin/reset-password/{token}','HomeController@resetPassword');
	Route::any('/logout','AdminController@logout');
    Route::any('dashboard','AdminController@dashboard');
	Route::any('user-list','AdminController@user_list');
	Route::any('view-user/{id}','AdminController@view_user');
	Route::any('block_unblock_user/{id}','AdminController@block_unblock_user');
	Route::any('block_unblock_organizer/{id}','AdminController@block_unblock_organizer');
	Route::any('edit-user/{id}','AdminController@edit_user');
	Route::any('organizer-list','AdminController@organizer_list');
	Route::any('view-organizer/{id}','AdminController@view_organizer');
	Route::any('edit-organizer/{id}','AdminController@edit_organizer');
    Route::any('edit-public-interest/{id}','AdminController@edit_public_interest');
	Route::any('update_public_interest','AdminController@update_public_interest');
	Route::any('add-public-interest','AdminController@add_public_interest');
	Route::any('delete_public_interest/{id}','AdminController@delete_public_interest');
	Route::any('music-interest','AdminController@music_interest');
	Route::any('add-music-interest','AdminController@add_music_interest');
	Route::any('edit-music-interest/{id}','AdminController@edit_music_interest');
	Route::any('update_music_interest','AdminController@update_music_interest');
	Route::any('delete_music_interest/{id}','AdminController@delete_music_interest');
	Route::any('private-events','AdminController@private_events');
	Route::any('edit-private-event-details/{id}','AdminController@edit_private_event_details');
	Route::any('update_private_event','AdminController@update_private_event');
	Route::any('public-events','AdminController@public_events');
	Route::any('payment-list','AdminController@payment_list');
	Route::any('coin-management','AdminController@coin_management');
	Route::any('public-interest','AdminController@public_interest');
	
	Route::any('update_user','AdminController@update_user');
	Route::any('update_organizer','AdminController@update_organizer');
	Route::any('private-view-event-details/{id}','AdminController@private_view_event_details');
	Route::any('public-view-event-details/{id}','AdminController@public_view_event_details');
	Route::any('block_unblock_event/{id}','AdminController@block_unblock_event');
	Route::any('block_unblock_public_event/{id}','AdminController@block_unblock_public_event');
	Route::any('approve_event_private/{id}','AdminController@approve_event_private');
	Route::any('approve_event_public/{id}','AdminController@approve_event_public');
	Route::any('delete_user/{id}','AdminController@delete_user');
	Route::any('delete_organizer/{id}','AdminController@delete_organizer');
	Route::any('edit_public_event/{id}','AdminController@edit_public_event');
	Route::any('update_public_event','AdminController@update_public_event');
	Route::any('delete_public_event/{id}','AdminController@delete_public_event');
	Route::any('delete_private_event/{id}','AdminController@delete_private_event');
	Route::any('delete_report_event/{id}','AdminController@delete_report_event');  
	
	
/*************/
	Route::any('event-reports','AdminController@event_reports');
	Route::any('view-event-report/{id}','AdminController@view_event_report');
	Route::any('block-event/{id}','AdminController@block_event');
	

	Route::get('/home', 'HomeController@index')->name('home');






################################# WEBSITE ##############################################

Route::group(['namespace' => 'Website','prefix'=>'website', 'as' => 'website.'], function() {


	Route::get('event_share_url/{event_share_id}','WebsiteController@event_share_url');
	Route::post('email-subscribe','WebsiteController@emailSubscribe');
	Route::post('send-link','WebsiteController@sendlink');
	Route::match(['Get','Post'],'login','ProfileController@login');
	Route::get('/','WebsiteController@currentEvent');
	Route::get('home','WebsiteController@homePage');
	Route::match(['Get','Post'],'register','ProfileController@register');
	Route::match(['Get','Post'],'organiser-register','ProfileController@organiserRegister');
	Route::match(['Get','Post'],'forgot-password','ProfileController@forgotPassword');
	Route::match(['Get','Post'],'reset-password/{reset_token?}','ProfileController@resetPassword')->name('resetPassword');
    Route::get('password-reset-success','ProfileController@viewMessageResetPassword')->name('passwordResetInvalid');
    Route::get('reset-password-invalid','ProfileController@resetPasswordInvalid')->name('passwordResetInvalid');
    Route::match(['Get','Post'],'user-web/verify/{token}','ProfileController@verify');
    
	Route::get('events','WebsiteController@events');
	Route::get('current-events','WebsiteController@currentEvent');
	Route::get('upcoming-events','WebsiteController@upcomingEvents');
	Route::get('favorite-events','WebsiteController@favoriteEvents');

	Route::get('search-current/{search_by_name}','WebsiteController@search_by_current');
	Route::get('search-upcoming/{search_by_name}','WebsiteController@search_by_upcoming');
	Route::get('search-favorite/{search_by_name}','WebsiteController@search_by_favorite');

	Route::get('tickets','WebsiteController@tickets');
	Route::match(['Get','Post'],'filter','WebsiteController@filter');
	Route::match(['Get','Post'],'contact-us','ProfileController@contactUs');
	
	Route::get('social-login','ProfileController@socialLogin')->name('socialLogin');
	Route::any('facebook-callback','ProfileController@loginFacebookCallback');

	Route::get('terms','WebsiteController@termCondition');
	Route::get('current-filter','WebsiteController@currentFilter');
	Route::get('privacy','WebsiteController@privacy');
	Route::get('get-in-touch','WebsiteController@getInTouch');
	Route::get('faq','WebsiteController@faq');
	Route::get('support','WebsiteController@support');
	Route::get('add-details/{user_id}','WebsiteController@add_details');
	Route::post('add-details-insert','WebsiteController@add_details_insert');

	Route::get('event_details/{event_id}','WebsiteController@event_details');

	Route::group(['middleware' => ['check_user']],  function() {
		Route::get('event-ticket/{event_id}','WebsiteController@eventTicket');
		Route::get('my-profile','ProfileController@myProfile');
        Route::get('block-users','ProfileController@blockedUsersList');
        Route::get('unblock-user/{user_id}','ProfileController@unblockedUser');
		Route::match(['Get','Post'],'change-password','ProfileController@changePassword');
		Route::get('website-view/{event_id}','WebsiteController@websiteView');
        Route::get('logout','ProfileController@logout');
		Route::match(['Get','Post'],'create-motive','WebsiteController@createMotive');
		Route::match(['Get','Post'],'create-event','WebsiteController@createEvent');
        Route::get('settings','WebsiteController@settings');
        Route::match(['Get','Post'],'comments/{post_id}','WebsiteController@comments');
        Route::get('posts/{event_id}','WebsiteController@posts');
        Route::post('like-post/{post_id}','WebsiteController@likePost');
        Route::post('favorite','WebsiteController@addToFavourite');
        Route::match(['Get','Post'],'add-post/{event_id}','WebsiteController@addPost');
        Route::post('show-age','WebsiteController@showAge');
        Route::get('ticket-view/{event_id}','WebsiteController@ticketView');
        Route::post('notificationOff','WebsiteController@notificationOff');
        Route::match(['Get','Post'],'friend-list','ProfileController@friendList');
        Route::match(['Get','Post'],'friend-request','ProfileController@friendRequest');
        Route::get('referall-code','ProfileController@referallCode');
        Route::get('public-interest','ProfileController@publicInterest');
        Route::get('music-interest','ProfileController@musicInterest');
        Route::match(['Get','Post'],'add-friends','ProfileController@addFriend');
        Route::get('sent-request/{friend_id}','ProfileController@sentRequest');
        Route::match(['Get','Post'],'invitation','WebsiteController@invitation');
        Route::match(['Get','Post'],'edit-profile/{id}','ProfileController@editProfile');
        Route::post('block-user','ProfileController@blockUser');
        Route::get('website-first/{invitation_id}','WebsiteController@invitationView');
        Route::get('accept-invitation/{invitation_id}','WebsiteController@acceptInvitation');
        Route::get('reject-invitation/{invitation_id}','WebsiteController@rejectInvitation');
        Route::match(['Get','Post'],'view-profile/{id}','ProfileController@viewProfile');
        Route::get('accept-request/{request_id}','ProfileController@acceptRequest');
        Route::get('reject-request/{request_id}','ProfileController@rejectRequest');
        Route::get('past-event','WebsiteController@pastEvent');
        Route::post('edit-public-interest','ProfileController@editPublicInterest');
        Route::post('edit-music-interest','ProfileController@editMusicInterest');
        Route::get('view-friend/{friend_id}','ProfileController@viewFriend');
        Route::match(['Get','Post'],'book-now/{event_id}','WebsiteController@bookNow');
        Route::get('view-request/{request_id}','ProfileController@viewRequest');
        Route::get('block/{friend_id}','ProfileController@blockUser');
        Route::get('notification','WebsiteController@notification');
        Route::get('past-event/{event_id}','WebsiteController@pastEventView');
        Route::match(['Get','Post'],'buy-now/{event_id}','WebsiteController@buyNow');
        Route::get('payment','WebsiteController@payment');
        Route::get('my-event','WebsiteController@myEvent');
        Route::post('add-ticket','WebsiteController@addTicket');
        Route::get('delete-ticket/{ticket_id}','WebsiteController@deleteTicket');
        Route::get('check-in-event','WebsiteController@checkInEvent');
        Route::get('check-in-detail/{event_id}','WebsiteController@checkinDetail');
        Route::get('close-sales/{event_id}','WebsiteController@closeSales');
        Route::get('bought-tickets/{event_id}','WebsiteController@boughtTickets');
        Route::get('guest-list-name/{event_id}','WebsiteController@guestListName');
        Route::match(['Get','Post'],'add-guest/{guest_name_id}/{event_id}','WebsiteController@addGuest');
        Route::match(['Get','Post'],'add-guest-name/{event_id}','WebsiteController@addGuestName');
        Route::get('guest-list/{guest_id}/{event_id}','WebsiteController@guestList');
        Route::get('delete-guest/{guest_id}','WebsiteController@deleteGuest');
        Route::get('check-in-guest/{event_id}','WebsiteController@checkInGuest');
        Route::match(['Get','Post'],'edit-event/{event_id}','WebsiteController@editEvent');
        Route::get('event-view/{event_id}','WebsiteController@eventView');
        Route::get('dashboard/{event_id}','WebsiteController@eventDashboard');
        Route::get('delete-buy-ticket/{ticket_id}','WebsiteController@deleteBuyTickets');

        
        Route::get('print-ticket/{event_id}','WebsiteController@printTickets');
        Route::get('print-guest/{event_id}','WebsiteController@printGuestList');
        Route::post('analytics','WebsiteController@analytics');
		Route::get('ticket-qr/{id}','WebsiteController@ticketQr');

		Route::get('remove-profile/{id}','ProfileController@removeProfile');
    });
});