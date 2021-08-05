<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
/*test*/
	Route::post('search_event2','ApiController@search_event2');
	Route::post('verifyRefferalCode','ApiController@verifyRefferalCode');
	Route::post('logout','ApiController@logout'); //review
	Route::post('change_password','ApiController@changePassword'); //review
	Route::post('get_data_list','ApiController@getDataList'); //review
	Route::post('add_data','ApiController@addData'); //review
	Route::post('createEvent','ApiController@createEvent'); //review
	Route::post('createPost','ApiController@createPost');  //review
	Route::post('update_profile','ApiController@updateProfile'); //review
	Route::post('contact_us','ApiController@contactUs'); //review
	Route::post('notification-list','ApiController@userNotificationList');
	Route::post('event_list','ApiController@eventList');
	Route::post('eventList_new','ApiController@eventList_new');
	Route::post('each_event_list','ApiController@eachEventList');
	Route::post('post_list','ApiController@postList');
	Route::post('friend_list','ApiController@friendList'); //review
	Route::post('pending_list','ApiController@pendingList');
	Route::post('send_request','ApiController@sendRequest');
	Route::post('add_comment','ApiController@addComment'); //review
	Route::post('search_friends','ApiController@SearchFriends');
	Route::post('search_invite','ApiController@Search_invite'); //review
	Route::post('get_music_interest_list','ApiController@get_music_interest_list');
	Route::post('update_music_interest_list','ApiController@update_music_interest_list');
	Route::post('get_public_interest_list','ApiController@get_public_interest_list');
	Route::post('update_public_interest_list','ApiController@update_public_interest_list');
	Route::post('invitation_list','ApiController@invitation_list');
	Route::post('send_invitation','ApiController@send_invitation');
	Route::post('guest_list','ApiController@guest_list');
	Route::post('attend_event','ApiController@attend_event');
	Route::post('search_event','ApiController@search_event');
	Route::post('notification_list','ApiController@notification_list');
	Route::post('get_profile_counts','ApiController@get_profile_counts');
    // Route::post('get_profile','ApiController@get_profile');
	/*test*/
	Route::post('signup','ApiController@signup'); //review
	Route::post('getCountry','ApiController@getCountry'); //review
	Route::post('signin','ApiController@signin'); //review
	Route::post('musicInterestList','ApiController@musicInterestList'); //review
	Route::post('publicInterestList','ApiController@publicInterestList'); //review
	Route::post('forgotPassword','ApiController@forgotPassword');  //review
	Route::post('socialLogin','ApiController@socialLogin'); //review
	Route::post('checkEmailExist','ApiController@checkEmailExist'); //review
	Route::post('accept_decline','ApiController@acceptDecline');
	Route::post('cancel_invitation','ApiController@cancel_invitation');
	//Route::post('comment_list','ApiController@commentList');
	
	Route::post('accept_decline_invitation','ApiController@accept_decline_invitation');
	// Route::post('guest_list','ApiController@guest_list');
	Route::post('create_event_doc_list','ApiController@create_event_doc_list');
	Route::post('eventList_test','ApiController@eventList_test');
	Route::post('createEvent_test','ApiController@createEvent_test');
	Route::post('edit_event','ApiController@edit_event');
	Route::post('edit_event_test','ApiController@edit_event_test');
	Route::post('send_message','ApiController@send_message'); //done
	Route::post('get_chat','ApiController@get_chat');
	Route::post('recent_chat','ApiController@recent_chat');
	Route::post('refferal_num2','ApiController@refferal_num2');
	Route::post('guest_list_test','ApiController@guest_list_test');
	Route::post('add_co_admin','ApiController@add_co_admin'); //review
	Route::post('attend_unattend_event','ApiController@attend_unattend_event');
	Route::post('badge_count','ApiController@badge_count');
	Route::post('event_list','ApiController@eventList');
	Route::post('event_close_sales','ApiController@closeSales');




