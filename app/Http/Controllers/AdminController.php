<?php
namespace App\Http\Controllers;

header('Cache-Control: no-store, private, no-cache, must-revalidate');
header('Cache-Control: pre-check=0, post-check=0, max-age=0, max-stale = 0', false);

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Validator;
Use App\User;
use GuzzleHttp;
use Hash;
use Redirect;
use Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use App\Mail\EventApproveAdmin;
use DB;
use Session;
use DateTime;
date_default_timezone_set('Europe/London');

class AdminController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
    }

	public function logout(Request $request){
		Auth::logout();
		return redirect('admin/login');
	}
	
	public function changePassword(Request $request){
		$validator = Validator::make($request->all(), [
			'new_password' => 'required',
			'old_password' => 'required',
		]);
		
		if($validator->fails()){
			$this->responseWithError($validator->errors()->first());
			exit;
		}
		$user_data = $this->checkUserExist();
		if(Hash::check($request->input('old_password'),$user_data->password)){
			$update_data = User::where('id',$user_data->id)->update(['password' => Hash::make($request->input('new_password')),'visibsle_pwd' => $request->input('new_password'), 'updated_at' => Date('Y-m-d H:i:s')]);
			$this->responseOk('Password has been changed Successfully','');
		}else{
			$this->responseWithError('Old password does not match with your account');
		}
		
	}
		
	public function userManagement(Request $request){
		$get_data = User::where(['status' => 1,'role'=>2])->orderBy('id','DESC')->get();
		//echo'<pre>'; print_r($get_data->toArray());exit;
		  return view('admin/user-management',['data' => $get_data]);
	}
	
	public function deleteUser(Request $request,$id){
		$get_user_data = User::where(['id' => $id,'role'=>2])->first();
		if(!empty($get_user_data)){
			$delete = User::where(['id' => $id])->delete();
			if($delete){
				Session::flash('message','User deleted successfully.');
				return redirect('admin/user-management');
			}
		}else{
			return view('pageNotFound404');
		}
	}
	
	public function viewUser(Request $request,$id){
		$get_user_data = User::where(['id' => $id,'role'=>2])->first();
		if(!empty($get_user_data)){
			return view('admin/user-view',['data' => $get_user_data]);
		}else{
			return view('pageNotFound404');
		}
	}
	
	public function editUserDetail(Request $request,$id){
		$get_user_data = User::where(['id' => $id,'role'=>2])->first();
		if(!empty($get_user_data)){
			if($request->isMethod('post')){
				$validator = Validator::make($request->all(), [
					'name' => 'required',
					'email' => 'required|email',
				]);
				if($validator->fails()){
					return Redirect:: back()->withErrors($validator)->withInput();
				}
				$email = $request->input('email');
				$update_data = User::where(['id' => $id])->update(['name' => $request->input('name'), 'email' => $email]);
				if($update_data){
					Session::flash('message','User Detail updated successfully.');
					return Redirect('admin/user-management');
				}else{
					$message = 'Oops Something wrong. Please try again later!';
					return Redirect('admin/user-edit')->withErrors($message);
				}
			}else{
				return view('admin/user-edit',['data' => $get_user_data]);
			}
		}else{
			return view('pageNotFound404');
		}
	}
	
	public function addData(Request $request){
		$validator = Validator::make($request->all(), [
			'name' => 'required',
			'email' => 'required|email',
			'mobile_number' => 'required',
			'nationality' => 'required',
			'city' => 'required',
			'dob' => 'required',
			'occupation' => 'required',
			'comment' => 'required',
			// 'media' => 'required',
		]);
		
		if($validator->fails()){
			$this->responseWithError($validator->errors()->first());
			exit;
		}
		$user_data = $this->checkUserExist();
		if(!empty($request->file('media'))){
			$image_name = $user_data->id.'_'.str_random(20).'.png';
			$path = $request->file('media')->storeAs('public/data_images',$image_name);
			$mediaUrl = url(Storage::url($path));
		}else{
			$mediaUrl = '';
		}
		$get_data = DB::table('data_list')->insertGetId(['user_id' => $user_data->id,'name' => $request->input('name'),'email' => $request->input('email'),'mobile_number' => $request->input('mobile_number'),'nationality'=> $request->input('nationality'),'city' => $request->input('city'),'dob' => $request->input('dob'),'occupation' => $request->input('occupation'),'comment' => $request->input('comment'),'mediaUrl' => $mediaUrl,'created_at' => Date('Y-m-d H:i:s'),'updated_at'=> Date('Y-m-d H:i:s')]);
		if($get_data){
			
			//$this->responseOk('Data inserted successfully','');
		}else{
			$this->responseWithError('Oops Something Wrong');
		}
	}
	
	public function dataManagement(Request $request){
		$get_data = User::where(['status' => 1,'role'=>2])->orderBy('id','DESC')->get();
		//echo'<pre>'; print_r($get_data->toArray());exit;
		 return view('admin/data-management',['data' => $get_data]);
	}
	
	public function eachUserDataManagement(Request $request,$id){
		$get_data = DB::table('data_list')->where(['user_id' => $id])->orderBy('data_list_id','DESC')->get();
		//echo "<pre>";print_r($get_data);
		//echo $get_data[0]->user_id;
		//exit;
		if(count($get_data)){	
		//echo "yes";die;
			$select = DB::table('users')->select('name')->where('id',$get_data[0]->user_id)->first();
		
		     $data1 = $select->name; 
			
			 return view('admin/view-data',['data' => $get_data,'data1'=>$data1]);
		}else{
			//echo "no";die;
			  $data['data']=  '';
			  $data1['data1']=  '';
			 return view('admin/view-data',['data' => $get_data,'data1'=>$data1]);
		}
	}
	
	public function eachData(Request $request,$id){
		$get_data = DB::table('data_list')->where(['data_list_id' => $id])->first();
		//print_r($get_data);die;
		if(!empty($get_data)){
			$select = DB::table('users')->select('name')->where('id',$get_data->user_id)->first();
			$data1 = $select->name; 			
			return view('admin/view-user-data',['data' => $get_data,'data1'=>$data1]);
		}else{
			//$get_data=  '';
			  $data1=  '';
			 return view('admin/view-user-data',['data' => $get_data,'data1'=>$data1]);
		}
	}
	
	/*gurmeet functions*/
	
	function user_list(){
		$get_userlist = User::where(['role'=>2,'is_deleted'=>1])->get();
		return view('admin/user-list',['user_list'=>$get_userlist]);
	}
	
	
	
	function organizer_list(){
		$get_userlist = User::where(['role'=>3,'is_deleted'=>1])->get();
		return view('admin/organizer-list',['user_list'=>$get_userlist]);
		
	}
	function view_organizer(Request $request){
		$view_id = $request->id;
		$get_view_list = User::where(['role'=>3,'id'=>$view_id])->first();
		if(!empty($get_view_list)){
			return view('admin/view-organizer',['user_list'=>$get_view_list]);
		}else{
			return view('pageNotFound404');
		}
		
	}
	function edit_organizer(Request $request ){
		$view_id = $request->id;
		$get_view_list = User::where(['role'=>3,'id'=>$view_id])->first();
		if(!empty($get_view_list)){
			return view('admin/edit-organizer',['user_list'=>$get_view_list]);
		}else{
			return view('pageNotFound404'); 
		}
		
		
	}
	
	function delete_organizer(Request $request ){
		$view_id = $request->id;
		//print $view_id;die;
		if(!empty($view_id)){
			$get_view_list = User::where(['id'=>$view_id])->update(['is_deleted'=>2,'email'=>'']);
		}
		return redirect('organizer-list')->with('status','Organizer deleted successfully');
		
	}
	function update_organizer(Request $request ){
		if($request->isMethod('post')){
			//print_r($request->all());die;
			$validator = Validator::make($request->all(), [
				'name' => 'required|min:3|max:20|regex:/^[\pL\s\-]+$/u',
				//'email' => 'required|email|min:3|max:50',
				'phone' => 'nullable|digits_between:8,15|numeric',
				'image' => 'nullable|image|mimes:jpeg,png,jpg',
			]);
			if($validator->fails()){
				return Redirect:: back()->withErrors($validator)->withInput();
			}
			if(!empty($request->file('image'))){
			$image_name = $request->input('user_id').'_'.str_random(20).'.png';
			$path = $request->file('image')->storeAs('public/user_images',$image_name);
			// $mediaUrl = url(Storage::url($path));
			// $mediaUrl='http://54.206.13.240/sim_admin/storage/app/public/user_images/'.$image_name;
			// $mediaUrl='http://175.176.186.116/webservices/sim_admin/storage/app/public/user_images/'.$image_name;
			$baseUrl = url('/');
			$baseUrl = str_replace('/public','/',$baseUrl);
			$mediaUrl = $baseUrl.'/storage/app/'.$path;
			$update_data = User::where(['id' =>$request->input('user_id')])->update(['image_url'=>$mediaUrl]);
			}
			if(!empty($request->input('music_interest'))){
				$music_interest=implode(",",$request->input('music_interest'));
			}else{
				$music_interest="";
			}
			if(!empty($request->input('public_interest'))){
				$public_interest=implode(",",$request->input('public_interest'));
			}else{
				$public_interest="";
			}
			$update_data = User::where(['id' =>$request->input('user_id')])->update(['name' =>$request->input('name'),/* 'email'=>$request->input('email'), */'phone_number'=>!empty($request->input('phone')) ? $request->input('phone') :'','music_interest'=>$music_interest,'public_interest'=>$public_interest]);
			//if($update_data){
				$get_view_list = User::where(['role'=>2,'id'=>$request->input('user_id')])->first();
				Session::flash('message','User Detail updated successfully.');
				//return Redirect('view-use/',$request->input('user_id'))->with('status','User Detail updated successfully');
			   return redirect('view-organizer/'.$request->input('user_id'))->with('status','Organizer Detail updated successfully');
			/* }else{
				
				return Redirect('admin/organizer-edit')->withErrors($message);
			}  */
			//return view('admin/view-organizer',['user_list'=>$get_view_list]);
		}

	}
	function block_unblock_user(Request $request){
		$id = $request->id;
		#block=>1 #unblock=>2
		$check_block_status = User::where(['id'=>$id])->first();
		if($check_block_status->blockStatus ==1){
			$check_block_status = User::where('id',$id)->update(['blockStatus'=>2]);
			return redirect('user-list')->with('status','User unblocked successfully');
		}else{
			$update_block_status = User::where('id',$id)->update(['blockStatus'=>1]);
			if($check_block_status->device_type == 'I'){
					$message = array('sound' =>1,'message'=>'You are blocked by Admin',
					'notifykey'=>'block_status','data'=>'Mo-Tiv','title'=>'Mo-Tiv');
					$device_token=$check_block_status->device_token;
					//$dd=$this->send_iphone_notification($device_token,'You are blocked by Admin','block_status',$message);
			}   
			if($check_block_status->device_type == 'A'){
				$message = array('sound' =>1,'message'=>'You are blocked by Admin',
				'notifykey'=>'block_status','data'=>'hello','title'=>'Mo-Tiv');
				$device_token=$check_block_status->device_token;
				//$msg=$this->send_android_notification($device_token,'You are blocked by Admin','block_status',$message);
			}
			//print $msg;
			  return redirect('user-list')->with('status','User blocked successfully');
		}
	}
	function block_unblock_organizer(Request $request){
		$id = $request->id;
		$check_block_status = User::where(['id'=>$id])->first();
		if($check_block_status->blockStatus ==1){
			$update = User::where('id',$id)->update(['blockStatus'=>2]);
			return redirect('organizer-list')->with('status','Organizer unblocked successfully');
		}else{
			$update = User::where('id',$id)->update(['blockStatus'=>1]);
			if($check_block_status->device_type == 'I'){
					$message = array('sound' =>1,'message'=>'You are blocked by Admin',
					'notifykey'=>'block_status','data'=>'Mo-Tiv','title'=>'Mo-Tiv');
					$device_token=$check_block_status->device_token;
					//$dd=$this->send_iphone_notification($device_token,'You are blocked by Admin','block_status',$message);
			}   
			if($check_block_status->device_type == 'A'){
				$message = array('sound' =>1,'message'=>'You are blocked by Admin',
				'notifykey'=>'block_status','data'=>'hello','title'=>'Mo-Tiv');
				$device_token=$check_block_status->device_token;
				//$this->send_android_notification($device_token,'You are blocked by Admin','block_status',$message);
			}
			return redirect('organizer-list')->with('status','Organizer blocked successfully');
		}
	}
	function view_user(Request $request){
		$view_id = $request->id;
		$get_view_list = User::where(['role'=>2,'id'=>$view_id])->first();
		if(!empty($get_view_list)){
			return view('admin/view-user',['user_list'=>$get_view_list]);
		}else{
			return view('pageNotFound404');
		}
		
	}
	function edit_user(Request $request ){
		$view_id = $request->id;
		$get_view_list = User::where(['role'=>2,'id'=>$view_id])->first();
		if(!empty($get_view_list)){
			return view('admin/edit-user',['user_list'=>$get_view_list]);
		}else{
			return view('pageNotFound404');
		}
		
		
	}
	
	function delete_user(Request $request ){
		$view_id = $request->id;
		//print $view_id;die;
		if(!empty($view_id)){
			$get_view_list = User::where(['id'=>$view_id])->update(['is_deleted'=>2,'email'=>'']);
			/* DB::table('users')->where(['id'=>$view_id])->delete();
			DB::table('comments')->where(['user_id'=>$view_id])->delete();
			DB::table('contact_us')->where(['user_id'=>$view_id])->delete();
			DB::table('event_list')->where(['user_id'=>$view_id])->delete();
			   
			DB::table('friends')->orwhere(['sender_id'=>$view_id])->orwhere(['receiver_id'=>$view_id])->delete();
			DB::table('invitations')->orwhere(['sender_id'=>$view_id])->orwhere(['receiver_id'=>$view_id])->delete();
			DB::table('likes')->where(['user_id'=>$view_id])->delete();
			DB::table('notification_list')->orwhere(['user_id'=>$view_id])->orwhere(['other_user_id'=>$view_id])->delete();
			DB::table('post_list')->orwhere(['user_id'=>$view_id])->delete();
			DB::table('user_music_interest')->orwhere(['user_id'=>$view_id])->delete();
			DB::table('user_public_interest')->orwhere(['user_id'=>$view_id])->delete(); */
			   
		}
		return redirect('user-list')->with('status','User deleted successfully');
		
	}
	
	function update_user(Request $request ){
		if($request->isMethod('post')){
			// print_r($request->all());die;
			$validator = Validator::make($request->all(), [
				'name' => 'required|min:3|max:20|regex:/^[\pL\s\-]+$/u',
				//'email' => 'required|email|min:3|max:50',
				'phone' => 'nullable|digits_between:8,15|numeric',
				'age' => 'required|numeric',
				'image' => 'nullable|image|mimes:jpeg,png,jpg',
			]);
			if($validator->fails()){
				return Redirect::back()->withErrors($validator)->withInput();
			}
			if(!empty($request->file('image'))){
				$image_name = $request->input('user_id').'_'.str_random(20).'.png';
				$path = $request->file('image')->storeAs('public/user_images',$image_name);
				// $mediaUrl = url(Storage::url($path));
				// $mediaUrl='http://54.206.13.240/sim_admin/storage/app/public/user_images/'.$image_name;
				// $mediaUrl='http://175.176.186.116/webservices/sim_admin/storage/app/public/user_images/'.$image_name;
				$baseUrl = url('/');
				$baseUrl = str_replace('/public','/',$baseUrl);
				$mediaUrl = $baseUrl.'/storage/app/'.$path;
				$update_data = User::where(['id' =>$request->input('user_id')])->update(['image_url'=>$mediaUrl]);
			}
			if(!empty($request->input('music_interest'))){
				$music_interest=implode(",",$request->input('music_interest'));
			}else{
				$music_interest="";
			}
			if(!empty($request->input('public_interest'))){
				$public_interest=implode(",",$request->input('public_interest'));
			}else{
				$public_interest="";
			}
			$update_data = User::where(['id' =>$request->input('user_id')])->update(['name' =>$request->input('name'),
			/* 'email'=>$request->input('email') */'phone_number'=>!empty($request->input('phone')) ? $request->input('phone'):'','music_interest'=>$music_interest,'public_interest'=>$public_interest,'age'=>!empty($request->input('age')) ? $request->input('age') : null]);
			//if($update_data){
				$get_view_list = User::where(['role'=>2,'id'=>$request->input('user_id')])->first();
				Session::flash('message','User Detail updated successfully.');
				//return Redirect('view-use/',$request->input('user_id'))->with('status','User Detail updated successfully');
			   return redirect('view-user/'.$request->input('user_id'))->with('status','User Detail updated successfully');
			 
			//return view('admin/view-user',['user_list'=>$get_view_list]);
		}

	}
	function dashboard(){
		return view('admin/dashboard');
	}
	
	
	function private_events(){
	    $events=DB::table('event_list')
			->where(['post_type'=>2,'users.is_deleted'=>1,'users.status' => 1,'users.blockStatus'=>2])
			->select(['event_list.*'])
			->join('users','users.id','=','event_list.user_id')
			->orderBy('event_list.id','DESC')->get();
		return view('admin/private-events',['events'=>$events]);
	}  
	
	function private_events_schedule_list(){
	    $events=DB::table('event_list')
			->where(['post_type'=>2,'users.is_deleted'=>1,'users.status' => 1,'users.blockStatus'=>2])
			->select(['event_list.*'])
			->join('users','users.id','=','event_list.user_id')
			->orderBy('event_list.id','DESC')->get();
		return view('admin/private-events-schedule-list',['events'=>$events]);
	}
	function block_unblock_event(Request $request){
		$id = $request->id;
		$check_block_status = DB::table('event_list')->where(['id'=>$id])->first();
		if($check_block_status->status ==1){
			$update =DB::table('event_list')->where('id',$id)->update(['status'=>2]);
			$get_friend_name=DB::table('users')->where(['id'=>$check_block_status->user_id])->first();	
			$notify_count=$this->notification_count($check_block_status->user_id);
			if($get_friend_name->device_type == 'I'){
					$message = array('sound' =>1,'message'=>'Your event is approved by Admin',
					'notifykey'=>'event_status','data'=>'Mo-Tiv','title'=>'Mo-Tiv',
					'event_date'=>$check_block_status->event_date,'event_time'=>$check_block_status->event_time,'event_id'=>$check_block_status->id,'notify_count'=>$notify_count);
					$device_token=$get_friend_name->device_token;
					$dd=$this->send_iphone_notification($device_token,'Your event is approved by Admin','event_status',$message);
			}   
			if($get_friend_name->device_type == 'A'){
				$message = array('sound' =>1,'message'=>'Your event is approved by Admin',
				'notifykey'=>'event_status','data'=>'hello','title'=>'Mo-Tiv',
				'event_date'=>$check_block_status->event_date,'event_time'=>$check_block_status->event_time,'event_id'=>$check_block_status->id,'notify_count'=>$notify_count);
				$device_token=$get_friend_name->device_token;
				$this->send_android_notification($device_token,'Your event is approved by Admin','event_status',$message);
			}
			return redirect('private-events')->with('status','Event Un-Approve successfully');
		}else{
			$check_block_status = DB::table('event_list')->where('id',$id)->update(['status'=>1]);
			return redirect('private-events')->with('status','Event Approved successfully');
			
		}
		
	}
	function private_view_event_details(Request $request){
		$view_id = $request->id;
		$event_detail = DB::table('event_list')->where(['id'=>$view_id])->first();
		if(!empty($event_detail)){
			return view('admin/private-view-event-details',['event_detail'=>$event_detail]);			
		}else{
			return view('pageNotFound404');
		}
		
	}
	function edit_private_event_details(Request $request){
		//die;
		$event_id = $request->id;
		$event_detail=DB::table('event_list')->where(['id'=>$event_id])->first();
		return view('admin/edit-private-event-details',['event_detail'=>$event_detail]);
	}
	function edit_public_event(Request $request){
		//die;
		$event_id = $request->id;
		$event_detail=DB::table('event_list')->where(['id'=>$event_id])->first();
		if(!empty($event_detail)){
			return view('admin/edit-public-event-details',['event_detail'=>$event_detail]);
		}else{
			return view('pageNotFound404');
		}
		
	}
	function delete_private_event(Request $request){
		$id=$request->id;
		//die;
		$music_interest=DB::table('event_list')->where(['id'=>$id])->delete();
		$music_interest=DB::table('event_music_interest_list')->where(['event_id'=>$id])->delete();
		$music_interest=DB::table('event_public_interest_list')->where(['event_id'=>$id])->delete();
		$music_interest=DB::table('invitations')->where(['event_id'=>$id])->delete();
		
		$check_post=DB::table('post_list')->where(['event_id'=>$id])->first();
		if(!empty($check_post)){
		    $music_interest=DB::table('likes')->where(['post_id'=>$check_post->id])->delete();
			$music_interest=DB::table('comments')->where(['post_id'=>$check_post->id])->delete();
			$music_interest=DB::table('post_list')->where(['event_id'=>$id])->delete();
		}
		
		return redirect('private-events')->with('status','Event deleted successfully');
	}
	
	function delete_report_event(Request $request){
		$id=$request->id;
		//die;
		$music_interest=DB::table('event_list')->where(['id'=>$id])->delete();
		$music_interest=DB::table('event_music_interest_list')->where(['event_id'=>$id])->delete();
		$music_interest=DB::table('event_public_interest_list')->where(['event_id'=>$id])->delete();
		$music_interest=DB::table('invitations')->where(['event_id'=>$id])->delete();
		
		$check_post=DB::table('post_list')->where(['event_id'=>$id])->first();
		if(!empty($check_post)){
		    $music_interest=DB::table('likes')->where(['post_id'=>$check_post->id])->delete();
			$music_interest=DB::table('comments')->where(['post_id'=>$check_post->id])->delete();
			$music_interest=DB::table('post_list')->where(['event_id'=>$id])->delete();
		}
		Session::flash('status','Event deleted successfully');
		return redirect('event-reports');
		
	}
	function delete_public_event(Request $request){
		
		$id=$request->id;
		//print $id; die;
		$music_interest=DB::table('event_list')->where(['id'=>$id])->delete();
		$music_interest=DB::table('event_music_interest_list')->where(['event_id'=>$id])->delete();
		$music_interest=DB::table('event_public_interest_list')->where(['event_id'=>$id])->delete();
		$music_interest=DB::table('invitations')->where(['event_id'=>$id])->delete();
		
		$check_post=DB::table('post_list')->where(['event_id'=>$id])->first();
		if(!empty($check_post)){
		    $music_interest=DB::table('likes')->where(['post_id'=>$check_post->id])->delete();
			$music_interest=DB::table('comments')->where(['post_id'=>$check_post->id])->delete();
			$music_interest=DB::table('post_list')->where(['event_id'=>$id])->delete();
		}
		
		return redirect('public-events')->with('status','Event deleted successfully');
	}
	function update_public_event_old(Request $request){
		if($request->isMethod('post')){
			//echo '<pre>';
			//print_r($request->all());die;
			$validator = Validator::make($request->all(), [
				// 'event_name' => 'required|min:3|max:100|regex:/^[\pL\s\-]+$/u',
				'event_name' => 'required|min:3|max:100',
				'event_address' => 'required|min:3|max:500',
				'event_description' => 'required|min:3|',
				'event_date' => 'required',   
				'event_time' => 'required',
				'age' => 'max:100|nullable|numeric',
			]);
			
			$event_id = $request->event_id;
			if($validator->fails()){
				return Redirect:: back()->withErrors($validator)->withInput();
			}
			 $check_event=DB::table('event_list')->where(['id'=>$event_id])->first();
			if(!empty($request->file('image'))){
			$image_name = $event_id.'_'.str_random(20).'.png';
			$path = $request->file('image')->storeAs('public/event_media',$image_name);
			// $mediaUrl = url(Storage::url($path));
			// $mediaUrl='http://54.206.13.240/sim_admin/storage/app/public/event_media/'.$image_name;
			// $mediaUrl='http://175.176.186.116/webservices/sim_admin/storage/app/public/event_media/'.$image_name;
			$baseUrl = url('/');
			$baseUrl = str_replace('/public','/',$baseUrl);
			$mediaUrl = $baseUrl.'storage/app/'.$path;
			$update=DB::table('event_list')->where(['id'=>$request->event_id])->update(['event_image_url'=>$mediaUrl]);
			}
				$update=DB::table('event_list')->where(['id'=>$request->event_id])->update(['event_name'=>$request->event_name,'event_location'=>$request->event_address,
																		'description'=>$request->event_description,'event_date'=>$request->event_date,
																		'event_time'=>$request->event_time.':00','age_restrictions'=>$request->age,'end_time'=>$request->end_time.':00','repeat_interval'=>$request->input('repeat_interval')]);
			
			if(!empty($request->public_interest)){
				$check_public=DB::table('event_public_interest_list')->where(['event_id'=>$request->event_id])->first();
				if(!empty($check_public)){
					DB::table('event_public_interest_list')->where(['event_id'=>$request->event_id])->update(['public_interest_id'=>$request->public_interest]);
				}else{
					$update2=DB::table('event_public_interest_list')->insert(['public_interest_id'=>$request->public_interest,'event_id'=>$request->event_id,'updated_at'=>Date('Y-m-d H:i:s'),'created_at' => Date('Y-m-d H:i:s')]);
				}
			}
			
			if(count($request->music_interest)>0){
				$music_interests=implode(",",$request->input('music_interest'));
				DB::table('event_list')->where(['id'=>$request->event_id])->update(['music_int_id'=>$music_interests]);
				$music_id=$request->music_interest;
				foreach($music_id as $music_ids){	
            	    $update2=DB::table('event_music_interest_list')->insert(['music_interest_id'=>$music_ids,'event_id'=>$request->event_id,'updated_at'=>Date('Y-m-d H:i:s'),'created_at' => Date('Y-m-d H:i:s')]);
					} 
					
			}
			
			  #event actual date  
			   $event_date=$request->event_date;
			   #clinet date format
			   $event_date2=date('D j M',strtotime($event_date)); 
			 
			 if($check_event->repeat_interval != $request->input('repeat_interval')){
				#delete events if user change interval
				DB::table('event_schedule')->where(['event_id'=>$event_id])->delete();
				$music_interest=DB::table('invitations')->where(['event_id'=>$event_id])->delete();
				$check_post=DB::table('post_list')->where(['event_id'=>$event_id])->first();
				if(!empty($check_post)){  
					DB::table('likes')->where(['post_id'=>$check_post->id])->delete();
					DB::table('comments')->where(['post_id'=>$check_post->id])->delete();
					DB::table('post_list')->where(['event_id'=>$event_id])->delete();
				}
				#-----------------------------------------
				  
				if($request->input('repeat_interval')=='one_day'){
					$last_id=DB::table('event_schedule')->insertGetId(['event_id'=>$event_id,'event_date2'=>$event_date2,'user_id'=>$check_event->user_id,'event_date'=>$event_date,'event_time'=>$request->event_time.':00','end_time'=>$request->event_time.':00','created_at'=>Date('Y-m-d H:i:s')]);
				}elseif($request->input('repeat_interval')=='monthly'){
					for($i=1;$i<=12;$i++){
					  $last_id=DB::table('event_schedule')->insertGetId(['event_id'=>$event_id,'event_date2'=>$event_date2,'user_id'=>$check_event->user_id,'event_date'=>$event_date,'event_time'=>$request->event_time.':00','end_time'=>$request->event_time.':00','created_at'=>Date('Y-m-d H:i:s')]);
					  $get_date=DB::table('event_schedule')->where(['id'=>$last_id])->first();
					  $event_date=date('Y-m-d', strtotime("+1 months", strtotime($get_date->event_date)));
					  $event_date2=date('D j M',strtotime("+1 months", strtotime($get_date->event_date2))); 
					}  
				}elseif($request->input('repeat_interval')=='weekly'){
					for($i=1;$i<=48;$i++){  
					  $last_id=DB::table('event_schedule')->insertGetId(['event_id'=>$event_id,'event_date2'=>$event_date2,'user_id'=>$check_event->user_id,'event_date'=>$event_date,'event_time'=>$request->event_time.':00','end_time'=>$request->event_time.':00','created_at'=>Date('Y-m-d H:i:s')]);
					  $get_date=DB::table('event_schedule')->where(['id'=>$last_id])->first();
					  $event_date=date('Y-m-d', strtotime("+1 week", strtotime($get_date->event_date)));
					  $event_date2=date('D j M',strtotime("+1 week", strtotime($get_date->event_date2)));
					} 
				}elseif($request->input('repeat_interval')=='2_weekly'){
					for($i=1;$i<=24;$i++){  
					  $last_id=DB::table('event_schedule')->insertGetId(['event_id'=>$event_id,'user_id'=>$check_event->user_id,'event_date'=>$event_date,'event_date2'=>$event_date2,'event_time'=>$request->event_time.':00','end_time'=>$request->event_time.':00','created_at'=>Date('Y-m-d H:i:s')]);
					  $get_date=DB::table('event_schedule')->where(['id'=>$last_id])->first();
					  $event_date=date('Y-m-d', strtotime("+2 week", strtotime($get_date->event_date)));
					  $event_date2=date('D j M',strtotime("+2 week", strtotime($get_date->event_date2)));
					} 
				}
			}
             $get_event=DB::table('event_list')->where(['id'=>$event_id])->first();
             #event updation notification
			$guests = DB::table('invitations')
					->leftJoin('users', 'users.id', '=', 'invitations.receiver_id')
					->where(['request_status'=>1,'sub_event_id'=>$event_id])
					->get();		
			if((count($guests)>0)){
				foreach($guests as $guests){  
				#save invitation notification
					DB::table('notification_list')->insert(['user_id'=>$guests->id,'other_user_id'=>$id,'message'=>'Event detail has been updated','notification_type'=>1,'timestamp'=>round(microtime(true) * 1000),'created_at' =>Date('Y-m-d H:i:s'),'updated_at' =>Date('Y-m-d H:i:s')]);
					#***************************  
					$notify_count=$this->notification_count($guests->id);
					if($guests->device_type == 'I'){
							$message = array('sound' =>1,'message'=>'Event detail has been updated',
							'notifykey'=>'invitation','data'=>'Mo-Tiv','title'=>'Mo-Tiv',
							'event_date'=>$get_event->event_date,'event_time'=>$get_event->event_time,'notify_count'=>$notify_count);
							$device_token=$guests->device_token;
							$dd=$this->send_iphone_notification($device_token,'Event detail has been updated','invitation',$message);
					}   
					if($guests->device_type == 'A'){
						$message = array('sound' =>1,'message'=>'Event detail has been updated',
						'notifykey'=>'invitation','data'=>'hello','title'=>'Mo-Tiv',
						'event_date'=>$get_event->event_date,'event_time'=>$get_event->event_time,'notify_count'=>$notify_count);
						$device_token=$guests->device_token;
						$this->send_android_notification($device_token,'Event detail has been updated','invitation',$message);
					}
				}
			}
			
			//print $update2;die;															
			return redirect('public-events')->with('status','Event updated successfully');
		}
	} 
	
	
	function update_public_event(Request $request){
		$admin=Auth::user();
		if($request->isMethod('post')){
		   // echo '<pre>';
			//print_r($request->all());die;
			$validator = Validator::make($request->all(), [
				//'event_name' => 'required|min:3|max:20|regex:/^[\pL\s\-]+$/u',
				'event_name' => 'required|min:3|max:100',
				'event_address' => 'required|min:3|max:500',
				'event_description' => 'required|min:3|',
				'event_date' => 'required',   
				'event_time' => 'required',
				'end_time' => 'required|different:event_time',
				'age' => 'max:100|nullable|numeric',
				
			]);
			$event_id = $request->event_id;
			if($validator->fails()){
				return Redirect:: back()->withErrors($validator)->withInput();
			}
			$check_event=DB::table('event_list')->where(['id'=>$event_id])->first();
			$image = $request->file('image');
			//print $check_event->event_media_type;die;
			if($check_event->event_media_type == 1){
				if(!empty($image)){
					$get_ext= $image->getClientOriginalExtension();
					if($get_ext == "jpg" || $get_ext == "png"){
						$image_name = $event_id.'_'.str_random(20).'.png';
						$path = $request->file('image')->storeAs('public/event_media',$image_name);
						$baseUrl = url('/');
						$baseUrl = str_replace('/public','/',$baseUrl);
						$mediaUrl = $baseUrl.'/storage/app/'.$path;
						$update=DB::table('event_list')->where(['id'=>$request->event_id])->update(['event_image_url'=>$mediaUrl]);
					}else{
						return Redirect:: back()->with('status','Please upload only image');
					}
				}		
			}else if($check_event->event_media_type ==2){
				
					if(!empty($image)){
						$get_ext= $image->getClientOriginalExtension();
						if($get_ext == "jpg" || $get_ext == "png"){
							$image_name = $event_id.'_'.str_random(20).'.png';
							$path = $request->file('image')->storeAs('public/event_media',$image_name);
							$baseUrl = url('/');
							$baseUrl = str_replace('/public','/',$baseUrl);
							$mediaUrl = $baseUrl.'/storage/app/'.$path;
							$update=DB::table('event_list')->where(['id'=>$request->event_id])->update(['event_image_url'=>$mediaUrl]);
						}else{
							return Redirect:: back()->with('status','Please upload only image');
						}
					}
						
					if(!empty($request->file('video_thumbnail'))){
						$video_thumbnail=$request->file('video_thumbnail');
						$get_ext=$video_thumbnail->getClientOriginalExtension();
						// if($get_ext !== 'mp4'){
							// return Redirect:: back()->with('status','Please upload only mp4 video');
						// }
						$image_name = $event_id.'_'.str_random(20).'.mp4';
						$path = $request->file('video_thumbnail')->storeAs('public/event_media',$image_name);
						$baseUrl = url('/');
						$baseUrl = str_replace('/public','/',$baseUrl);
						$mediaUrl = $baseUrl.'/storage/app/'.$path;
						$update=DB::table('event_list')->where(['id'=>$request->event_id])->update(['event_video_url'=>$mediaUrl]);
					}
				
			}
			$event_date =$request->event_date;
			$get_start_time= $request->event_time.':00';
			$get_end_time = $request->end_time.':00';
			$event_date2=date('D j M',strtotime($event_date)); 
			$event_start_date_time = date('Y-m-d H:i:s', strtotime("$event_date $get_start_time"));
			$event_end_date_time = date('Y-m-d H:i:s', strtotime("$event_date $get_end_time"));
			if(strtotime($event_start_date_time) > strtotime($event_end_date_time)){
				$event_end_date_time = date('Y-m-d H:i:s', strtotime("+1 days", strtotime($event_end_date_time)));
			}
			$update=DB::table('event_list')->where(['id'=>$request->event_id])->update(['event_name'=>$request->event_name,'event_location'=>$request->event_address,'description'=>$request->event_description,'event_date'=>$event_date,'event_date2'=>$event_date2,'event_time'=>$request->event_time.':00','age_restrictions'=>$request->age,'end_time'=>$request->end_time.':00','repeat_interval'=>$request->input('repeat_interval')]);
				$event_start_date_time=$event_date." ".$request->event_time.':00';
				$event_end_date_time=$event_date." ".$request->end_time.':00';
			
			if($check_event->repeat_interval == $request->input('repeat_interval')){
				DB::table('event_schedule')->where(['event_id'=>$event_id])
						->update([
								'event_date'=>$event_date,
								'event_date2'=>date('D j M',strtotime($event_date)),
								'event_time'=>$request->event_time.':00',
								'end_time'=>$request->end_time.':00',
								'event_start_date_time'=>$event_start_date_time,
								'event_end_date_time'=>$event_end_date_time,
								]);
				$get_schedule_events=DB::table('event_schedule')->where(['event_id'=>$event_id])->get();
				foreach($get_schedule_events as $get_schedule_event){
					if($check_event->repeat_interval == 'one_day'){
						DB::table('event_schedule')->where(['id'=>$get_schedule_event->id])
						->update([
								'event_date'=>$event_date,
								'event_date2'=>date('D j M',strtotime($event_date)),
								'event_time'=>$request->event_time.':00',
								'end_time'=>$request->end_time.':00',
								'event_start_date_time'=>$event_start_date_time,
								'event_end_date_time'=>$event_end_date_time,
								]);
					}elseif($check_event->repeat_interval == 'monthly'){
						DB::table('event_schedule')->where(['id'=>$get_schedule_event->id])
						->update([
								'event_date'=>$event_date,
								'event_date2'=>date('D j M',strtotime($event_date)),
								'event_time'=>$request->event_time.':00',
								'end_time'=>$request->end_time.':00',
								'event_start_date_time'=>$event_start_date_time,
								'event_end_date_time'=>$event_end_date_time,
								]);
						$get_date=DB::table('event_schedule')->where(['id'=>$get_schedule_event->id])->first();
						$event_date=date('Y-m-d', strtotime("+1 months", strtotime($get_date->event_date)));
						$event_date2=date('D j M',strtotime("+1 months", strtotime($get_date->event_date2))); 
						$event_start_date_time = date('Y-m-d H:i:s', strtotime("$event_date $get_start_time"));
						$event_end_date_time = date('Y-m-d H:i:s', strtotime("$event_date $get_end_time"));
						if(strtotime($event_start_date_time) > strtotime($event_end_date_time)){
							$event_end_date_time = date('Y-m-d H:i:s', strtotime("+1 days", strtotime($event_end_date_time)));
						}
						 
					}elseif($check_event->repeat_interval == 'weekly'){
						DB::table('event_schedule')->where(['id'=>$get_schedule_event->id])
						->update([
								'event_date'=>$event_date,
								'event_date2'=>date('D j M',strtotime($event_date)),
								'event_time'=>$request->event_time.':00',
								'end_time'=>$request->end_time.':00',
								'event_start_date_time'=>$event_start_date_time,
								'event_end_date_time'=>$event_end_date_time,
								]);
						$get_date=DB::table('event_schedule')->where(['id'=>$get_schedule_event->id])->first();
						$event_date=date('Y-m-d', strtotime("+1 week", strtotime($get_date->event_date)));
						$event_date2=date('D j M',strtotime("+1 week", strtotime($get_date->event_date2)));
					  
						$event_start_date_time = date('Y-m-d H:i:s', strtotime("$event_date $get_start_time"));
						$event_end_date_time = date('Y-m-d H:i:s', strtotime("$event_date $get_end_time"));
						if(strtotime($event_start_date_time) > strtotime($event_end_date_time)){
							$event_end_date_time = date('Y-m-d H:i:s', strtotime("+1 days", strtotime($event_end_date_time)));
						}
						
					}elseif($check_event->repeat_interval == '2_weekly'){
						DB::table('event_schedule')->where(['id'=>$get_schedule_event->id])
						->update([
								'event_date'=>$event_date,
								'event_date2'=>date('D j M',strtotime($event_date)),
								'event_time'=>$request->event_time.':00',
								'end_time'=>$request->end_time.':00',
								'event_start_date_time'=>$event_start_date_time,
								'event_end_date_time'=>$event_end_date_time,
								]); 
					  
						$get_date=DB::table('event_schedule')->where(['id'=>$get_schedule_event->id])->first();
						$event_date=date('Y-m-d', strtotime("+2 week", strtotime($get_date->event_date)));
						$event_date2=date('D j M',strtotime("+2 week", strtotime($get_date->event_date2)));
						$event_start_date_time = date('Y-m-d H:i:s', strtotime("$event_date $get_start_time"));
						$event_end_date_time = date('Y-m-d H:i:s', strtotime("$event_date $get_end_time"));
						if(strtotime($event_start_date_time) > strtotime($event_end_date_time)){
							$event_end_date_time = date('Y-m-d H:i:s', strtotime("+1 days", strtotime($event_end_date_time)));
						}
						 
					}					
				}
				
				
			}
			
			if(!empty($request->public_interest)){
				$check_public=DB::table('event_public_interest_list')->where(['event_id'=>$request->event_id])->first();
				if(!empty($check_public)){
					DB::table('event_public_interest_list')->where(['event_id'=>$request->event_id])->update(['public_interest_id'=>$request->public_interest]);
				}else{
					$update2=DB::table('event_public_interest_list')->insert(['public_interest_id'=>$request->public_interest,'event_id'=>$request->event_id,'updated_at'=>Date('Y-m-d H:i:s'),'created_at' => Date('Y-m-d H:i:s')]);
				}
			}
			DB::table('event_music_interest_list')->where(['event_id'=>$request->event_id])->delete();
			if(count($request->music_interest)>0){
				$music_interests=implode(",",$request->input('music_interest'));
				DB::table('event_list')->where(['id'=>$request->event_id])->update(['music_int_id'=>$music_interests]);
				$music_id=$request->music_interest;
				foreach($music_id as $music_ids){	
            	    $update2=DB::table('event_music_interest_list')->insert(['music_interest_id'=>$music_ids,'event_id'=>$request->event_id,'updated_at'=>Date('Y-m-d H:i:s'),'created_at' => Date('Y-m-d H:i:s')]);
				} 
					
			}
			  
			  #event actual date  
			   $event_date=$request->event_date;
			   #clinet date format
			   $event_date2=date('D j M',strtotime($event_date)); 
			 
			 if($check_event->repeat_interval != $request->input('repeat_interval')){
				#delete events if user change interval
				DB::table('event_schedule')->where(['event_id'=>$event_id])->delete();
				$music_interest=DB::table('invitations')->where(['event_id'=>$event_id])->delete();
				$check_post=DB::table('post_list')->where(['event_id'=>$event_id])->first();
				if(!empty($check_post)){  
					DB::table('likes')->where(['post_id'=>$check_post->id])->delete();
					DB::table('comments')->where(['post_id'=>$check_post->id])->delete();
					DB::table('post_list')->where(['event_id'=>$event_id])->delete();
				}
				#-----------------------------------------
				if($request->input('repeat_interval')=='one_day'){
					$last_id=DB::table('event_schedule')->insertGetId(['event_id'=>$event_id,'event_date2'=>$event_date2,'user_id'=>$check_event->user_id,'event_date'=>$event_date,'event_time'=>$request->event_time.':00','end_time'=>$get_end_time.':00','event_start_date_time'=>$event_start_date_time,'event_end_date_time'=>$event_end_date_time,'created_at'=>Date('Y-m-d H:i:s')]);
				}elseif($request->input('repeat_interval')=='monthly'){
					for($i=1;$i<=12;$i++){
						$last_id=DB::table('event_schedule')->insertGetId(['event_id'=>$event_id,'event_date2'=>$event_date2,'user_id'=>$check_event->user_id,'event_date'=>$event_date,'event_time'=>$request->event_time.':00','end_time'=>$get_end_time.':00','event_start_date_time'=>$event_start_date_time,'event_end_date_time'=>$event_end_date_time,'created_at'=>Date('Y-m-d H:i:s')]);
						$get_date=DB::table('event_schedule')->where(['id'=>$last_id])->first();
						$event_date=date('Y-m-d', strtotime("+1 months", strtotime($get_date->event_date)));
						$event_date2=date('D j M',strtotime("+1 months", strtotime($get_date->event_date2))); 
					  
						$event_start_date_time = date('Y-m-d H:i:s', strtotime("$event_date $get_start_time"));
						$event_end_date_time = date('Y-m-d H:i:s', strtotime("$event_date $get_end_time"));
						if(strtotime($event_start_date_time) > strtotime($event_end_date_time)){
							$event_end_date_time = date('Y-m-d H:i:s', strtotime("+1 days", strtotime($event_end_date_time)));
						}
					}  
				}elseif($request->input('repeat_interval')=='weekly'){
					for($i=1;$i<=48;$i++){  
						$last_id=DB::table('event_schedule')->insertGetId(['event_id'=>$event_id,'event_date2'=>$event_date2,'user_id'=>$check_event->user_id,'event_date'=>$event_date,'event_time'=>$request->event_time.':00','end_time'=>$get_end_time.':00','event_start_date_time'=>$event_start_date_time,'event_end_date_time'=>$event_end_date_time,'created_at'=>Date('Y-m-d H:i:s')]);	
						$get_date=DB::table('event_schedule')->where(['id'=>$last_id])->first();
						$event_date=date('Y-m-d', strtotime("+1 week", strtotime($get_date->event_date)));
						$event_date2=date('D j M',strtotime("+1 week", strtotime($get_date->event_date2)));
					  
						$event_start_date_time = date('Y-m-d H:i:s', strtotime("$event_date $get_start_time"));
						$event_end_date_time = date('Y-m-d H:i:s', strtotime("$event_date $get_end_time"));
						if(strtotime($event_start_date_time) > strtotime($event_end_date_time)){
							$event_end_date_time = date('Y-m-d H:i:s', strtotime("+1 days", strtotime($event_end_date_time)));
						}
					} 
				}elseif($request->input('repeat_interval')=='2_weekly'){
					for($i=1;$i<=24;$i++){  
					  $last_id=DB::table('event_schedule')->insertGetId(['event_id'=>$event_id,'event_date2'=>$event_date2,'user_id'=>$check_event->user_id,'event_date'=>$event_date,'event_time'=>$request->event_time.':00','end_time'=>$get_end_time.':00','event_start_date_time'=>$event_start_date_time,'event_end_date_time'=>$event_end_date_time,'created_at'=>Date('Y-m-d H:i:s')]);	
					  $get_date=DB::table('event_schedule')->where(['id'=>$last_id])->first();
					  $event_date=date('Y-m-d', strtotime("+2 week", strtotime($get_date->event_date)));
					  $event_date2=date('D j M',strtotime("+2 week", strtotime($get_date->event_date2)));
					  
					  $event_start_date_time = date('Y-m-d H:i:s', strtotime("$event_date $get_start_time"));
						$event_end_date_time = date('Y-m-d H:i:s', strtotime("$event_date $get_end_time"));
						if(strtotime($event_start_date_time) > strtotime($event_end_date_time)){
							$event_end_date_time = date('Y-m-d H:i:s', strtotime("+1 days", strtotime($event_end_date_time)));
						}
					} 
				}
			}
            $get_event=DB::table('event_list')->where(['id'=>$event_id])->first();
             #event updation notification
			$guests = DB::table('invitations')
					->leftJoin('users', 'users.id', '=', 'invitations.receiver_id')
					->where(['request_status'=>1,'sub_event_id'=>$event_id])
					->get();		
			if((count($guests)>0)){
				foreach($guests as $guests){  
				#save invitation notification
					DB::table('notification_list')->insert(['user_id'=>$guests->id,'other_user_id'=>$admin->id,'message'=>'Event detail has been updated','notification_type'=>1,'timestamp'=>round(microtime(true) * 1000),'created_at' =>Date('Y-m-d H:i:s'),'updated_at' =>Date('Y-m-d H:i:s')]);
					#***************************  
					if($guests->device_type == 'I'){
							$message = array('sound' =>1,'message'=>'Event detail has been updated',
							'notifykey'=>'invitation','data'=>'Mo-Tiv','title'=>'Mo-Tiv',
							'event_date'=>$get_event->event_date,'event_time'=>$get_event->event_time);
							$device_token=$guests->device_token;
							$dd=$this->send_iphone_notification($device_token,'Event detail has been updated','invitation',$message);
					}   
					if($guests->device_type == 'A'){
						$message = array('sound' =>1,'message'=>'Event detail has been updated',
						'notifykey'=>'invitation','data'=>'hello','title'=>'Mo-Tiv',
						'event_date'=>$get_event->event_date,'event_time'=>$get_event->event_time);
						$device_token=$guests->device_token;
						$this->send_android_notification($device_token,'Event detail has been updated','invitation',$message);
					}
				}
			}			 
			//print $update2;die;															
			return redirect('public-events')->with('status','Event updated successfully');
		}
	}	
		
	function update_private_event_old(Request $request){
		if($request->isMethod('post')){
		   // echo '<pre>';
			//print_r($request->all());die;
			$validator = Validator::make($request->all(), [
				'event_name' => 'required|min:3|max:20|regex:/^[\pL\s\-]+$/u',
				'event_address' => 'required|min:3|max:500',
				'event_description' => 'required|min:3|',
				'event_date' => 'required',   
				'event_time' => 'required',
				'age' => 'max:100|nullable|numeric',
				
			]);
			$event_id = $request->event_id;
			if($validator->fails()){
				return Redirect:: back()->withErrors($validator)->withInput();
			}
			 $check_event=DB::table('event_list')->where(['id'=>$event_id])->first();
			if(!empty($request->file('image'))){
			$image_name = $event_id.'_'.str_random(20).'.png';
			$path = $request->file('image')->storeAs('public/event_media',$image_name);
			$mediaUrl = url(Storage::url($path));
			// $mediaUrl='http://54.206.13.240/sim_admin/storage/app/public/event_media/'.$image_name;
			// $mediaUrl='http://175.176.186.116/webservices/sim_admin/storage/app/public/event_media/'.$image_name;
			$baseUrl = url('/');
			$baseUrl = str_replace('/public','/',$baseUrl);
			$mediaUrl = $baseUrl.'storage/app/'.$path;
			$update=DB::table('event_list')->where(['id'=>$request->event_id])->update(['event_image_url'=>$mediaUrl]);
			}
			
			$update=DB::table('event_list')->where(['id'=>$request->event_id])->update(['event_name'=>$request->event_name,'event_location'=>$request->event_address,
																		'description'=>$request->event_description,'event_date'=>$request->event_date,
																		'event_time'=>$request->event_time.':00','age_restrictions'=>$request->age,'end_time'=>$request->end_time.':00','repeat_interval'=>$request->input('repeat_interval')]);
			if(!empty($request->public_interest)){
				$check_public=DB::table('event_public_interest_list')->where(['event_id'=>$request->event_id])->first();
				if(!empty($check_public)){
					DB::table('event_public_interest_list')->where(['event_id'=>$request->event_id])->update(['public_interest_id'=>$request->public_interest]);
				}else{
					$update2=DB::table('event_public_interest_list')->insert(['public_interest_id'=>$request->public_interest,'event_id'=>$request->event_id,'updated_at'=>Date('Y-m-d H:i:s'),'created_at' => Date('Y-m-d H:i:s')]);
				}
			}
			DB::table('event_music_interest_list')->where(['event_id'=>$request->event_id])->delete();
			if(count($request->music_interest)>0){
				$music_interests=implode(",",$request->input('music_interest'));
				DB::table('event_list')->where(['id'=>$request->event_id])->update(['music_int_id'=>$music_interests]);
				$music_id=$request->music_interest;
				foreach($music_id as $music_ids){	
            	    $update2=DB::table('event_music_interest_list')->insert(['music_interest_id'=>$music_ids,'event_id'=>$request->event_id,'updated_at'=>Date('Y-m-d H:i:s'),'created_at' => Date('Y-m-d H:i:s')]);
				} 
					
			}
			  
			  #event actual date  
			   $event_date=$request->event_date;
			   #clinet date format
			   $event_date2=date('D j M',strtotime($event_date)); 
			 
			 if($check_event->repeat_interval != $request->input('repeat_interval')){
				#delete events if user change interval
				DB::table('event_schedule')->where(['event_id'=>$event_id])->delete();
				$music_interest=DB::table('invitations')->where(['event_id'=>$event_id])->delete();
				$check_post=DB::table('post_list')->where(['event_id'=>$event_id])->first();
				if(!empty($check_post)){  
					DB::table('likes')->where(['post_id'=>$check_post->id])->delete();
					DB::table('comments')->where(['post_id'=>$check_post->id])->delete();
					DB::table('post_list')->where(['event_id'=>$event_id])->delete();
				}
				#-----------------------------------------
				if($request->input('repeat_interval')=='one_day'){
					$last_id=DB::table('event_schedule')->insertGetId(['event_id'=>$event_id,'event_date2'=>$event_date2,'user_id'=>$check_event->user_id,'event_date'=>$event_date,'event_time'=>$request->event_time.':00','end_time'=>$request->event_time.':00','created_at'=>Date('Y-m-d H:i:s')]);
				}elseif($request->input('repeat_interval')=='monthly'){
					for($i=1;$i<=12;$i++){
					  $last_id=DB::table('event_schedule')->insertGetId(['event_id'=>$event_id,'event_date2'=>$event_date2,'user_id'=>$check_event->user_id,'event_date'=>$event_date,'event_time'=>$request->event_time.':00','end_time'=>$request->event_time.':00','created_at'=>Date('Y-m-d H:i:s')]);
					  $get_date=DB::table('event_schedule')->where(['id'=>$last_id])->first();
					  $event_date=date('Y-m-d', strtotime("+1 months", strtotime($get_date->event_date)));
					  $event_date2=date('D j M',strtotime("+1 months", strtotime($get_date->event_date2))); 
					}  
				}elseif($request->input('repeat_interval')=='weekly'){
					for($i=1;$i<=48;$i++){  
					  $last_id=DB::table('event_schedule')->insertGetId(['event_id'=>$event_id,'event_date2'=>$event_date2,'user_id'=>$check_event->user_id,'event_date'=>$event_date,'event_time'=>$request->event_time.':00','end_time'=>$request->event_time.':00','created_at'=>Date('Y-m-d H:i:s')]);
					  $get_date=DB::table('event_schedule')->where(['id'=>$last_id])->first();
					  $event_date=date('Y-m-d', strtotime("+1 week", strtotime($get_date->event_date)));
					  $event_date2=date('D j M',strtotime("+1 week", strtotime($get_date->event_date2)));
					} 
				}elseif($request->input('repeat_interval')=='2_weekly'){
					for($i=1;$i<=24;$i++){  
					  $last_id=DB::table('event_schedule')->insertGetId(['event_id'=>$event_id,'user_id'=>$check_event->user_id,'event_date'=>$event_date,'event_date2'=>$event_date2,'event_time'=>$request->event_time.':00','end_time'=>$request->event_time.':00','created_at'=>Date('Y-m-d H:i:s')]);
					  $get_date=DB::table('event_schedule')->where(['id'=>$last_id])->first();
					  $event_date=date('Y-m-d', strtotime("+2 week", strtotime($get_date->event_date)));
					  $event_date2=date('D j M',strtotime("+2 week", strtotime($get_date->event_date2)));
					} 
				}
			}
             $get_event=DB::table('event_list')->where(['id'=>$event_id])->first();
             #event updation notification
			$guests = DB::table('invitations')
					->leftJoin('users', 'users.id', '=', 'invitations.receiver_id')
					->where(['request_status'=>1,'sub_event_id'=>$event_id])
					->get();		
			if((count($guests)>0)){
				foreach($guests as $guests){  
				#save invitation notification
					DB::table('notification_list')->insert(['user_id'=>$guests->id,'other_user_id'=>$get_event->user_id,'message'=>'Event detail has been updated','notification_type'=>1,'timestamp'=>round(microtime(true) * 1000),'created_at' =>Date('Y-m-d H:i:s'),'updated_at' =>Date('Y-m-d H:i:s')]);
					#***************************  
					$notify_count=$this->notification_count($guests->id);
					if($guests->device_type == 'I'){
							$message = array('sound' =>1,'message'=>'Event detail has been updated',
							'notifykey'=>'invitation','data'=>'Mo-Tiv','title'=>'Mo-Tiv',
							'event_date'=>$get_event->event_date,'event_time'=>$get_event->event_time,'notify_count'=>$notify_count);
							$device_token=$guests->device_token;
							$dd=$this->send_iphone_notification($device_token,'Event detail has been updated','invitation',$message);
					}   
					if($guests->device_type == 'A'){
						$message = array('sound' =>1,'message'=>'Event detail has been updated',
						'notifykey'=>'invitation','data'=>'hello','title'=>'Mo-Tiv',
						'event_date'=>$get_event->event_date,'event_time'=>$get_event->event_time,'notify_count'=>$notify_count);
						$device_token=$guests->device_token;
						$this->send_android_notification($device_token,'Event detail has been updated','invitation',$message);
					}
				}
			}

				#event updation notification
			$sub_admins = DB::table('event_sub_admins')
					->leftJoin('users', 'users.id', '=', 'event_sub_admins.user_id')
					->where(['event_id'=>$get_event->id])
					->get();
			 //echo '<pre>';		
           //print_r($guests);					
			if((count($sub_admins)>0)){  
				foreach($sub_admins as $sub_admin){
				#save invitation notification  
					DB::table('notification_list')->insert(['user_id'=>$sub_admin->id,'other_user_id'=>$get_event->user_id,'message'=>$check_event->event_name.':'.'Event detail has been updated','notification_type'=>1,'timestamp'=>round(microtime(true) * 1000),'created_at' =>Date('Y-m-d H:i:s'),'updated_at' =>Date('Y-m-d H:i:s')]);
					#***************************  
					$notify_count=$this->notification_count($sub_admin->id);
					if($sub_admin->device_type == 'I'){
							$message = array('sound' =>1,'message'=>$check_event->event_name.':'.'Event detail has been updated',
							'notifykey'=>'invite_invitation','data'=>'Mo-Tiv','title'=>'Mo-Tiv',
							'event_date'=>$get_event->event_date,'event_time'=>$get_event->event_time,'notify_count'=>$notify_count);
							$device_token=$sub_admin->device_token;
							$dd=$this->send_iphone_notification($device_token,$check_event->event_name.':'.'Event detail has been updated','invite_invitation',$message);
					    
					}     
					if($sub_admin->device_type == 'A'){
						$message = array('sound' =>1,'message'=>$check_event->event_name.':'.'Event detail has been updated',
						'notifykey'=>'invite_invitation','data'=>'hello','title'=>'Mo-Tiv',
						'event_date'=>$get_event->event_date,'event_time'=>$get_event->event_time,'notify_count'=>$notify_count);
						$device_token=$sub_admin->device_token;
						$rr=$this->send_android_notification($device_token,$check_event->event_name.':'.'Event detail has been updated','invite_invitation',$message);
					 
					}
				}
			}
			#***************************			
			//print $update2;die;															
			return redirect('private-events')->with('status','Event updated successfully');
		}
	}    
	
	function update_private_event(Request $request){
		$admin=Auth::user();
		if($request->isMethod('post')){
		   // echo '<pre>';
			//print_r($request->all());die;
			$validator = Validator::make($request->all(), [
				//'event_name' => 'required|min:3|max:20|regex:/^[\pL\s\-]+$/u',
				'event_name' => 'required|min:3|max:100',
				'event_address' => 'required|min:3|max:500',
				'event_description' => 'required|min:3|',
				'event_date' => 'required',   
				'event_time' => 'required',
				'end_time' => 'required|different:event_time',
				'age' => 'max:100|nullable|numeric',
				
			]);
			$event_id = $request->event_id;
			if($validator->fails()){
				return Redirect:: back()->withErrors($validator)->withInput();
			}
			$check_event=DB::table('event_list')->where(['id'=>$event_id])->first();
			$image = $request->file('image');
			//print $check_event->event_media_type;die;
			if($check_event->event_media_type == 1){
				if(!empty($image)){
					$get_ext= $image->getClientOriginalExtension();
					if($get_ext == "jpg" || $get_ext == "png"){
						$image_name = $event_id.'_'.str_random(20).'.png';
						$path = $request->file('image')->storeAs('public/event_media',$image_name);
						$baseUrl = url('/');
						$baseUrl = str_replace('/public','/',$baseUrl);
						$mediaUrl = $baseUrl.'storage/app/'.$path;
						$update=DB::table('event_list')->where(['id'=>$request->event_id])->update(['event_image_url'=>$mediaUrl]);
					}else{
						return Redirect:: back()->with('status','Please upload only image');
					}
				}		
			}else if($check_event->event_media_type ==2){
				if(!empty($image)){
					$get_ext= $image->getClientOriginalExtension();
					if($get_ext == "jpg" || $get_ext == "png"){
						$image_name = $event_id.'_'.str_random(20).'.png';
						$path = $request->file('image')->storeAs('public/event_media',$image_name);
						$baseUrl = url('/');
						$baseUrl = str_replace('/public','/',$baseUrl);
						$mediaUrl = $baseUrl.'storage/app/'.$path;
						$update=DB::table('event_list')->where(['id'=>$request->event_id])->update(['event_image_url'=>$mediaUrl]);
					}else{
						return Redirect:: back()->with('status','Please upload only image');
					}
				}
						
				if(!empty($request->file('video_thumbnail'))){
					$video_thumbnail=$request->file('video_thumbnail');
					$get_ext=$video_thumbnail->getClientOriginalExtension();
					// if($get_ext !== 'mp4'){
						// return Redirect:: back()->with('status','Please upload only video');
					// }
					$image_name = $event_id.'_'.str_random(20).'.mp4';
					$path = $request->file('video_thumbnail')->storeAs('public/event_media',$image_name);
					$baseUrl = url('/');
					$baseUrl = str_replace('/public','/',$baseUrl);
					$mediaUrl = $baseUrl.'storage/app/'.$path;
					$update=DB::table('event_list')->where(['id'=>$request->event_id])->update(['event_video_url'=>$mediaUrl]);
				}
				
			}
			$event_date =$request->event_date;
			$get_start_time= $request->event_time.':00';
			$get_end_time = $request->end_time.':00';
			$event_date2=date('D j M',strtotime($event_date)); 
			$event_start_date_time = date('Y-m-d H:i:s', strtotime("$event_date $get_start_time"));
			$event_end_date_time = date('Y-m-d H:i:s', strtotime("$event_date $get_end_time"));
			if(strtotime($event_start_date_time) > strtotime($event_end_date_time)){
				$event_end_date_time = date('Y-m-d H:i:s', strtotime("+1 days", strtotime($event_end_date_time)));
			}
			$update=DB::table('event_list')->where(['id'=>$request->event_id])->update(['event_name'=>$request->event_name,'event_location'=>$request->event_address,
																		'description'=>$request->event_description,'event_date'=>$event_date,'event_date2'=>$event_date2,
																		'event_time'=>$request->event_time.':00','age_restrictions'=>$request->age,'end_time'=>$request->end_time.':00','repeat_interval'=>$request->input('repeat_interval')]);
			$event_start_date_time=$event_date." ".$request->event_time.':00';
			$event_end_date_time=$event_date." ".$request->end_time.':00';
			
			if($check_event->repeat_interval == $request->input('repeat_interval')){
				DB::table('event_schedule')->where(['event_id'=>$event_id])
						->update([
								'event_date'=>$event_date,
								'event_date2'=>date('D j M',strtotime($event_date)),
								'event_time'=>$request->event_time.':00',
								'end_time'=>$request->end_time.':00',
								'event_start_date_time'=>$event_start_date_time,
								'event_end_date_time'=>$event_end_date_time,
								]);
				$get_schedule_events=DB::table('event_schedule')->where(['event_id'=>$event_id])->get();
				foreach($get_schedule_events as $get_schedule_event){
					if($check_event->repeat_interval == 'one_day'){
						DB::table('event_schedule')->where(['id'=>$get_schedule_event->id])
						->update([
								'event_date'=>$event_date,
								'event_date2'=>date('D j M',strtotime($event_date)),
								'event_time'=>$request->event_time.':00',
								'end_time'=>$request->end_time.':00',
								'event_start_date_time'=>$event_start_date_time,
								'event_end_date_time'=>$event_end_date_time,
								]);
					}elseif($check_event->repeat_interval == 'monthly'){
						DB::table('event_schedule')->where(['id'=>$get_schedule_event->id])
						->update([
								'event_date'=>$event_date,
								'event_date2'=>date('D j M',strtotime($event_date)),
								'event_time'=>$request->event_time.':00',
								'end_time'=>$request->end_time.':00',
								'event_start_date_time'=>$event_start_date_time,
								'event_end_date_time'=>$event_end_date_time,
								]);
						$get_date=DB::table('event_schedule')->where(['id'=>$get_schedule_event->id])->first();
						$event_date=date('Y-m-d', strtotime("+1 months", strtotime($get_date->event_date)));
						$event_date2=date('D j M',strtotime("+1 months", strtotime($get_date->event_date2))); 
						$event_start_date_time = date('Y-m-d H:i:s', strtotime("$event_date $get_start_time"));
						$event_end_date_time = date('Y-m-d H:i:s', strtotime("$event_date $get_end_time"));
						if(strtotime($event_start_date_time) > strtotime($event_end_date_time)){
							$event_end_date_time = date('Y-m-d H:i:s', strtotime("+1 days", strtotime($event_end_date_time)));
						}
						 
					}elseif($check_event->repeat_interval == 'weekly'){
						DB::table('event_schedule')->where(['id'=>$get_schedule_event->id])
						->update([
								'event_date'=>$event_date,
								'event_date2'=>date('D j M',strtotime($event_date)),
								'event_time'=>$request->event_time.':00',
								'end_time'=>$request->end_time.':00',
								'event_start_date_time'=>$event_start_date_time,
								'event_end_date_time'=>$event_end_date_time,
								]);
						$get_date=DB::table('event_schedule')->where(['id'=>$get_schedule_event->id])->first();
						$event_date=date('Y-m-d', strtotime("+1 week", strtotime($get_date->event_date)));
						$event_date2=date('D j M',strtotime("+1 week", strtotime($get_date->event_date2)));
					  
						$event_start_date_time = date('Y-m-d H:i:s', strtotime("$event_date $get_start_time"));
						$event_end_date_time = date('Y-m-d H:i:s', strtotime("$event_date $get_end_time"));
						if(strtotime($event_start_date_time) > strtotime($event_end_date_time)){
							$event_end_date_time = date('Y-m-d H:i:s', strtotime("+1 days", strtotime($event_end_date_time)));
						}
						
					}elseif($check_event->repeat_interval == '2_weekly'){
						DB::table('event_schedule')->where(['id'=>$get_schedule_event->id])
						->update([
								'event_date'=>$event_date,
								'event_date2'=>date('D j M',strtotime($event_date)),
								'event_time'=>$request->event_time.':00',
								'end_time'=>$request->end_time.':00',
								'event_start_date_time'=>$event_start_date_time,
								'event_end_date_time'=>$event_end_date_time,
								]); 
					  
						$get_date=DB::table('event_schedule')->where(['id'=>$get_schedule_event->id])->first();
						$event_date=date('Y-m-d', strtotime("+2 week", strtotime($get_date->event_date)));
						$event_date2=date('D j M',strtotime("+2 week", strtotime($get_date->event_date2)));
						$event_start_date_time = date('Y-m-d H:i:s', strtotime("$event_date $get_start_time"));
						$event_end_date_time = date('Y-m-d H:i:s', strtotime("$event_date $get_end_time"));
						if(strtotime($event_start_date_time) > strtotime($event_end_date_time)){
							$event_end_date_time = date('Y-m-d H:i:s', strtotime("+1 days", strtotime($event_end_date_time)));
						}
						 
					}					
				}
				
				
			}
			
			if(!empty($request->public_interest)){
				$check_public=DB::table('event_public_interest_list')->where(['event_id'=>$request->event_id])->first();
				if(!empty($check_public)){
					DB::table('event_public_interest_list')->where(['event_id'=>$request->event_id])->update(['public_interest_id'=>$request->public_interest]);
				}else{
					$update2=DB::table('event_public_interest_list')->insert(['public_interest_id'=>$request->public_interest,'event_id'=>$request->event_id,'updated_at'=>Date('Y-m-d H:i:s'),'created_at' => Date('Y-m-d H:i:s')]);
				}
			}
			DB::table('event_music_interest_list')->where(['event_id'=>$request->event_id])->delete();
			if(count($request->music_interest)>0){
				$music_interests=implode(",",$request->input('music_interest'));
				DB::table('event_list')->where(['id'=>$request->event_id])->update(['music_int_id'=>$music_interests]);
				$music_id=$request->music_interest;
				foreach($music_id as $music_ids){	
            	    $update2=DB::table('event_music_interest_list')->insert(['music_interest_id'=>$music_ids,'event_id'=>$request->event_id,'updated_at'=>Date('Y-m-d H:i:s'),'created_at' => Date('Y-m-d H:i:s')]);
				} 
					
			}
			  #event actual date  
			   $event_date=$request->event_date;
			   #clinet date format
			   $event_date2=date('D j M',strtotime($event_date)); 
			 
			 if($check_event->repeat_interval != $request->input('repeat_interval')){
				#delete events if user change interval
				DB::table('event_schedule')->where(['event_id'=>$event_id])->delete();
				$music_interest=DB::table('invitations')->where(['event_id'=>$event_id])->delete();
				$check_post=DB::table('post_list')->where(['event_id'=>$event_id])->first();
				if(!empty($check_post)){  
					DB::table('likes')->where(['post_id'=>$check_post->id])->delete();
					DB::table('comments')->where(['post_id'=>$check_post->id])->delete();
					DB::table('post_list')->where(['event_id'=>$event_id])->delete();
				}
				#-----------------------------------------
				if($request->input('repeat_interval')=='one_day'){
					$last_id=DB::table('event_schedule')->insertGetId(['event_id'=>$event_id,'event_date2'=>$event_date2,'user_id'=>$check_event->user_id,'event_date'=>$event_date,'event_time'=>$request->event_time.':00','end_time'=>$get_end_time,'event_start_date_time'=>$event_start_date_time,'event_end_date_time'=>$event_end_date_time,'created_at'=>Date('Y-m-d H:i:s')]);
				}elseif($request->input('repeat_interval')=='monthly'){
					for($i=1;$i<=12;$i++){
						$last_id=DB::table('event_schedule')->insertGetId(['event_id'=>$event_id,'event_date2'=>$event_date2,'user_id'=>$check_event->user_id,'event_date'=>$event_date,'event_time'=>$request->event_time.':00','end_time'=>$get_end_time,'event_start_date_time'=>$event_start_date_time,'event_end_date_time'=>$event_end_date_time,'created_at'=>Date('Y-m-d H:i:s')]);
						$get_date=DB::table('event_schedule')->where(['id'=>$last_id])->first();
						$event_date=date('Y-m-d', strtotime("+1 months", strtotime($get_date->event_date)));
						$event_date2=date('D j M',strtotime("+1 months", strtotime($get_date->event_date2))); 
					  
						$event_start_date_time = date('Y-m-d H:i:s', strtotime("$event_date $get_start_time"));
						$event_end_date_time = date('Y-m-d H:i:s', strtotime("$event_date $get_end_time"));
						if(strtotime($event_start_date_time) > strtotime($event_end_date_time)){
							$event_end_date_time = date('Y-m-d H:i:s', strtotime("+1 days", strtotime($event_end_date_time)));
						}
					}  
				}elseif($request->input('repeat_interval')=='weekly'){
					for($i=1;$i<=48;$i++){  
						$last_id=DB::table('event_schedule')->insertGetId(['event_id'=>$event_id,'event_date2'=>$event_date2,'user_id'=>$check_event->user_id,'event_date'=>$event_date,'event_time'=>$request->event_time.':00','end_time'=>$get_end_time,'event_start_date_time'=>$event_start_date_time,'event_end_date_time'=>$event_end_date_time,'created_at'=>Date('Y-m-d H:i:s')]);	
						$get_date=DB::table('event_schedule')->where(['id'=>$last_id])->first();
						$event_date=date('Y-m-d', strtotime("+1 week", strtotime($get_date->event_date)));
						$event_date2=date('D j M',strtotime("+1 week", strtotime($get_date->event_date2)));
					  
						$event_start_date_time = date('Y-m-d H:i:s', strtotime("$event_date $get_start_time"));
						$event_end_date_time = date('Y-m-d H:i:s', strtotime("$event_date $get_end_time"));
						if(strtotime($event_start_date_time) > strtotime($event_end_date_time)){
							$event_end_date_time = date('Y-m-d H:i:s', strtotime("+1 days", strtotime($event_end_date_time)));
						}
					} 
				}elseif($request->input('repeat_interval')=='2_weekly'){
					for($i=1;$i<=24;$i++){  
					  $last_id=DB::table('event_schedule')->insertGetId(['event_id'=>$event_id,'event_date2'=>$event_date2,'user_id'=>$check_event->user_id,'event_date'=>$event_date,'event_time'=>$request->event_time.':00','end_time'=>$get_end_time,'event_start_date_time'=>$event_start_date_time,'event_end_date_time'=>$event_end_date_time,'created_at'=>Date('Y-m-d H:i:s')]);	
					  $get_date=DB::table('event_schedule')->where(['id'=>$last_id])->first();
					  $event_date=date('Y-m-d', strtotime("+2 week", strtotime($get_date->event_date)));
					  $event_date2=date('D j M',strtotime("+2 week", strtotime($get_date->event_date2)));
					  
					  $event_start_date_time = date('Y-m-d H:i:s', strtotime("$event_date $get_start_time"));
						$event_end_date_time = date('Y-m-d H:i:s', strtotime("$event_date $get_end_time"));
						if(strtotime($event_start_date_time) > strtotime($event_end_date_time)){
							$event_end_date_time = date('Y-m-d H:i:s', strtotime("+1 days", strtotime($event_end_date_time)));
						}
					} 
				}
			}
             $get_event=DB::table('event_list')->where(['id'=>$event_id])->first();
             #event updation notification
			$guests = DB::table('invitations')
					->leftJoin('users', 'users.id', '=', 'invitations.receiver_id')
					->where(['request_status'=>1,'sub_event_id'=>$event_id])
					->get();		
			if((count($guests)>0)){
				foreach($guests as $guests){  
				#save invitation notification
					DB::table('notification_list')->insert(['user_id'=>$guests->id,'other_user_id'=>$admin->id,'message'=>'Event detail has been updated','notification_type'=>1,'timestamp'=>round(microtime(true) * 1000),'created_at' =>Date('Y-m-d H:i:s'),'updated_at' =>Date('Y-m-d H:i:s')]);
					#***************************  
					if($guests->device_type == 'I'){
							$message = array('sound' =>1,'message'=>'Event detail has been updated',
							'notifykey'=>'invitation','data'=>'Mo-Tiv','title'=>'Mo-Tiv',
							'event_date'=>$get_event->event_date,'event_time'=>$get_event->event_time);
							$device_token=$guests->device_token;
							$dd=$this->send_iphone_notification($device_token,'Event detail has been updated','invitation',$message);
					}   
					if($guests->device_type == 'A'){
						$message = array('sound' =>1,'message'=>'Event detail has been updated',
						'notifykey'=>'invitation','data'=>'hello','title'=>'Mo-Tiv',
						'event_date'=>$get_event->event_date,'event_time'=>$get_event->event_time);
						$device_token=$guests->device_token;
						$this->send_android_notification($device_token,'Event detail has been updated','invitation',$message);
					}
				}
			}			 
			//print $update2;die;															
			return redirect('private-events')->with('status','Event updated successfully');
		}
	}
	
	
	
	function block_unblock_public_event(Request $request){
		$id = $request->id;
		$check_block_status = DB::table('event_list')->where(['id'=>$id])->first();
		// print_r($check_block_status);die;
		if($check_block_status->status ==1){
			$update = DB::table('event_list')->where('id',$id)->update(['status'=>2]);
			
			$get_lat_id	=	DB::table('event_schedule')
						->whereRaw("event_schedule.id IN (select MIN(event_schedule.id) FROM event_schedule WHERE event_id = '$id')")
						->first();
			// print_r($get_lat_id);die;
			$get_friend_name=DB::table('users')->where(['id'=>$check_block_status->user_id])->first();	
			// print_r($get_friend_name); die;
			$notify_count=$this->notification_count($check_block_status->user_id);
			DB::table('notification_list')->insert(['user_id'=>$get_friend_name->id,'other_user_id'=>0,'message'=>'Your event is approved by Admin','notification_type'=>1,'timestamp'=>round(microtime(true) * 1000),'created_at' =>Date('Y-m-d H:i:s'),'updated_at' =>Date('Y-m-d H:i:s')]);
			if($get_friend_name->device_type == 'I' && $get_friend_name->notification_status == 1){
					$message = array('sound' =>1,'message'=>'Your event has been approved by admin',
					'notifykey'=>'event_status','data'=>'Mo-Tiv','title'=>'Mo-Tiv',
					'event_date'=>$check_block_status->event_date,'event_time'=>$check_block_status->event_time,'event_id'=>$get_lat_id->id,'notify_count'=>$notify_count);
					$device_token=$get_friend_name->device_token;
					$dd=$this->send_iphone_notification2($device_token,'Your event has been approved by admin','event_status',$message);
			}   
			if($get_friend_name->device_type == 'A' && $get_friend_name->notification_status == 1){
				$message = array('sound' =>1,'message'=>'Your event has been approved by admin',
				'notifykey'=>'event_status','data'=>'hello','title'=>'Mo-Tiv',
				'event_date'=>$check_block_status->event_date,'event_time'=>$check_block_status->event_time,'event_id'=>$get_lat_id->id,'notify_count'=>$notify_count);
				$device_token=$get_friend_name->device_token;
				$msg=$this->send_android_notification($device_token,'Your event has been approved by admin','event_status',$message);
				
			}
			$user = User::where(['id' => $check_block_status->user_id])->first();
			if (!$user) {
                Session::flash('danger', 'User Not Exist');
                return back()->withInput();
			}
			$url = url("website/add-details/$check_block_status->user_id");
		 try{
				Mail::to($user->email)->send(new EventApproveAdmin($user, $url));
				//1=>approve,0=> unapprove,2=>email_send
				DB::table('event_list')->where('id',$check_block_status->id)->update(['event_verified_admin_email'=>2]);
            }catch(\Exception $ex) {
                return $ex->getMessage();
            }

			//print $msg;die;
			return redirect('public-events')->with('status','Event Approved successfully');
		}else{
			$check_block_status = DB::table('event_list')->where('id',$id)->update(['status'=>1]);
			return redirect('public-events')->with('status','Event Un-Approve successfully');
		}  
	}
	function approve_event_private(Request $request){
		$id = $request->id;
		$check_block_status = DB::table('event_list')->where(['id'=>$id])->first();
		if($check_block_status->status ==1){
			$check_block_status =DB::table('event_list')->where('id',$id)->update(['status'=>1]);
			return redirect('private-events')->with('status','Event Un-Approve successfully');
		}else{
			$check_block_status = DB::table('event_list')->where('id',$id)->update(['status'=>2]);
			return redirect('private-events')->with('status','Event Approved successfully');
		}
	}
	function approve_event_public(Request $request){
		$id = $request->id;
		$check_block_status = DB::table('event_list')->where(['id'=>$id])->first();
		if($check_block_status->status ==1){
			$check_block_status =DB::table('event_list')->where('id',$id)->update(['status'=>1]);
			return redirect('public-events')->with('status','Event Un-Approve successfully');
		}else{
			$check_block_status = DB::table('event_list')->where('id',$id)->update(['status'=>2]);
			//print_r($check_block_status);die;
			return redirect('public-events')->with('status','Event Approved successfully');
		}
	}
	function public_events(){
		$events=DB::table('event_list')
			// ->where(['post_type'=>1])
			->where(['post_type'=>1,'users.is_deleted'=>1,'users.status' => 1,'users.blockStatus'=>2])
			->select(['event_list.*'])
			->join('users','users.id','=','event_list.user_id')
			->orderBy('event_list.id','DESC')->get();
		return view('admin/public-events',['events'=>$events]);
	}
	
	function public_view_event_details(Request $request){
		$view_id = $request->id;
		$event_detail = DB::table('event_list')->where(['id'=>$view_id])->first();
		if(!empty($event_detail)){
			return view('admin/public-view-event-details',['event_detail'=>$event_detail]);
		}else{
			return view('pageNotFound404');
		}
		
	}
	function payment_list(){
		
		return view('admin/payment-list');
	}
	function coin_management(){
		
		return view('admin/coin-management');
	}
	function public_interest(){
		$public_interest=DB::table('public_interest')->get();
		return view('admin/public-interest',['public_interest'=>$public_interest]);
	}
	function edit_public_interest(Request $request){
		$public_id = $request->id;
		$public_name=DB::table('public_interest')->where(['id'=>$public_id])->first();
		return view('admin/edit-public-interest',['public_name'=>$public_name]);
		
	}
	
	
	function update_public_interest(Request $request){
		$public_id = $request->id;
		$name = $request->name;
		if($request->isMethod('post')){
			//print_r($request->all());die;
			$validator = Validator::make($request->all(), [
				'name' => 'required|min:3|max:50|regex:/^[\pL\s\-]+$/u',
			]);
			if($validator->fails()){
				return Redirect:: back()->withErrors($validator)->withInput();
			}
		    $rr=DB::table('public_interest')->where(['id'=>$public_id])->update(['name'=>$name]);
			return redirect('public-interest')->with('status','Public interest updated successfully');
		}
	}
	
	function add_public_interest(Request $request){
		//print_r($request->all());die;
		if($request->isMethod('post')){
			$validator = Validator::make($request->all(), [
				//'name' => 'required|min:3|max:50|regex:/^[ A-Za-z0-9()[]+-*/%]*$/',
				'name' => 'required|min:3|max:50|regex:/^[\pL\s\-]+$/u',
			]);
			if($validator->fails()){
				return Redirect:: back()->withErrors($validator)->withInput();
			}
			DB::table('public_interest')->insert(['name'=>$request->input('name'),'created_at'=> Date('Y-m-d H:i:s'),'updated_at'=>Date('Y-m-d H:i:s')]);
			return redirect('public-interest')->with('status','Public interest added successfully');
		}
		return view('admin/add-public-interest');
	}
	function delete_public_interest(Request $request){
		$id=$request->id;
		$music_interest=DB::table('public_interest')->where(['id'=>$id])->delete();
		return redirect('public-interest')->with('status','Public interest deleted successfully');
	}
	function music_interest(){
		$music_interest=DB::table('music_interest')->get();
		return view('admin/music-interest',['music_interest'=>$music_interest]);
	}
	function add_music_interest(Request $request){
		if($request->isMethod('post')){
			$validator = Validator::make($request->all(), [
				//'name' =>'required|min:3|max:50|regex:/^[ A-Za-z0-9()[]+-*/%]*$/',
				'name' =>'required|min:3|max:50|regex:/^[\pL\s\-]+$/u',
			]);
			if($validator->fails()){
				return Redirect:: back()->withErrors($validator)->withInput();
			}
			DB::table('music_interest')->insert(['name'=>$request->input('name'),'created_at'=> Date('Y-m-d H:i:s'),'updated_at'=>Date('Y-m-d H:i:s')]);
			return redirect('music-interest')->with('status','Music interest added successfully');
		}
		return view('admin/add-music-interest');
	}
	function edit_music_interest(Request $request){
		$music_id = $request->id;
		$music=DB::table('music_interest')->where(['id'=>$music_id])->first();
		return view('admin/edit-music-interest',['music'=>$music]);
	}
	function update_music_interest(Request $request){
		$music_id = $request->id;
		$name = $request->name;
		if($request->isMethod('post')){
			//print_r($request->all());die;
			$validator = Validator::make($request->all(), [
				'name' => 'required|min:3|max:50|regex:/^[\pL\s\-]+$/u',
			]);
			if($validator->fails()){
				return Redirect:: back()->withErrors($validator)->withInput();
			}
		    $rr=DB::table('music_interest')->where(['id'=>$music_id])->update(['name'=>$name]);
			return redirect('music-interest')->with('status','Music interest updated successfully');
		}
	}
	function delete_music_interest(Request $request){
		$id=$request->id;
		$music_interest=DB::table('music_interest')->where(['id'=>$id])->delete();
		return redirect('music-interest')->with('status','Music interest deleted successfully');
	}
	
	public function notification_count($user_id){
		$notification_count=DB::table('notification_list')->where(['user_id'=>$user_id,'status'=>2])->count();
		return $notification_count;
	}
	
	public function event_reports(Request $request){
		$reports = DB::table('event_reports')
					->Join('users', 'users.id', '=', 'event_reports.user_id')
					->Join('event_list', 'event_list.id', '=', 'event_reports.event_id')
					->where(['report_status'=>3])
					->select('users.*','event_reports.id as report_id','event_list.*','event_reports.*')
					->get();	
					
		return view('admin/event-reports',['reports'=>$reports]);
	}
	public function view_event_report(Request $request){
		$report_id = $request->id;
	    $reports=DB::table('event_reports')->where(['id'=>$report_id])->first();		
		/*$reports =  DB::table('event_reports')
					->Join('users', 'users.id', '=', 'event_reports.user_id')
					->Join('event_list', 'event_list.id', '=', 'event_reports.event_id')
					->where(['event_reports.event_id'=>$get_report->event_id])
					->select('event_reports.message','event_reports.id as report_id')
					->first(); */
		//print_r($reports);die; 			   
       
		if(!empty($reports)){
			$event_detail = DB::table('event_list')->where(['id'=>$reports->event_id])->first();
		}else{
			return view('pageNotFound404');
		}					
					
		return view('admin/view-event-report',['reports'=>$reports,'event_detail'=>$event_detail]);
	}
	
	public function block_event(Request $request){
		$report_id = $request->id;
		$event_detail = DB::table('event_reports')->where(['id'=>$report_id])->update(['report_status'=>1]);
		Session::flash('status','Event blocked successfully');
		return redirect('event-reports');
	}
	
	
	function sendPushMessage(Request $request){
		if($request->isMethod('Post')){
			$validate = Validator::make($request->all(),[
				'message'=>'required|max:255',
			]);
			
			if($validate->fails()){
				return Redirect:: back()->withErrors($validate)->withInput();
			}
			$message = $request->input('message');
			$get_user_list = User::where(['role' => 2,'is_deleted' => 1,'status' => 1,'blockStatus' => 2])->select(['device_type','device_token','id'])->get();
			
			if(count($get_user_list) >0){
				DB::table('notification_list')->insert(['user_id'=>Auth::User()->id,'other_user_id'=>Auth::User()->id,'message'=>$message,'notification_type'=>3,'timestamp'=>round(microtime(true) * 1000),'created_at' =>Date('Y-m-d H:i:s'),'updated_at' =>Date('Y-m-d H:i:s')]);
				foreach($get_user_list as $each_data){
					$message_data = array('sound' =>1,'message'=>$message,'notifykey'=>'admin_message');
					if($each_data->device_type == 'I' && !empty($each_data->device_token)){
						$dd=$this->send_iphone_notification($each_data->device_token,$message,'admin_message',$message_data);
						// echo '<pre>'; print_r($each_data->toArray()); exit;
						// echo '<pre>'; print_r($dd);
					}else if($each_data->device_type == 'A' && !empty($each_data->device_token)){
						$result = $this->send_android_notification($each_data->device_token,$message,'admin_message',$message_data);
						// echo '<pre>'; print_r($result); 
						// echo '<pre>'; print_r($each_data->toArray()); exit;
					}
				}
			}
			Session::flash('status','Message sent successfully.');
			return view('admin/msg-management');
		}else{
			// print_r('dertfe'); exit;
			return view('admin/msg-management');
		}
	}
	
	function maintenance(Request $request){
			return view('admin/maintenance-management');
		
	}
	
	function maintenance_update(){
		    $get_admin=DB::table('app_setting')->first();
			if($get_admin->maintenance_status ==1){
				DB::table('app_setting')->where('id',1)->update(['maintenance_status'=>0]);
				return '0';
			}else{
				DB::table('app_setting')->where('id',1)->update(['maintenance_status'=>1]);
				return '1';
			}
			
			
		
	}
	
	function maintenance_status(){
		$get_admin=DB::table('app_setting')->first();
		return  $get_admin->maintenance_status;
	}


	public static function send_iphone_notification($recivertok_id,$message,$notmessage='',$msgsender_id=''){  
			$PATH = dirname(dirname(dirname(dirname(__FILE__))))."/pem/DevPush.pem";
			//$PATH = dirname(dirname(dirname(dirname(__FILE__))))."/pemfile/DevPush.pem";
			$deviceToken = $recivertok_id;
			$passphrase = "";
			//$passphrase = 123456789;
			$message = $message;
			$ctx = stream_context_create();
				   stream_context_set_option($ctx, 'ssl', 'local_cert', $PATH);
			       stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
			
			   $fp = stream_socket_client(
										'ssl://gateway.push.apple.com:2195', $err,
				$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx); 
			   
			   // $fp = stream_socket_client(
										// 'ssl://gateway.sandbox.push.apple.com:2195', $err,
				//$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx); 
			  
		
			if (!$fp)
				 exit("Failed to connect: $err $errstr" . PHP_EOL);

				$body['aps'] = array(
					'alert' => $message,
					'sound' => 'default',
					'Notifykey' => $notmessage, 
					'msgsender_id'=>$msgsender_id
				);

			$payload = json_encode($body);
			$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
			$result = fwrite($fp, $msg, strlen($msg));

			//echo "<pre>";
			// print_r($body);
			
			// if (!$result)
				// echo 'Message not delivered' . PHP_EOL;
			// else
				// echo 'Message successfully delivered' . PHP_EOL;
			// exit;
			fclose($fp);
			return  $result;
		}
	
	public static function send_iphone_notification2($recivertok_id,$message,$notmessage='',$msgsender_id=''){  
		$PATH = dirname(dirname(dirname(dirname(__FILE__))))."/pem/DevPush.pem";
		//print $PATH;die;
		$deviceToken = $recivertok_id;
		$passphrase = "";
		//$passphrase = 123456789;
		$message = $message;
		$ctx = stream_context_create();
			   stream_context_set_option($ctx, 'ssl', 'local_cert', $PATH);
			   stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
		
				$fp = stream_socket_client(  
									'ssl://gateway.sandbox.push.apple.com:2195', $err,
			$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx); 
		
		if (!$fp)
			 exit("Failed to connect: $err $errstr" . PHP_EOL);

			$body['aps'] = array(
				'alert' => $message,
				'sound' => 'default',
				'Notifykey' => $notmessage, 
				'msgsender_id'=>$msgsender_id
			);

		$payload = json_encode($body);
		$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
		//sleep(5);
		$result = fwrite($fp, $msg, strlen($msg));
         
		// echo "<pre>";
		// print_r($result);die;
	     // if (!$result)
			 // echo 'Message not delivered' . PHP_EOL;
		 // else
			 // echo 'Message successfully delivered' . PHP_EOL;
		 // exit;
		fclose($fp);
		return  $result;
	}
		
		
		function send_android_notification($device_token,$message,$notmessage='',$msgsender_id=''){
			if (!defined('API_ACCESS_KEY'))
			{
				define('API_ACCESS_KEY','AIzaSyCpGtCxr9NjjohLSRhT1AIps3XrHgZNrF4');
			}
			$registrationIds = array($device_token);
				// echo "<pre>";
			// print_r($msgsender_id);exit;
			// $fields = array(
				// 'registration_ids' => $registrationIds,
				// 'alert' => $message,
				// 'sound' => 'default',
				// 'Notifykey' => $notmessage, 
				// 'data'=>$msgsender_id
			// );
			
			$fields = array(
				'registration_ids' => $registrationIds,
				'alert' => $message,
				'sound' => 'default',
				'Notifykey' => $notmessage, 
				'data'=>$msgsender_id
					
			);

			$headers = array(
				'Authorization: key=' . API_ACCESS_KEY,
				'Content-Type: application/json'
			);
			// echo "<br>";
			// print_r($headers);

			$ch = curl_init();
			curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
			curl_setopt( $ch,CURLOPT_POST, true );
			curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
			curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
			curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode($fields) );
			$result = curl_exec($ch);

			if($result == FALSE) {
				die('Curl failed: ' . curl_error($ch));
			}

			// echo curl_error($ch);
			curl_close( $ch );
			// echo "<pre>";print_r($result);exit;
			// echo "<br>";
			return  $result;

		}
			
			
		
	
	
	
	
	
	/***************************/
	
}
