<?php

namespace App\Http\Controllers;

use DB;
use Hash;
use Session;
use app\User;

use Auth;
use Validator;
use Illuminate\Http\Request;

class sadsaadminController extends HomeController	
{
	
 	function logout(){
		Session::flush ();
		Auth::logout ();
		return redirect('/login')->with('message' ,'User Logout Successfully');
		
	} 
	function reset_password(){
		
		return view('admin/reset-password');
	} 
	function forgot_password(){
		
		return view('admin/forgot-password');
	} 
	
	function dashboard(){
		
		return view('admin/dashboard');
	}
	 function user_list(){
		 
		 
		$get_userlist = User::where('role',2)->get();
		 /* echo "<pre>";
		 print_r($get_userlist); */
		 
		
		return view('admin/user-list',['user_list'=>$get_userlist]);
	}
	 function view_user(Request $request ){
		 $view_id = $request->id;
		 if(!empty($view_id)){
			$get_view_list = User::where(['role'=>2,'id'=>$view_id])->first();
		 }
		 else{
			 $get_view_list=array();
			 
		 }
			 
		 
	
		return view('admin/view-user',['user_list'=>$get_view_list]);
	}
	 function edit_user(Request $request ){
		$view_id = $request->id;
		 if(!empty($view_id)){
			$get_view_list = User::where(['role'=>2,'id'=>$view_id])->first();
		 }
		 else{
			 $get_view_list=array(); 
		 }
		return view('admin/edit-user',['user_list'=>$get_view_list]);
		
	}
	function organizer_list(){
		
		return view('admin/organizer-list');
	}
	function private_events(){
		
		return view('admin/private-events');
	}
	function public_events(){
		
		return view('admin/public-events');
	}
	function payment_list(){
		
		return view('admin/payment-list');
	}
	function coin_management(){
		
		return view('admin/coin-management');
	}
	function public_interest(){
		
		return view('admin/public-interest');
	}
	function edit_public_interest(){
		
		return view('admin/edit-public-interest');
	}
	function add_public_interest(){
		
		return view('admin/add-public-interest');
	}
	function music_list(){
		
		return view('admin/music-list');
	}
	function add_music_interest(){
		
		return view('admin/add-music-interest');
	}
	function edit_music_interest(){
		
		return view('admin/edit-music-interest');
	}
	
}