Route::group(['middleware' => 'auth:api'],function(){
	Route::post('cancel_request','ApiController@cancel_request'); //done
	Route::post('guests','ApiController@guests');
	Route::post('send-ticket','ApiController@sendTicket');
	Route::post('guest-ticket','ApiController@orgGuestTicket');
	Route::post('notificationOff','ApiController@notificationOff'); //review
	Route::post('users_list','ApiController@users'); //review
	Route::post('chat_id','ApiController@chat_id'); //review
	Route::post('like_post','ApiController@likePost');
	Route::post('contact_us','ApiController@contactUs'); //review
	Route::post('logout','ApiController@logout'); //review
	Route::post('change_password','ApiController@changePassword'); //review
	Route::post('get_data_list','ApiController@getDataList'); //review
	Route::post('add_data','ApiController@addData'); //review
	Route::post('createEvent','ApiController@createEvent');
	Route::post('createPost','ApiController@createPost');
	Route::post('update_profile','ApiController@updateProfile');
	Route::post('contact_us','ApiController@contactUs');
	Route::post('check_ins','ApiController@check_ins');

	Route::post('each_event_list','ApiController@eachEventList');
	Route::post('post_list','ApiController@postList');
	Route::post('comment_list','ApiController@commentList'); //review
	Route::post('friend_list','ApiController@friendList'); //review
	Route::post('pending_list','ApiController@pendingList');
	Route::post('send_request','ApiController@sendRequest');
	Route::post('add_comment','ApiController@addComment'); //reeview
	Route::post('like_post','ApiController@likePost');  //review
	Route::post('search_friends','ApiController@SearchFriends'); //review
	Route::post('search_invite','ApiController@Search_invite'); // review
	Route::post('get_music_interest_list','ApiController@get_music_interest_list');
	Route::post('update_music_interest_list','ApiController@update_music_interest_list');
	Route::post('get_public_interest_list','ApiController@get_public_interest_list');
	Route::post('update_public_interest_list','ApiController@update_public_interest_list');
	Route::post('invitation_list','ApiController@invitation_list');
	Route::post('send_invitation','ApiController@send_invitation');
	Route::post('guest_list','ApiController@guest_list');
	Route::post('attend_event','ApiController@attend_event');
	Route::post('search_event','ApiController@search_event');
	Route::post('notification_list','ApiController@notification_list');
	Route::post('get_profile_counts','ApiController@get_profile_counts');
	Route::post('get_profile','ApiController@get_profile');
	Route::post('edit_event','ApiController@edit_event');
	Route::post('add_co_admin','ApiController@add_co_admin');   //review
    Route::post('badge_count','ApiController@badge_count');
	Route::post('block_unblock_user','ApiController@block_unblock_user');
	Route::post('report_event','ApiController@report_event');
	Route::post('block_user_list','ApiController@block_user_list');
	Route::post('check_in_events','ApiController@check_in_events');
	Route::post('get_ticket_list','ApiController@get_ticket_list');
	Route::post('buy_ticket','ApiController@buy_ticket'); //review
	Route::post('my_tickets','ApiController@my_tickets'); //review
	Route::post('add_favourite','ApiController@addFavourite');
	Route::post('check_in_events','ApiController@check_in_events');
	Route::post('delete_guest','ApiController@delete_guest');
	Route::post('close_sale','ApiController@close_sale'); //review
	Route::post('dashboard','ApiController@dashboard');
	Route::post('scan_ticket','ApiController@scan_ticket');
	Route::post('get_user_interest_list','ApiController@get_user_interest_list'); //review
	Route::post('getMyApprovedEvents','ApiController@getMyApprovedEvents');
	Route::post('create_guest_list_name','ApiController@create_guest_list_name'); //review
	Route::post('get_guest_list_name','ApiController@get_guest_list_name');
	Route::post('guest_list','ApiController@guest_list');
	Route::post('event_view_users_list','ApiController@userList');
	Route::post('add_guest','ApiController@add_guest');
	Route::post("scan_ticket_buy","ApiController@scan_ticket_buy");
	Route::post("user-guest-list","ApiController@userGuestList");

	// New Api's 

	Route::get("get-weekly-events/{event_id}","ApiController@getWeeklyEvents");
	Route::post("get-ticket-list","ApiController@getEventTickets");




});

// ------------ version 2 start  ------------
Route::group(['prefix' => 'v2'],function(){
	Route::post('signup','ApiControllerV2@signup'); //review
	Route::post('getCountry','ApiControllerV2@getCountry');
	Route::post('signin','ApiControllerV2@signin'); //review
	Route::post('musicInterestList','ApiControllerV2@musicInterestList'); //review
	Route::post('publicInterestList','ApiControllerV2@publicInterestList'); //review
	Route::post('forgotPassword','ApiControllerV2@forgotPassword');
	Route::post('socialLogin','ApiControllerV2@socialLogin'); //review
	Route::post('checkEmailExist','ApiControllerV2@checkEmailExist');
	// Route::post('accept_decline','ApiControllerV2@acceptDecline');
	Route::post('cancel_invitation','ApiControllerV2@cancel_invitation');
	//Route::post('comment_list','ApiControllerV2@commentList');
	Route::post('cancel_request','ApiControllerV2@cancel_request'); //review
	Route::post('accept_decline_invitation','ApiControllerV2@accept_decline_invitation');
	// Route::post('guest_list','ApiControllerV2@guest_list');
	Route::post('create_event_doc_list','ApiControllerV2@create_event_doc_list');
	Route::post('eventList_test','ApiControllerV2@eventList_test');
	Route::post('createEvent_test','ApiControllerV2@createEvent_test');
	// Route::post('edit_event','ApiControllerV2@edit_event');
	Route::post('edit_event_test','ApiControllerV2@edit_event_test');
	Route::post('send_message','ApiControllerV2@send_message'); //done
	Route::post('get_chat','ApiControllerV2@get_chat');
	Route::post('recent_chat','ApiControllerV2@recent_chat');
	Route::post('refferal_num2','ApiControllerV2@refferal_num2');
	Route::post('guest_list_test','ApiControllerV2@guest_list_test');
	// Route::post('add_co_admin','ApiControllerV2@add_co_admin');
	Route::post('attend_unattend_event','ApiControllerV2@attend_unattend_event');
	Route::post('add_guest','ApiControllerV2@add_guest');
	// Route::post('badge_count','ApiControllerV2@badge_count');
});

Route::group(['middleware' =>['auth:api','admin_maintenance'],'prefix' => 'v2'],function(){
	Route::post('logout','ApiControllerV2@logout'); //review
	Route::post('change_password','ApiControllerV2@changePassword'); //review
	Route::post('get_data_list','ApiControllerV2@getDataList'); //review
	Route::post('add_data','ApiControllerV2@addData'); //done
	Route::post('createEvent','ApiControllerV2@createEvent');
	Route::post('createPost','ApiControllerV2@createPost');
	Route::post('update_profile','ApiControllerV2@updateProfile');  //done
	Route::post('contact_us','ApiControllerV2@contactUs'); //review
	Route::post('event_list','ApiControllerV2@eventList');
	Route::post('each_event_list','ApiControllerV2@eachEventList');
	Route::post('post_list','ApiControllerV2@postList');
	Route::post('comment_list','ApiControllerV2@commentList');
	// Route::post('friend_list','ApiControllerV2@friendList');
	Route::post('pending_list','ApiControllerV2@pendingList');
	Route::post('send_request','ApiControllerV2@sendRequest');
	Route::post('add_comment','ApiControllerV2@addComment'); //review
	Route::post('like_post','ApiControllerV2@likePost');
	Route::post('search_friends','ApiControllerV2@SearchFriends'); //review
	Route::post('search_invite','ApiControllerV2@Search_invite'); //review
	Route::post('get_music_interest_list','ApiControllerV2@get_music_interest_list');
	Route::post('update_music_interest_list','ApiControllerV2@update_music_interest_list');
	Route::post('get_public_interest_list','ApiControllerV2@get_public_interest_list');
	Route::post('update_public_interest_list','ApiControllerV2@update_public_interest_list');
	Route::post('invitation_list','ApiControllerV2@invitation_list');
	Route::post('send_invitation','ApiControllerV2@send_invitation');
	Route::post('check_ins','ApiControllerV2@check_ins');
	Route::post('guest_list','ApiControllerV2@guest_list');
	Route::post('attend_event','ApiControllerV2@attend_event');
	Route::post('search_event','ApiControllerV2@search_event');
	Route::post('notification_list','ApiControllerV2@notification_list');
	Route::post('get_profile_counts','ApiControllerV2@get_profile_counts');
	Route::post('get_profile','ApiControllerV2@get_profile'); //review
	Route::post('edit_event','ApiControllerV2@edit_event');
	Route::post('add_co_admin','ApiControllerV2@add_co_admin');  //review
    Route::post('badge_count','ApiControllerV2@badge_count');
	Route::post('block_unblock_user','ApiControllerV2@block_unblock_user');
	Route::post('report_event','ApiControllerV2@report_event');
	Route::post('block_user_list','ApiControllerV2@block_user_list');
	Route::post('search_event_filter','ApiControllerV2@search_event_filter');
	Route::post('create_guest_list_name','ApiControllerV2@create_guest_list_name'); //done
	Route::post('get_guest_list_name','ApiControllerV2@get_guest_list_name'); //done
	Route::post('add_guest','ApiControllerV2@add_guest');
	Route::post('update_ticket','ApiControllerV2@update_ticket'); //review
	Route::post('profile_settings','ApiControllerV2@profile_settings'); //done
	Route::post('add_card','ApiControllerV2@add_card'); //done
	Route::post('get_cards','ApiControllerV2@get_cards'); //done
	Route::post('get_ticket_list','ApiControllerV2@get_ticket_list');
	Route::post('buy_ticket','ApiControllerV2@buy_ticket'); //review
	Route::post('my_tickets','ApiControllerV2@my_tickets');//review
	Route::post('add_favourite','ApiControllerV2@addFavourite');
	Route::post('check_in_events','ApiControllerV2@check_in_events');
	Route::post('delete_guest','ApiControllerV2@delete_guest');
	Route::post('close_sale','ApiControllerV2@close_sale'); //done
	Route::post('dashboard','ApiControllerV2@dashboard');
	Route::post('scan_ticket','ApiControllerV2@scan_ticket');
	Route::post('get_user_interest_list','ApiControllerV2@get_user_interest_list'); //review

});

// ------------ version 2 End  ------------

