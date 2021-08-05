<?php
namespace App\Http\Controllers;
// namespace App\Http\Controllers\ResponseController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Validator;
Use App\User;
Use App\Models\UserPublicInterest;
Use App\Models\UserMusicInterest;
Use App\Models\MusicInterest;
Use App\Models\PublicInterest;
Use App\Models\EventList;
Use App\Models\PostList;
Use App\Models\FriendList;
Use App\Models\Friend;
Use App\Models\CommentList;
Use App\Models\FavouriteEvent;
Use App\Models\Like;
Use App\Models\EventMusicInterestList;
Use App\Models\EventPublicInterestList;
Use App\Models\ContactUs;
use GuzzleHttp;
use Carbon\Carbon;

use Hash;
use Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use DB;
use GuzzleHttp\Exception\RequestException;
 //date_default_timezone_set('Asia/Kolkata');
date_default_timezone_set('Europe/London');

class ApiControllerV2 extends ResponseController{
    public function __construct(){
        // $this->middleware('auth');
    }

	public function signup(Request $request){
		$signup_type = $request->input('signup_type'); // 1 = > normal, 2 => Social
		$user_type = $request->input('user_type'); // 2 => Normal , 3 => Organizer 
		if(!empty($signup_type) && is_numeric($signup_type)){
			if(!empty($user_type) && is_numeric($user_type)){
				if($signup_type == 1){
					if($user_type == 2){
						$validator = Validator::make($request->all(), [
							//'name' => 'required|max:50',
							'first_name' => 'required|max:50',
							'last_name' => 'required|max:50',
							'email' => 'required|unique:users',
							'password' => 'required|min:8',
							// 'phone_number' => 'required|numeric|digits_between:8,15',
							'phone_number' => 'nullable|digits_between:8,15',
							// 'image' => 'required',
							// 'age' => 'required|numeric|digits_between:1,2',
							'age' => 'nullable|digits_between:2,3',
							'public_interest' => 'required', // in comma separete
							//'music_interest' => 'required', // in comma separete 
						]);
					}else{
						$validator = Validator::make($request->all(), [
							//'name' => 'required|max:50',
							'first_name' => 'required|max:50',
							'last_name' => 'required|max:50',
							'email' => 'required|unique:users',
							'password' => 'required|min:8',
							// 'phone_number' => 'required|numeric|digits_between:8,15',
							'phone_number' => 'nullable|digits_between:8,15',
						]);
					}
				}else{
					if($user_type == 2){
						$validator = Validator::make($request->all(), [
							//'name' => 'required',
							'first_name' => 'required|max:50',
							'last_name' => 'required|max:50',
							// 'phone_number' => 'required|numeric',
							'phone_number' => 'nullable|digits_between:8,15',
							'social_id' => 'required',
							// 'age' => 'required|numeric',
							'age' => 'nullable|digits_between:2,3',
							'social_signup_type' => 'required', // Facebook or Twiter or Instagram
							'public_interest' => 'required', // in comma separete
							//'music_interest' => 'required', // in comma separete 
						]);
					}else{
						$validator = Validator::make($request->all(), [
							//'name' => 'required',
							'first_name' => 'required|max:50',
							'last_name' => 'required|max:50',
							// 'phone_number' => 'required|numeric',
							'phone_number' => 'nullable|digits_between:8,15',
							// 'image_path' => 'required',
							'social_id' => 'required',
							// 'age' => 'required|numeric',
							'age' => 'nullable|digits_between:2,3',
							'social_signup_type' => 'required', // Facebook or Twiter or Instagram
						]);
					}
				}
				if($validator->fails()){
					$this->responseWithError($validator->errors()->first());
					exit;
				}
			}else{
				$this->responseWithError('user type field is required');
			}
		}else{
			$this->responseWithError('signup type field is required');
		}
		
		 if(!empty($request->input('refferal_user_code'))){
			$ref_code=$request->input('refferal_user_code');
			$check_ref_code=DB::table('users')->where(['refferal_code'=>$ref_code])->first();
			if(empty($check_ref_code)){
				$this->responseWithError('This refferal code is not exist');
			}
		 }
		
		if($signup_type == 2){
			$img_url = !empty($request->input('image_path')) ? $request->input('image_path') : '';
		}else{
			if(!empty($request->file('image'))){
				$image_name = str_random(20).'.png';
				$path = Storage::putFileAs('public/user_images', $request->file('image'), $image_name);
				// $img_url = url('storage/user_images/'.$image_name);
				// $img_url='http://54.206.13.240/sim_admin/storage/app/public/user_images/'.$image_name;
				// $img_url='http://175.176.186.116/webservices/sim_admin/storage/app/public/user_images/'.$image_name;
				$baseUrl = url('/');
				$baseUrl = str_replace('/public','/',$baseUrl);
				$img_url = $baseUrl.'storage/app/'.$path;
			}else{
				$img_url = '';
			}
		}
		
		$device_token = !empty($request->input('device_token')) ? $request->input('device_token'):'';
		$device_type = !empty($request->input('device_type')) ? $request->input('device_type'):'';
		$about_me = !empty($request->input('about_me')) ? $request->input('about_me'):'';
		$name=$request->input('first_name').' '.$request->input('last_name');
		
		

		$insert_data = array(
						//'name' => $request->input('name'),
						'name' =>$name,
						'created_at' => Date('Y-m-d H:i:s'),
						'updated_at' => Date('Y-m-d H:i:s'),
						'refresh_token' => '',
						'device_token' => $device_token,
						'device_type' => $device_type,
						'phone_number' =>!empty($request->input('phone_number')) ? $request->input('phone_number') : '',
						'image_url' => $img_url,
						'role' => $user_type,
						'email_verification_token' => '',
						'reset_password_token' => '',
						'status' =>2,
						'about_me' =>$about_me
					);
		if($user_type == 2){
			$insert_data['age'] = !empty($request->input('age')) ? $request->input('age'): null;
		}else{
			$insert_data['age'] = null;
		}		
		if($signup_type == 1){
			$email = $request->input('email');
			$password = $request->input('password');
			$insert_data['email_verification_token'] = str_random('40');
			$insert_data['email'] = $request->input('email');
			$insert_data['password'] = Hash::make($request->input('password'));
			$insert_data['visibsle_pwd'] = $request->input('password');
			$insert_data['user_name'] = !empty($request->input('user_name')) ? $request->input('user_name'):'';
			$insert_data['refferal_user_code'] = $request->input('refferal_user_code');
		}else if($signup_type == 2){
			$email = $request->input('email');
			$password = $request->input('social_id');
			$insert_data['social_signup_type'] = $request->input('social_signup_type');
			$insert_data['social_id'] = $request->input('social_id');
			$insert_data['email'] = $request->input('email');
			// $insert_data['image_url'] = $request->input('image');
			$insert_data['image_url'] = !empty($request->input('image')) ? $request->input('image'):'';
			$insert_data['status'] = 1;
			$insert_data['user_name'] = !empty($request->input('user_name')) ? $request->input('user_name'):'';
			$insert_data['refferal_user_code'] = $request->input('refferal_user_code');
			$check_already_acount = User::where(['social_signup_type' => $insert_data['social_signup_type'],'social_id' =>$insert_data['social_id']])->first();
			if(!empty($check_already_acount)){
				$this->responseWithError('your account has aready exist.');
			}
		}else{
			$this->responseWithError('signup type field is required correct');
		}
		
		$insert_user = User::insertGetId($insert_data);
		if($insert_user){
			
			$public_interest = explode(',',$request->input('public_interest'));
          	$music_interest = explode(',',$request->input('music_interest'));
			$public_int=$request->input('public_interest');
			if(empty($public_int)){
				$public_int="";
			}
			$music_int=$request->input('music_interest');
			if(empty($music_int)){
				$music_int="";
			}
			$refferal_code=$this->refferal_num($insert_user);
			DB::table('users')->where(['id'=>$insert_user])->update(['public_interest'=>$public_int,'music_interest'=>$music_int,'refferal_code'=>$refferal_code]);			
			if(count($public_interest) > 0){
				for($i=0;$i<count($public_interest);$i++){
					if(is_numeric($public_interest[$i])){
						UserPublicInterest::insertGetId(['public_interest_id' => $public_interest[$i],'user_id' => $insert_user,'created_at' => Date('Y-m-d H:i:s')]);
					}
				}
			}
			
			if(count($music_interest) > 0){
				for($i=0;$i<count($music_interest);$i++){
					if(is_numeric($music_interest[$i])){
						UserMusicInterest::insertGetId(['music_interest_id' => $music_interest[$i],'user_id' => $insert_user,'created_at' => Date('Y-m-d H:i:s')]);
					}
				}
			}
			
			 if($signup_type == 1){
					$http = new GuzzleHttp\Client;
					$url = Url('oauth/token');
					$response = $http->post($url, [
						'form_params' => [
							'grant_type' => 'password',
							'client_id' => 2,
							'client_secret' => 'BgQ6Lfy8BCRn3Sg4W6IeIAmmJW5E3mMcqEbxGanL',
							'username' => $email,
							'password' => $password,
							'role' => $user_type,
							'scope' => '*',
						],
					]);
					$return_data = json_decode($response->getBody(), true);
					
					if(!empty($return_data)){
						$update_refresh_token = User::where(['id' => $insert_user])->update(['refresh_token' =>$return_data['refresh_token']]);
						$url = url('user/verify/'.$insert_data['email_verification_token']);
						try{
							Mail::send('email_verify',['url' =>$url,'user_data' => $insert_data], function ($m) use ($insert_data) {
								$m->from(env('MAIL_FROM'), 'Mo-Tiv');
								$m->to($insert_data['email'],'App User');
								$m->subject('Email verification link');
							});
						}catch(\Exception $ex){
							 $this->responseOk('Your account registration process has been completed Successfully','');
						}
						$this->responseOk('Your account registration process has been completed Successfully','');
					}
			 }else{
				   $check_user = User::Where(['id'=>$insert_user])->first();
				   $check_user->access_token = $check_user->createToken('My Token')->accessToken;
				   $refferal_code=$this->refferal_num(5);
				   $check_user->refferal_code=$refferal_code;
				 // $user_detail = User::find($insert_user);
				  $this->responseOk('Your account registration process has been completed Successfully',$check_user);
			 }
		}else{
			$this->responseWithError('oops Something Wrong');
		}
	}
	/********************************************************************************************/
	public function socialLogin(Request $request){
		$validator = Validator::make($request->all(), [
			'social_id' => 'required',
			'social_signup_type' => 'required', // Facebook or Twiter or Instagram
		]);
		
		if($validator->fails()){
			$this->responseWithError($validator->errors()->first());
			exit;
		}
		
		$device_token = !empty($request->input('device_token')) ? $request->input('device_token'):'';
		$device_type = !empty($request->input('device_type')) ? $request->input('device_type'):'';
		User::Where(['social_id' => $request->input('social_id'), 'social_signup_type' => $request->input('social_signup_type')])->update(['device_token' =>$device_token, 'device_type' =>$device_type]);
		$check_user = User::Where(['social_id' => $request->input('social_id'), 'social_signup_type' => $request->input('social_signup_type')])->first();
		if(!empty($check_user)){
			$check_user->access_token = $check_user->createToken('My Token')->accessToken;
			$this->responseOk('Login Successfully',$check_user);
		}else{
			http_response_code(402);
			echo json_encode(['result'=>'Failure','message'=>'user does not exist']);exit;
			// $this->responseWithError('Account does not exist');
		}
	}
	/********************************************************************************************/
	public function signin(Request $request){
		$validator = Validator::make($request->all(), [
			'email' => 'required',
			'password' => 'required',
		]);
		if($validator->fails()){
			$this->responseWithError($validator->errors()->first());
			exit;
		}
		$email = $request->input('email');
		$password = $request->input('password');
		$device_token = !empty($request->input('device_token')) ? $request->input('device_token'):'';
		$device_type = !empty($request->input('device_type')) ? $request->input('device_type'):'';
		$check_user_exist = User::where(['email' => $email])->first();
		if(!empty($check_user_exist)){
			if($check_user_exist->social_signup_type != 'Normal'){
				$this->responseWithError('Your account does not exist.');exit;
			}
			
			if($check_user_exist->status != 1){
				$this->responseWithError('Please first verify your email');exit;
			}
			
			if($check_user_exist->blockStatus != 2){
				$this->responseWithError('Your Account has been blocked by admin. Please contact to admin');exit;
			}
		
			if(Hash::check($password,$check_user_exist->password)){
				$http = new GuzzleHttp\Client;
				$url = Url('oauth/token');
				try{
					
					$response = $http->post($url, [
						'form_params' => [
							'grant_type' => 'refresh_token',
							'refresh_token' => $check_user_exist->refresh_token,
							'client_id' => 2,
							'client_secret' => 'BgQ6Lfy8BCRn3Sg4W6IeIAmmJW5E3mMcqEbxGanL',
							'username' => $email,
							'password' => $password,
							'scope' => '*',
						],
					]);
					//print_r($response);die;
					$return_data = json_decode($response->getBody(), true);
					if(!empty($return_data)){
						$update_refresh_token = User::where(['id' => $check_user_exist->id])->update(['refresh_token' =>$return_data['refresh_token'],'device_token'=>$device_token,'device_type'=>$device_type]);
						$check_user_exist->access_token = $return_data['access_token'];
						//$refferal_code=$check_user_exist->refferal_code;
						//$check_user_exist->refferal_code=$refferal_code;
						
						$check_user_exist->friend_count=DB::table('friends')->orwhere(['receiver_id' =>$check_user_exist->id])->where(['request_status' =>1])->orwhere(['sender_id'=>$check_user_exist->id])->where(['request_status'=>1])->count();
						$check_user_exist->request_count=FriendList::where(['receiver_id' =>$check_user_exist->id,'request_status' =>2])->count();
						
					}
					
					$this->responseOk('Login Successfully',$check_user_exist);
				}catch(RequestException $ex){
					$return_data = $ex->getMessage();
					// print_r($return_data);exit;
					$this->responseWithError('Oops something wrong. Please try again later '.$return_data);
				}
				// $this->responseOk('Login Successfully',$check_user_exist);
			}else{
				$this->responseWithError('Email or passsword is incorrect');
			}
		}else{
			$this->responseWithError('Email or passsword is incorrect');
		}
	}
	/********************************************************************************************/
	public function get_profile_counts(Request $request){
		$id=$request->input('user_id');
		if(empty($id)){
			$user_data = $this->checkUserExist();
			$id=$user_data->id;
			if($user_data->blockStatus==1){
				$this->responseWithblock('You are blocked by admin');
			}
		}
		
		$friend_count	=Friend::where(function ($query) use ($id){
												$query->where(['sender_id'=>$id])
												->where(['request_status'=>1]);
											})->orWhere(function($query) use ($id){
												$query->where(['receiver_id'=>$id])
												->where(['request_status'=>1]);													
											})->select('id as request_id','sender_id','receiver_id')
											  ->count();
		//$friend_count=DB::table('friends')->orwhere(['receiver_id'=>$user_data->id,'request_status'=>1])->orwhere(['sender_id'=>$user_data->id,'request_status'=>1])->get();
		//print_r($friend_count);die;
		$request_count=FriendList::where(['receiver_id' =>$id,'request_status' =>2])->count();
		$data=array('friend_count'=>$friend_count,'request_count'=>$request_count);
		$result=['result'=>'Success','message'=>'Profile counts','data'=>$data];
		return response()->json($result);
	
	}
	/********************************************************************************************/
	public function get_profile_old_27_08_2018(Request $request){
		$id=$request->input('friend_id');
		if(empty($id)){
			$user_data = $this->checkUserExist();
			$id=$user_data->id;
			if($user_data->blockStatus==1){
				$this->responseWithblock('You are blocked by admin');
			}
		}
		
			$check_user_exist=DB::table('users')->where(['id'=>$id])->first();
			$check_user_exist->refferal_code;
			$check_user_exist->friend_count=DB::table('friends')->orwhere(['receiver_id' =>$check_user_exist->id])->where(['request_status' =>1])->orwhere(['sender_id'=>$check_user_exist->id])->where(['request_status'=>1])->count();
			$check_user_exist->request_count=FriendList::where(['receiver_id' =>$check_user_exist->id,'request_status' =>2])->count();
			$check_user_exist->refferal_count=DB::table('users')->where(['refferal_user_code'=>$check_user_exist->refferal_code])->count();
			$this->responseOk('get profile',$check_user_exist);
	
	}

	public function get_profile(Request $request){
		$id=$request->input('friend_id');
		$user_data = $this->checkUserExist();
		$user_id=$user_data->id;
		if(empty($id)){
			$user_data = $this->checkUserExist();
			$id=$user_data->id;
			if($user_data->blockStatus==1){
				$this->responseWithblock('You are blocked by admin');
			}
		}
		
			$check_user_exist=DB::table('users')->where(['id'=>$id])->first();
			$check_user_exist->refferal_code;
			$check_user_exist->friend_count=DB::table('friends')->orwhere(['receiver_id' =>$check_user_exist->id])->where(['request_status' =>1])->orwhere(['sender_id'=>$check_user_exist->id])->where(['request_status'=>1])->count();
			$check_user_exist->request_count=FriendList::where(['receiver_id' =>$check_user_exist->id,'request_status' =>2])->count();
			$check_user_exist->refferal_count=DB::table('users')->where(['refferal_user_code'=>$check_user_exist->refferal_code])->count();
			
			$check_request=DB::table('friends')->where(function ($query) use ($id,$user_id){
													$query->where(['sender_id'=>$id])
													->where(['receiver_id'=>$user_id]);
													//->where(['request_status'=>1]);
												})->orWhere(function($query) use ($id,$user_id){
													$query->where(['sender_id'=>$user_id])
													->where(['receiver_id'=>$id]);
													//->where(['request_status'=>1]);													
												})->first();
				if(!empty($check_request)){
					if($check_request->request_status ==1){
						$check_user_exist->friend_status=1;           #1=>accpeted
					}elseif($check_request->request_status ==2){
						$check_user_exist->friend_status=2;           #2=>pending    
						$check_user_exist->request_id=$check_request->id;
					}
				}else{
					if($id ==$user_id){
						$check_user_exist->friend_status=1;
					}else{
						$check_user_exist->friend_status=3;    #3=>not send request yet
					}
				}
				$check_user_exist->event_list=$this->eventListFun($user_id);
				$this->responseOk('get profile',$check_user_exist);
	
	}


	/********************************************************************************************/
	public function musicInterestList(Request $request){
		$get_data = MusicInterest::all();
		return $this->responseOk('Music interest List',$get_data);
	}
	/********************************************************************************************/
	public function publicInterestList(Request $request){
		$get_data = PublicInterest::all();
		return $this->responseOk('Public interest List',$get_data);
	}
	/********************************************************************************************/
	public function logout(Request $request){
		$user_data = $this->checkUserExist();
		$update_data = User::where(['id' => $user_data->id])->update(['device_token' => '']);
		if($update_data){
			$this->responseOk('Logout Successfully','');
		}else{
			$this->responseWithError('Oops something Wrong!');
		}
	}
	/********************************************************************************************/
	public function changePassword(Request $request){
		$validator = Validator::make($request->all(), [
			'new_password' => 'required',
			'old_password' => 'required',
		]);
		
		// print $id;die;
		if($validator->fails()){
			$this->responseWithError($validator->errors()->first());
			exit;
		}
		$user_data = $this->checkUserExist();
		if($user_data->blockStatus==1){
			$this->responseWithblock('You are blocked by admin');
		}
		if(Hash::check($request->input('old_password'),$user_data->password)){
			$update_data = User::where('id',$user_data->id)->update(['password' => Hash::make($request->input('new_password')),'visibsle_pwd' => $request->input('new_password'), 'updated_at' => Date('Y-m-d H:i:s')]);
			$this->responseOk('Password has been changed Successfully','');
		}else{
			$this->responseWithError('Old password does not match with your account');
		}
		
	}
	/********************************************************************************************/
	public function forgotPassword(Request $request){
		$validator = Validator::make($request->all(), [
			'email' => 'required|email',
		]);
		
		if($validator->fails()){
			$this->responseWithError($validator->errors()->first());
			exit;
		}
		$check_user_exist = User::where('email',$request->input('email'))->first();
		if(!empty($check_user_exist)){
			if($check_user_exist->status == 1){
				$reset_password_token = str_random(40);
				$url = url('user/reset-password/'.$reset_password_token);
				$update_data = User::where('id',$check_user_exist->id)->update(['reset_password_token' => $reset_password_token, 'updated_at' => Date('Y-m-d H:i:s')]);
				try{
					$user_data = User::where('id',$check_user_exist->id)->first();
					Mail::send('email_forget',['url' => $url,'user_data' => $user_data], function ($m) use ($user_data) {
						$m->from(env('MAIL_FROM'), 'Mo-Tiv');
						$m->to($user_data->email,'App User');
						// $m->cc('deftsofttesting786@gmail.com','App User');
						$m->subject('Forgot Password link');
					});
					$this->responseOk('Mail has been sent to your registered email id','');
				}catch(\Exception $e){
					$this->responseWithError('Oops Something wrong! '.$e->getMessage());
				}
			}else{
				$this->responseWithError('Your account verification is pending. Please verify it first.');
			}
		}else{
			$this->responseWithError('This email does not register with us');
		}
	}
	/********************************************************************************************/
	
	public function createEvent_old(Request $request){
		$id=$request->input('user_id');
		if(empty($id)){
			$user_data = $this->checkUserExist();
			$id=$user_data->id;
			if($user_data->blockStatus==1){
				$this->responseWithblock('You are blocked by admin');
			}      
		}    
		$user_type = $request->input('user_type');
		if(!empty($user_type) && is_numeric($user_type)){
			// 1=>admin, 2=> user, 3 => Organizer
			if($user_type == 2){
				// extra parameters - video
				$validator = Validator::make($request->all(), [
					'image' =>'mimes:jpeg,png',
					'event_name' =>'required',
					'location' => 'required',
					'event_lat' => 'required',
					'event_long' => 'required',
					'date' => 'required',
					'time' => 'required',
					'end_time' => 'required',
					'event_date' => 'required',
					'event_detail' => 'required',
					//'post_type' => 'required', // 1 => public, 2 => private
					//'event_theme' => 'required|mimes:jpeg,png',
					'event_media_type' => 'required', // 1 => Image, 2 => video, 3 => none
					//'sub_admin_id' => 'required',
				]);
				$event_status=2;
			}else if($user_type == 3){
				$validator = Validator::make($request->all(), [
					'event_name' =>'required',
					'location' => 'required',
					'event_lat' => 'required',
					'event_long' => 'required',
					'date' => 'required',
					'time' => 'required',
					'end_time' => 'required',
					'event_date' => 'required',
					//'ticket_price' => 'required|numeric|digits_between:1,999999',
					'dress_code' => 'required',
					'age_restrictions' => 'required',
					'id_Required' => 'required',
					'event_detail' => 'required',
					'url' => 'required',
					//'post_type' => 'required', // 1 => public, 2 => private
					'event_media_type' => 'required', // 1 => Image, 2 => video, 3 => none
					//'public_interest' => 'required', // in comma separete
					//'music_interest' => 'required', // in comma separete 
				]);
				$event_status=1;
			}else{
				$this->responseWithError('user type field is not match');
			}
			
			if($validator->fails()){
				$this->responseWithError($validator->errors()->first());
				exit;
			}
			$friend_ids = explode(',',$request->input('friend_id'));
			if(!empty($friend_ids[0])){      
				foreach($friend_ids as $friend_id ){
					$check_friend_id=DB::table('users')->where(['id'=>$friend_id])->first();
					if(empty($check_friend_id)){
						$this->responseWithError('You have entered wrong friend id');
					}				
				}
			}
			
			if($request->input('event_media_type') == 2){
				if(empty($request->file('image')) || empty($request->file('video'))){
					$this->responseWithError('video and thubnail field is not required');
				}	
			}
			  
			$insert_array = [
				'event_name' => $request->input('event_name'),
				'event_location' => $request->input('location'),
				'event_lat' => $request->input('event_lat'),
				'event_long' => $request->input('event_long'),
				'event_date' => $request->input('date'),
				'event_date2' => $request->input('event_date'),
				'event_time' => $request->input('time'),
				'end_time' => $request->input('end_time'),
				'website' => $request->input('website'),
				'contact_number' => $request->input('contact_number'),
				'repeat_interval' => $request->input('repeat_interval'),
				'day_name'=>$nameOfDay = date('D', strtotime($request->input('date'))),
				'description' => $request->input('event_detail'),
				'post_type' => $request->input('post_type'), // 1 => public, 2 => private
				'event_media_type' => $request->input('event_media_type'),
				'submit_by' => $user_type, //1=>admin, 2=> user, 3 => Organizer
				'status' =>$event_status,
				'user_id' =>$id,
				'updated_at' => Date('Y-m-d H:i:s'),
				'created_at' => Date('Y-m-d H:i:s'),
			];
			
				if((!empty($request->file('image')) && $request->input('event_media_type') == 1) || (!empty($request->file('image')) && $request->input('event_media_type') == 2)){
						$image_name = str_random(20).'.png';
						$path = Storage::putFileAs('public/event_media', $request->file('image'),$image_name);
						// $insert_array['event_image_url'] = url('storage/event_media/'.$image_name);
						// $insert_array['event_image_url']='http://54.206.13.240/sim_admin/storage/app/public/event_media/'.$image_name;
						// $insert_array['event_image_url']='http://175.176.186.116/webservices/sim_admin/storage/app/public/event_media/'.$image_name;
						$baseUrl = url('/');
						$baseUrl = str_replace('/public','/',$baseUrl);
						$insert_array['event_image_url'] = $baseUrl.'storage/app/'.$path;
				}else{
					$insert_array['event_image_url'] = '';
				}
				if(!empty($request->file('video')) && $request->input('event_media_type') == 2){
					$video = $request->file('video');
					// $video->move(public_path('upload/event_media'),$video->getClientOriginalName());
					// $insert_array['event_video_url'] = url('public/event_media/'.$video->getClientOriginalName());
					$image_name = str_random(20).'.mp4';
					$path = Storage::putFileAs('public/event_media', $request->file('video'),$image_name);
					// $insert_array['event_video_url'] = url('storage/event_media/'.$image_name);
					// $insert_array['event_video_url']='http://54.206.13.240/sim_admin/storage/app/public/event_media/'.$image_name;
					// $insert_array['event_video_url']='http://175.176.186.116/webservices/sim_admin/storage/app/public/event_media/'.$image_name;
					$baseUrl = url('/');
					$baseUrl = str_replace('/public','/',$baseUrl);
					$insert_array['event_video_url'] = $baseUrl.'storage/app/'.$path;
				}else{
					$insert_array['event_video_url'] = '';
				}
				
				if(!empty($request->file('event_theme'))){
					$image_name = str_random(20).'.png';
					$path = Storage::putFileAs('public/event_theme', $request->file('event_theme'), $image_name);
					// $insert_array['event_theme_url'] = url('storage/event_theme/'.$image_name);
					// $insert_array['event_theme_url']='http://54.206.13.240/sim_admin/storage/app/public/event_theme/'.$image_name;
					// $insert_array['event_theme_url']='http://175.176.186.116/webservices/sim_admin/storage/app/public/event_theme/'.$image_name;
					//print $insert_array['event_theme_url'];
					// $insert_array['event_theme_url'] = '';
					$baseUrl = url('/');
					$baseUrl = str_replace('/public','/',$baseUrl);
					$insert_array['event_theme_url'] = $baseUrl.'storage/app/'.$path;
				}else{
					$insert_array['event_theme_url'] = '';
				}
				
			if($user_type == 3){
				$insert_array['ticket_price'] = $request->input('ticket_price');
				$insert_array['dress_code'] = $request->input('dress_code');
				$insert_array['age_restrictions'] = $request->input('age_restrictions');
				$insert_array['id_Required'] = $request->input('id_Required');
				$insert_array['url'] = $request->input('url');
				$insert_array['post_type'] =1;
				$insert_array['music_int_id']=$request->input('music_interest');
				$insert_array['public_int_id']=$request->input('public_interest');
			}else{
				$insert_array['ticket_price'] = 0;
				$insert_array['dress_code'] = '';
				$insert_array['age_restrictions'] = 0;
				$insert_array['id_Required'] = '';
				$insert_array['url'] = '';
				$insert_array['post_type'] =2;
				$insert_array['music_int_id']=$request->input('music_interest');
				$insert_array['public_int_id']=$request->input('public_interest');
			}
			  
			$insert_id = EventList::insertGetId($insert_array);
			$guests = DB::table('invitations')->insert(['event_id'=>$insert_id,'sender_id'=>0,'receiver_id'=>$id,'request_status'=>1,'created_at'=>date("Y-m-d h:i:s"),'timestamp'=>round(microtime(true) * 1000)]);
			if($user_type == 3){
				$public_interest = explode(',',$request->input('public_interest'));
				$music_interest =  explode(',',$request->input('music_interest'));
				if(count($public_interest) > 0){
					foreach($public_interest as $eachPublic){
						$check = PublicInterest::find($eachPublic);
						if(!empty($check)){
							EventPublicInterestList::insert(['event_id'=>$insert_id,'public_interest_id'=>$eachPublic, 'updated_at'=>Date('Y-m-d H:i:s'),'created_at' => Date('Y-m-d H:i:s')]);
						}
					}
				}
				if(count($music_interest) > 0){
					foreach($music_interest as $eachPublic){
						$check = MusicInterest::find($eachPublic);
						if(!empty($check)){
							EventMusicInterestList::insert(['event_id'=>$insert_id,'music_interest_id'=>$eachPublic, 'updated_at'=>Date('Y-m-d H:i:s'),'created_at' => Date('Y-m-d H:i:s')]);
						}
					}
				}
			}
			
			$event_date=$request->input('date');
			$event_date2=$request->input('event_date');
			
			$get_start_time = $request->input('time');
			$get_end_time = $request->input('end_time');
				
			if($request->input('repeat_interval')=='one_day'){
				$event_start_date_time = date('Y-m-d H:i:s', strtotime("$event_date $get_start_time"));
				$event_end_date_time = date('Y-m-d H:i:s', strtotime("$event_date $get_end_time"));
				if(strtotime($event_start_date_time) > strtotime($event_end_date_time)){
					$event_end_date_time = date('Y-m-d H:i:s', strtotime("+1 days", strtotime($event_end_date_time)));
				}
				$temp_data = ['event_id'=>$insert_id,'user_id'=>$id,'event_date'=>$event_date,'event_date2'=>$event_date2,'event_time'=>$request->input('time'),'end_time'=>$request->input('end_time'),'created_at'=>Date('Y-m-d H:i:s'),'event_start_date_time' => $event_start_date_time,'event_end_date_time' => $event_end_date_time];
				// print_r($temp_data); exit;
				$last_id=DB::table('event_schedule')->insertGetId($temp_data);
			}elseif($request->input('repeat_interval')=='monthly'){
				for($i=1;$i<=12;$i++){
					$event_start_date_time = date('Y-m-d H:i:s', strtotime("$event_date $get_start_time"));
					$event_end_date_time = date('Y-m-d H:i:s', strtotime("$event_date $get_end_time"));
					if(strtotime($event_start_date_time) > strtotime($event_end_date_time)){
						$event_end_date_time = date('Y-m-d H:i:s', strtotime("+1 days", strtotime($event_end_date_time)));
					}
					$temp_data =  ['event_id'=>$insert_id,'user_id'=>$id,'event_date'=>$event_date,'event_date2'=>$event_date2,'event_time'=>$request->input('time'),'end_time'=>$request->input('end_time'),'created_at'=>Date('Y-m-d H:i:s'),'event_start_date_time' => $event_start_date_time,'event_end_date_time' => $event_end_date_time];
					$last_id=DB::table('event_schedule')->insertGetId($temp_data);
					$get_date=DB::table('event_schedule')->where(['id'=>$last_id])->first();
					$event_date=date('Y-m-d', strtotime("+1 months", strtotime($get_date->event_date)));
					$event_date2=date('D j M',strtotime("+1 months", strtotime($get_date->event_date2))); 
				}  
			}elseif($request->input('repeat_interval')=='weekly'){
				for($i=1;$i<=48;$i++){ 
					$event_start_date_time = date('Y-m-d H:i:s', strtotime("$event_date $get_start_time"));
					$event_end_date_time = date('Y-m-d H:i:s', strtotime("$event_date $get_end_time"));
					if(strtotime($event_start_date_time) > strtotime($event_end_date_time)){
						$event_end_date_time = date('Y-m-d H:i:s', strtotime("+1 days", strtotime($event_end_date_time)));
					}
					  $last_id=DB::table('event_schedule')->insertGetId(['event_id'=>$insert_id,'user_id'=>$id,'event_date'=>$event_date,'event_date2'=>$event_date2,'event_time'=>$request->input('time'),'end_time'=>$request->input('end_time'),'created_at'=>Date('Y-m-d H:i:s'),'event_start_date_time' => $event_start_date_time,'event_end_date_time' => $event_end_date_time]);
					  $get_date=DB::table('event_schedule')->where(['id'=>$last_id])->first();
					  $event_date=date('Y-m-d', strtotime("+1 week", strtotime($get_date->event_date)));
					  $event_date2=date('D j M',strtotime("+1 week", strtotime($get_date->event_date2))); 
				} 
			}elseif($request->input('repeat_interval')=='2_weekly'){
				for($i=1;$i<=24;$i++){
					$event_start_date_time = date('Y-m-d H:i:s', strtotime("$event_date $get_start_time"));
					$event_end_date_time = date('Y-m-d H:i:s', strtotime("$event_date $get_end_time"));
					if(strtotime($event_start_date_time) > strtotime($event_end_date_time)){
						$event_end_date_time = date('Y-m-d H:i:s', strtotime("+1 days", strtotime($event_end_date_time)));
					}					
				  $last_id=DB::table('event_schedule')->insertGetId(['event_id'=>$insert_id,'user_id'=>$id,'event_date'=>$event_date,'event_date2'=>$event_date2,'event_time'=>$request->input('time'),'end_time'=>$request->input('end_time'),'created_at'=>Date('Y-m-d H:i:s'),'event_start_date_time' => $event_start_date_time,'event_end_date_time' => $event_end_date_time]);
				  $get_date=DB::table('event_schedule')->where(['id'=>$last_id])->first();
				  $event_date=date('Y-m-d', strtotime("+2 week", strtotime($get_date->event_date)));
				  $event_date2=date('D j M',strtotime("+2 week", strtotime($get_date->event_date2)));
				} 
			}  
			#send invitation to friends at event create time
			$get_lat_id=DB::table('event_schedule')
			->whereRaw("event_schedule.id IN (select MIN(event_schedule.id) FROM event_schedule WHERE event_id = '$insert_id')")
			->first();
			$get_event=DB::table('event_list')->where(['id'=>$insert_id])->first();
			$friend_ids = explode(',',$request->input('friend_id'));
			if(!empty($friend_ids[0])){      
				foreach($friend_ids as $friend_id ){
					$get_friend_name=DB::table('users')->where(['id'=>$friend_id])->first();
             		$get_friend = DB::table('invitations')->insert(['sender_id'=>$id,'receiver_id'=>$friend_id,'event_id'=>$insert_id,'sub_event_id'=>$get_lat_id->id,'created_at'=>date("Y-m-d h:i:s"),'timestamp'=>round(microtime(true) * 1000)]);
					#save invitation notification
					DB::table('notification_list')->insert(['user_id'=>$friend_id,'other_user_id'=>$id,'message'=>'you have received event invitation','notification_type'=>1,'timestamp'=>round(microtime(true) * 1000),'created_at' =>Date('Y-m-d H:i:s'),'updated_at' =>Date('Y-m-d H:i:s')]);
					#***************************
					$notify_count=$this->notification_count($friend_id);
					if($get_friend_name->device_type == 'I'){
							$message = array('sound' =>1,'message'=>'you have received event invitation',
							'notifykey'=>'invitation','data'=>'Mo-Tiv','title'=>'Mo-Tiv',
							'event_date'=>$get_event->event_date,'event_time'=>$get_event->event_time,'notify_count'=>$notify_count);
							$device_token=$get_friend_name->device_token;
							$dd=$this->send_iphone_notification($device_token,'you have received event invitation','invitation',$message);
					}   
					if($get_friend_name->device_type == 'A'){
						$message = array('sound' =>1,'message'=>'you have received event invitation',
						'notifykey'=>'invitation','data'=>'hello','title'=>'Mo-Tiv',
						'event_date'=>$get_event->event_date,'event_time'=>$get_event->event_time,'notify_count'=>$notify_count);
						$device_token=$get_friend_name->device_token;
						$this->send_android_notification($device_token,'you have received event invitation','invitation',$message);
					}  
				}   
			}
			$sub_admin_ids = explode(',',$request->input('sub_admin_id'));
			if(!empty($sub_admin_ids[0])){      
				foreach($sub_admin_ids as $sub_admin_id ){
					$get_friend_name=DB::table('users')->where(['id'=>$sub_admin_id])->first();
             		$get_friend = DB::table('event_sub_admins')->insert(['user_id'=>$sub_admin_id,'event_id'=>$insert_id]);
					#save sub admin notification
					DB::table('notification_list')->insert(['user_id'=>$sub_admin_id,'other_user_id'=>$id,'message'=>'You are a sub admin of this event','notification_type'=>1,'timestamp'=>round(microtime(true) * 1000),'created_at' =>Date('Y-m-d H:i:s'),'updated_at' =>Date('Y-m-d H:i:s')]);
					#***************************
					$notify_count=$this->notification_count($sub_admin_id);
					if($get_friend_name->device_type == 'I'){
							$message = array('sound' =>1,'message'=>'You are a sub admin of this event',
							'notifykey'=>'invitation','data'=>'Mo-Tiv','title'=>'Mo-Tiv',
							'event_date'=>$get_event->event_date,'event_time'=>$get_event->event_time,'notify_count'=>$notify_count);
							$device_token=$get_friend_name->device_token;
							$dd=$this->send_iphone_notification($device_token,'You are a sub admin of this event','invitation',$message);
					}   
					if($get_friend_name->device_type == 'A'){
						$message = array('sound' =>1,'message'=>'You are a sub admin of this event',
						'notifykey'=>'invitation','data'=>'hello','title'=>'Mo-Tiv',
						'event_date'=>$get_event->event_date,'event_time'=>$get_event->event_time,'notify_count'=>$notify_count);
						$device_token=$get_friend_name->device_token;
						$this->send_android_notification($device_token,'You are a sub admin of this event','invitation',$message);
					}  
				}   
			}
			$this->responseOk('Event submitted Successfully','');
		}else{
			$this->responseWithError('user type field is required');
		}
		
	}

	public function createEvent(Request $request){
		$id=$request->input('user_id');
		if(empty($id)){
			$user_data = $this->checkUserExist();
			$id=$user_data->id;
			if($user_data->blockStatus==1){
				$this->responseWithblock('You are blocked by admin');
			}      
		}    
		$user_type = $request->input('user_type');
		if(!empty($user_type) && is_numeric($user_type)){
			// 1=>admin, 2=> user, 3 => Organizer
			$request->merge(json_decode($request->event_media,true));
			//dd($request->all());
			if($user_type == 2){
				// extra parameters - video
				$validator = Validator::make($request->all(), [
					'image' =>'mimes:jpeg,png',
					'event_name' =>'required',
					'location' => 'required',
					'event_lat' => 'required',
					'event_long' => 'required',
					'event_start_date_time' => 'required',
					//'time' => 'required',
					'event_end_date_time' => 'required',
					//'event_date' => 'required',
					'event_detail' => 'required',
					//'post_type' => 'required', // 1 => public, 2 => private
					//'event_theme' => 'required|mimes:jpeg,png',
					'event_media_type' => 'required', // 1 => Image, 2 => video, 3 => none
					'repeat_interval' => 'required|in:one_day,monthly,weekly,2_weekly',
					//'sub_admin_id' => 'required',
					'event_image_url' => 'required_with:event_video_url',
				    'event_image_url2' => 'required_with:event_video_url2',
				    'primary_image' => 'required',
				    // 'event_image_url' => 'required',
				  //  'event_image_url2' => 'required',

				]);
				$event_status=2;
			}else if($user_type == 3){
				$validator = Validator::make($request->all(), [
					'event_name' =>'required',
					'location' => 'required',
					'event_lat' => 'required',
					'event_long' => 'required',
					'event_start_date_time' => 'required',
					//'time' => 'required',
					'event_end_date_time' => 'required',
					//'event_date' => 'required',
					//'ticket_price' => 'required|numeric|digits_between:1,999999',
					'dress_code' => 'required',
					'age_restrictions' => 'required',
					'id_Required' => 'required',
					'event_detail' => 'required',
					//'url' => 'required',
					//'post_type' => 'required', // 1 => public, 2 => private
					'event_media_type' => 'required', // 1 => Image, 2 => video, 3 => none
					'repeat_interval' => 'required|in:one_day,monthly,weekly,2_weekly',
					'event_image_url' => 'required_with:event_video_url',
				    'event_image_url2' => 'required_with:event_video_url2',
				    'primary_image' => 'required',
				  
					//'public_interest' => 'required', // in comma separete
					//'music_interest' => 'required', // in comma separete 
				]);
				$event_status=1;
			}else{
				$this->responseWithError('user type field is not match');
			}

			if($validator->fails()){
				$this->responseWithError($validator->errors()->first());
				exit;
			}
			$friend_ids = explode(',',$request->input('friend_id'));
			if(!empty($friend_ids[0])){      
				foreach($friend_ids as $friend_id ){
					$check_friend_id=DB::table('users')->where(['id'=>$friend_id])->first();
					if(empty($check_friend_id)){
						$this->responseWithError('You have entered wrong friend id');
					}				
				}
			}
			if($request->input('event_media_type') == 2){
				if(empty($request->file('image')) || empty($request->file('video'))){
					$this->responseWithError('video and thubnail field is not required');
				}	
			}
			
			$insert_array = [
				'event_name' => $request->input('event_name'),
				'event_location' => $request->input('location'),
				'event_lat' => $request->input('event_lat'),
				'event_long' => $request->input('event_long'),
				'event_date' => date('Y-m-d',(strtotime($request->input('event_start_date_time')))),
				'event_date2' => date('D j M',strtotime($request->input('event_start_date_time'))),
				'event_time' => date('H:i:s',(strtotime($request->input('event_start_date_time')))),
				'end_time' => date('H:i:s',(strtotime($request->input('event_end_date_time')))),
				'website' => $request->input('website'),
				'contact_number' => $request->input('contact_number'),
				'repeat_interval' => $request->input('repeat_interval'),
				'day_name'=>$nameOfDay = date('D', strtotime($request->input('date'))),
				'description' => $request->input('event_detail'),
				'post_type' => $request->input('post_type'), // 1 => public, 2 => private
				'event_media_type' => $request->input('event_media_type'),
				'submit_by' => $user_type, //1=>admin, 2=> user, 3 => Organizer
				'status' =>$event_status,
				'user_id' =>$id,
				'updated_at' => Date('Y-m-d H:i:s'),
				'created_at' => Date('Y-m-d H:i:s'),
			];
			   $event_date2=date('D j M',strtotime($request->input('event_start_date_time'))); 

                /*******upload video******/
			//	dd($request->file('event_image_url'));
				
				if(!empty($event_medias)){
					if(!empty($request->file('event_video_url'))){
						$image_name = str_random(20).'.mp4';
						$path = Storage::putFileAs('public/event_media',$request->file('event_video_url'),$image_name);
						$baseUrl = url('/');
						$baseUrl = str_replace('/public','/',$baseUrl);
						$insert_array['event_video_url'] = $baseUrl.'storage/app/'.$path;
					
						if(!empty($request->file('event_image_url'))){
							$image_name = str_random(20).'.png';
							$path = Storage::putFileAs('public/event_media',$request->file('event_image_url'),$image_name);
							$baseUrl = url('/');
							$baseUrl = str_replace('/public','/',$baseUrl);
							$insert_array['event_image_url'] = $baseUrl.'storage/app/'.$path;
						}

					}else{
						$insert_array['event_video_url'] = '';
					}
				
					if(!empty($request->file('event_video_url2'))){
						$video = $request->file('event_video_url2');
						$image_name = str_random(20).'.mp4';
						$path = Storage::putFileAs('public/event_media', $request->file('event_video_url2'),$image_name);
						$baseUrl = url('/');
						$baseUrl = str_replace('/public','/',$baseUrl);
						$insert_array['event_video_url2'] = $baseUrl.'storage/app/'.$path;
						if(!empty($request->file('event_image_url2'))){
							$image_name = str_random(20).'.png';
							$path = Storage::putFileAs('public/event_media', $request->file('event_image_url2'),$image_name);
							$baseUrl = url('/');
							$baseUrl = str_replace('/public','/',$baseUrl);
							$insert_array['event_image_url2'] = $baseUrl.'storage/app/'.$path;
						}	
					}else{
						$insert_array['event_video_url2'] = '';
					}
					if(empty($request->file('event_video_url'))){
						if(!empty($event_medias['event_image_url'])){
							$image_name = str_random(20).'.png';
							$path = Storage::putFileAs('public/event_media', $request->file('event_image_url'),$image_name);
							$baseUrl = url('/');
							$baseUrl = str_replace('/public','/',$baseUrl);
							$insert_array['event_image_url'] = $baseUrl.'storage/app/'.$path;
						}	
					}
					if(empty($request->file('event_video_url2'))){
						if(!empty($event_medias['event_image_url2'])){
							$image_name = str_random(20).'.png';
							$path = Storage::putFileAs('public/event_media', $event_medias['event_image_url2'],$image_name);
							$baseUrl = url('/');
							$baseUrl = str_replace('/public','/',$baseUrl);
							$insert_array['event_image_url2'] = $baseUrl.'storage/app/'.$path;
						}	
					}
					
				}
				if(!empty($request->file('event_theme'))){
					$image_name = str_random(20).'.png';
					$path = Storage::putFileAs('public/event_theme', $request->file('event_theme'), $image_name);
					$baseUrl = url('/');
					$baseUrl = str_replace('/public','/',$baseUrl);
					$insert_array['event_theme_url'] = $baseUrl.'storage/app/'.$path;
				}else{
					$insert_array['event_theme_url'] = '';
				}

				if(!empty($request->file('primary_image'))){
					$image_name = str_random(20).'.png';
					$path = Storage::putFileAs('public/event_theme', $request->file('primary_image'), $image_name);
					$baseUrl = url('/');
					$baseUrl = str_replace('/public','/',$baseUrl);
					$insert_array['primary_image'] = $baseUrl.'storage/app/'.$path;
				}else{
					$insert_array['primary_image'] = '';
				}

				
			
			if($user_type == 3){
				$insert_array['ticket_amount'] = $request->input('ticket_amount');
				$insert_array['dress_code'] = $request->input('dress_code');
				$insert_array['age_restrictions'] = $request->input('age_restrictions');
				$insert_array['id_Required'] = $request->input('id_Required');
				$insert_array['url'] = $request->input('url');
				$insert_array['post_type'] =1;
				$insert_array['music_int_id']=$request->input('music_interest');
				$insert_array['public_int_id']=$request->input('public_interest');
			}else{
				$insert_array['ticket_amount'] = 0;
				$insert_array['dress_code'] = '';
				$insert_array['age_restrictions'] = 0;
				$insert_array['id_Required'] = '';
				$insert_array['url'] = '';
				$insert_array['post_type'] =2;
				$insert_array['music_int_id']=$request->input('music_interest');
				$insert_array['public_int_id']=$request->input('public_interest');
			}
			  
			$insert_id = EventList::insertGetId($insert_array);
			$guests = DB::table('invitations')->insert(['event_id'=>$insert_id,'sender_id'=>0,'receiver_id'=>$id,'request_status'=>1,'created_at'=>date("Y-m-d h:i:s"),'timestamp'=>round(microtime(true) * 1000)]);
			if($user_type == 3){
				$public_interest = explode(',',$request->input('public_interest'));
				$music_interest =  explode(',',$request->input('music_interest'));
				if(count($public_interest) > 0){
					foreach($public_interest as $eachPublic){
						$check = PublicInterest::find($eachPublic);
						if(!empty($check)){
							EventPublicInterestList::insert(['event_id'=>$insert_id,'public_interest_id'=>$eachPublic, 'updated_at'=>Date('Y-m-d H:i:s'),'created_at' => Date('Y-m-d H:i:s')]);
						}
					}
				}
				if(count($music_interest) > 0){
					foreach($music_interest as $eachPublic){
						$check = MusicInterest::find($eachPublic);
						if(!empty($check)){
							EventMusicInterestList::insert(['event_id'=>$insert_id,'music_interest_id'=>$eachPublic, 'updated_at'=>Date('Y-m-d H:i:s'),'created_at' => Date('Y-m-d H:i:s')]);
						}
					}
				}
			}

            if(!empty($request->add_ticket)){
            	$return_data=$this->add_tickets($request->add_ticket,$insert_id);
				if($return_data==2){
					$this->responseWithError('Please fill all add ticket fields');
				}
			}

			
			$event_date=$request->input('event_start_date_time');
			$event_date=$request->input('event_end_date_time');
			$event_date2=date('D j M',strtotime($request->input('event_start_date_time')));
			$get_start_time = date('H:i:s',(strtotime($request->input('event_start_date_time'))));
			$get_end_time = date('H:i:s',(strtotime($request->input('event_end_date_time'))));
			$event_time=date('H:i:s',(strtotime($request->input('event_start_date_time'))));	
			$end_time=date('H:i:s',(strtotime($request->input('event_end_date_time'))));
			
			if($request->input('repeat_interval')=='one_day'){
				$event_start_date_time = date('Y-m-d H:i:s', strtotime($request->input('event_start_date_time')));
				$event_end_date_time = date('Y-m-d H:i:s', strtotime($request->input('event_end_date_time')));
				if(strtotime($event_start_date_time) > strtotime($event_end_date_time)){
					$event_end_date_time = date('Y-m-d H:i:s', strtotime("+1 days", strtotime($event_end_date_time)));
				}
				$temp_data = ['event_id'=>$insert_id,'user_id'=>$id,'event_date'=>$event_date,'event_date2'=>$event_date2,'event_time'=>$event_time,'end_time'=>$end_time,'created_at'=>Date('Y-m-d H:i:s'),'event_start_date_time' => $event_start_date_time,'event_end_date_time' => $event_end_date_time];
				// print_r($temp_data); exit;
				$last_id=DB::table('event_schedule')->insertGetId($temp_data);
			}elseif($request->input('repeat_interval')=='monthly'){
				for($i=1;$i<=12;$i++){
					$event_start_date_time = date('Y-m-d H:i:s', strtotime($request->input('event_start_date_time')));
					$event_end_date_time = date('Y-m-d H:i:s', strtotime($request->input('event_end_date_time')));
					if(strtotime($event_start_date_time) > strtotime($event_end_date_time)){
						$event_end_date_time = date('Y-m-d H:i:s', strtotime("+1 days", strtotime($event_end_date_time)));
					}
					$temp_data =  ['event_id'=>$insert_id,'user_id'=>$id,'event_date'=>$event_date,'event_date2'=>$event_date2,'event_time'=>$event_time,'end_time'=>$end_time,'created_at'=>Date('Y-m-d H:i:s'),'event_start_date_time' => $event_start_date_time,'event_end_date_time' => $event_end_date_time];
					$last_id=DB::table('event_schedule')->insertGetId($temp_data);
					$get_date=DB::table('event_schedule')->where(['id'=>$last_id])->first();
					$event_date=date('Y-m-d', strtotime("+1 months", strtotime($get_date->event_date)));
					$event_date2=date('D j M',strtotime("+1 months", strtotime($get_date->event_date2))); 
				}  
			}elseif($request->input('repeat_interval')=='weekly'){
				for($i=1;$i<=48;$i++){ 
					$event_start_date_time = date('Y-m-d H:i:s', strtotime($request->input('event_start_date_time')));
					$event_end_date_time = date('Y-m-d H:i:s', strtotime($request->input('event_end_date_time')));
					if(strtotime($event_start_date_time) > strtotime($event_end_date_time)){
						$event_end_date_time = date('Y-m-d H:i:s', strtotime("+1 days", strtotime($event_end_date_time)));
					}
					  $last_id=DB::table('event_schedule')->insertGetId(['event_id'=>$insert_id,'user_id'=>$id,'event_date'=>$event_date,'event_date2'=>$event_date2,'event_time'=>$event_time,'end_time'=>$end_time,'created_at'=>Date('Y-m-d H:i:s'),'event_start_date_time' => $event_start_date_time,'event_end_date_time' => $event_end_date_time]);
					  $get_date=DB::table('event_schedule')->where(['id'=>$last_id])->first();
					  $event_date=date('Y-m-d', strtotime("+1 week", strtotime($get_date->event_date)));
					  $event_date2=date('D j M',strtotime("+1 week", strtotime($get_date->event_date2))); 
				} 
			}elseif($request->input('repeat_interval')=='2_weekly'){
				for($i=1;$i<=24;$i++){
					$event_start_date_time = date('Y-m-d H:i:s', strtotime($request->input('event_start_date_time')));
					$event_end_date_time = date('Y-m-d H:i:s', strtotime($request->input('event_end_date_time')));
					if(strtotime($event_start_date_time) > strtotime($event_end_date_time)){
						$event_end_date_time = date('Y-m-d H:i:s', strtotime("+1 days", strtotime($event_end_date_time)));
					}					
				  $last_id=DB::table('event_schedule')->insertGetId(['event_id'=>$insert_id,'user_id'=>$id,'event_date'=>$event_date,'event_date2'=>$event_date2,'event_time'=>$event_time,'end_time'=>$end_time,'created_at'=>Date('Y-m-d H:i:s'),'event_start_date_time' => $event_start_date_time,'event_end_date_time' => $event_end_date_time]);
				  $get_date=DB::table('event_schedule')->where(['id'=>$last_id])->first();
				  $event_date=date('Y-m-d', strtotime("+2 week", strtotime($get_date->event_date)));
				  $event_date2=date('D j M',strtotime("+2 week", strtotime($get_date->event_date2)));
				} 
			}  
			#send invitation to friends at event create time
			$get_lat_id=DB::table('event_schedule')
			->whereRaw("event_schedule.id IN (select MIN(event_schedule.id) FROM event_schedule WHERE event_id = '$insert_id')")
			->first();
			$get_event=DB::table('event_list')->where(['id'=>$insert_id])->first();
			$friend_ids = explode(',',$request->input('friend_id'));
			if(!empty($friend_ids[0])){      
				foreach($friend_ids as $friend_id ){
					$get_friend_name=DB::table('users')->where(['id'=>$friend_id])->first();
             		$get_friend = DB::table('invitations')->insert(['sender_id'=>$id,'receiver_id'=>$friend_id,'event_id'=>$insert_id,'sub_event_id'=>$get_lat_id->id,'created_at'=>date("Y-m-d h:i:s"),'timestamp'=>round(microtime(true) * 1000)]);
					#save invitation notification
					DB::table('notification_list')->insert(['user_id'=>$friend_id,'other_user_id'=>$id,'message'=>'you have received event invitation','notification_type'=>1,'timestamp'=>round(microtime(true) * 1000),'created_at' =>Date('Y-m-d H:i:s'),'updated_at' =>Date('Y-m-d H:i:s')]);
					#***************************
					$notify_count=$this->notification_count($friend_id);
					if($get_friend_name->device_type == 'I'){
							$message = array('sound' =>1,'message'=>'you have received event invitation',
							'notifykey'=>'invitation','data'=>'Mo-Tiv','title'=>'Mo-Tiv',
							'event_date'=>$get_event->event_date,'event_time'=>$get_event->event_time,'notify_count'=>$notify_count);
							$device_token=$get_friend_name->device_token;
							$dd=$this->send_iphone_notification($device_token,'you have received event invitation','invitation',$message);
					}   
					if($get_friend_name->device_type == 'A'){
						$message = array('sound' =>1,'message'=>'you have received event invitation',
						'notifykey'=>'invitation','data'=>'hello','title'=>'Mo-Tiv',
						'event_date'=>$get_event->event_date,'event_time'=>$get_event->event_time,'notify_count'=>$notify_count);
						$device_token=$get_friend_name->device_token;
						$this->send_android_notification($device_token,'you have received event invitation','invitation',$message);
					}  
				}   
			}
			$sub_admin_ids = explode(',',$request->input('sub_admin_id'));
			if(!empty($sub_admin_ids[0])){      
				foreach($sub_admin_ids as $sub_admin_id ){
					$get_friend_name=DB::table('users')->where(['id'=>$sub_admin_id])->first();
             		$get_friend = DB::table('event_sub_admins')->insert(['user_id'=>$sub_admin_id,'event_id'=>$insert_id]);
					#save sub admin notification
					DB::table('notification_list')->insert(['user_id'=>$sub_admin_id,'other_user_id'=>$id,'message'=>'You are a sub admin of this event','notification_type'=>1,'timestamp'=>round(microtime(true) * 1000),'created_at' =>Date('Y-m-d H:i:s'),'updated_at' =>Date('Y-m-d H:i:s')]);
					#***************************
					$notify_count=$this->notification_count($sub_admin_id);
					if($get_friend_name->device_type == 'I'){
							$message = array('sound' =>1,'message'=>'You are a sub admin of this event',
							'notifykey'=>'invitation','data'=>'Mo-Tiv','title'=>'Mo-Tiv',
							'event_date'=>$get_event->event_date,'event_time'=>$get_event->event_time,'notify_count'=>$notify_count);
							$device_token=$get_friend_name->device_token;
							$dd=$this->send_iphone_notification($device_token,'You are a sub admin of this event','invitation',$message);
					}   
					if($get_friend_name->device_type == 'A'){
						$message = array('sound' =>1,'message'=>'You are a sub admin of this event',
						'notifykey'=>'invitation','data'=>'hello','title'=>'Mo-Tiv',
						'event_date'=>$get_event->event_date,'event_time'=>$get_event->event_time,'notify_count'=>$notify_count);
						$device_token=$get_friend_name->device_token;
						$this->send_android_notification($device_token,'You are a sub admin of this event','invitation',$message);
					}  
				}   
			}
			$this->responseOk('Event submitted Successfully','');
		}else{
			$this->responseWithError('user type field is required');
		}
		
	}
	
	/********************************************************************************************/
	public function edit_event(Request $request){
		$id=$request->input('user_id');
		if(empty($id)){
			$user_data = $this->checkUserExist();
			$id=$user_data->id;
			if($user_data->blockStatus==1){
				$this->responseWithblock('You are blocked by admin');
			}
			$user_type=$user_data->role;
		}
		 
		//$user_type=$request->input('user_type');
			// 1=>admin, 2=> user, 3 => Organizer
			if($user_type == 2){
				// extra parameters - video
				$validator = Validator::make($request->all(), [
				    'event_id' =>'required',
					'event_name' =>'required',
					'location' => 'required',
					'event_lat' => 'required',
					'event_long' => 'required',
					'date' => 'required',
					'event_date' =>'required',
					'time' => 'required',
					'end_time' => 'required',
					'event_detail' => 'required',
					//'post_type' => 'required', // 1 => public, 2 => private
					//'event_media_type' => 'required', // 1 => Image, 2 => video, 3 => none
				]);
				$event_status=2;
			}elseif($user_type == 3){
				$validator = Validator::make($request->all(), [
				    'event_id' =>'required',
					'event_name' =>'required',
					'location' =>'required',
					'event_lat' =>'required',
					'event_long' =>'required',
					'date' =>'required',
					'event_date' =>'required',
					'time' =>'required',
					'end_time' =>'required',
					'dress_code' =>'required',
					'age_restrictions' =>'required',
					'id_Required' => 'required',
					'event_detail' => 'required',
					'public_interest' => 'required',
					'ticket_price' => 'required',
					'url' => 'required',
				
				]);
				$event_status=1;
			}
			if($validator->fails()){
				$this->responseWithError($validator->errors()->first());
				exit;
			}
			$event_id=$request->input('event_id');
			$get_event=DB::table('event_schedule')->where(['id'=>$event_id])->first();
			if(empty($get_event)){
				$this->responseWithError('Event id not exist');
			}
			$check_event=DB::table('event_list')->where(['id'=>$get_event->event_id])->first();
			
			#check for valid friend id
			$friend_ids = explode(',',$request->input('friend_id'));
			if(!empty($friend_ids[0])){      
				foreach($friend_ids as $friend_id ){
					$check_friend_id=DB::table('users')->where(['id'=>$friend_id])->first();
					if(empty($check_friend_id)){
						$this->responseWithError('You have entered wrong friend id');
					}				
				}
			}
			#check friend invitation status
			if(!empty($friend_ids[0])){
				foreach($friend_ids as $friend_id ){
					$check_request=DB::table('invitations')->where(function ($query) use ($id,$friend_id,$event_id){
												$query->where(['receiver_id'=>$friend_id])
												->where(['sub_event_id'=>$event_id]);
												})->orWhere(function($query) use ($id,$friend_id,$event_id){
												$query->where(['sender_id'=>$friend_id])
												//->where(['receiver_id'=>$id])
												->where(['sub_event_id'=>$event_id]);
												})->first();							
												
												
					if(!empty($check_request)){									
						//$this->responseOk('This user already invited for this event','');
					}
				}
			}
			if($request->input('event_media_type') == 2){
				if(empty($request->file('image')) || empty($request->file('video'))){
					//$this->responseWithError('video and thubnail field is not required');
				}	
			}
			  
			$insert_array = [
				'event_name' => $request->input('event_name'),
				'event_location' => $request->input('location'),
				'event_lat' => $request->input('event_lat'),
				'event_long' => $request->input('event_long'),
				'event_date' => $request->input('date'),
				'event_date2' => $request->input('event_date'),
				'event_time' => $request->input('time'),
				'end_time' => $request->input('end_time'),
				'repeat_interval' => $request->input('repeat_interval'),
				'website' => $request->input('website'),
				'contact_number' => $request->input('contact_number'),
				'day_name'=>$nameOfDay = date('D', strtotime($request->input('date'))),
				'description' => $request->input('event_detail'),
				'post_type' => $request->input('post_type'), // 1 => public, 2 => private
				'event_media_type' => $request->input('event_media_type'),
				'submit_by' => $user_type, //1=>admin, 2=> user, 3 => Organizer
				//'status' =>$event_status,
				'user_id' =>$id,
				'updated_at' => Date('Y-m-d H:i:s'),
				'created_at' => Date('Y-m-d H:i:s'),
			];
			
				if((!empty($request->file('image')) && $request->input('event_media_type') == 1) || (!empty($request->file('image')) && $request->input('event_media_type') == 2)){
						$image_name = str_random(20).'.png';
						$path = Storage::putFileAs('public/event_media', $request->file('image'),$image_name);
						// $insert_array['event_image_url'] = url('storage/event_media/'.$image_name);
						// $insert_array['event_image_url']='http://54.206.13.240/sim_admin/storage/app/public/event_media/'.$image_name;
						// $insert_array['event_image_url']='http://175.176.186.116/webservices/sim_admin/storage/app/public/event_media/'.$image_name;
						$baseUrl = url('/');
						$baseUrl = str_replace('/public','/',$baseUrl);
						$insert_array['event_image_url'] = $baseUrl.'storage/app/'.$path;
						DB::table('event_list')->where(['id'=>$request->input('event_id')])->update(['event_image_url'=>$insert_array['event_image_url']]);
				}
				if(!empty($request->file('video')) && $request->input('event_media_type') == 2){
					$video = $request->file('video');
					// $video->move(public_path('upload/event_media'),$video->getClientOriginalName());
					// $insert_array['event_video_url'] = url('public/event_media/'.$video->getClientOriginalName());
					$image_name = str_random(20).'.mp4';
					$path = Storage::putFileAs('public/event_media', $request->file('video'),$image_name);
					// $insert_array['event_video_url'] = url('storage/event_media/'.$image_name);
					// $insert_array['event_video_url']='http://54.206.13.240/sim_admin/storage/app/public/event_media/'.$image_name;
					// $insert_array['event_video_url']='http://175.176.186.116/webservices/sim_admin/storage/app/public/event_media/'.$image_name;
					$baseUrl = url('/');
					$baseUrl = str_replace('/public','/',$baseUrl);
					$insert_array['event_video_url'] = $baseUrl.'storage/app/'.$path;
					DB::table('event_list')->where(['id'=>$request->input('event_id')])->update(['event_video_url'=>$insert_array['event_video_url']]);
				}  
				if(!empty($request->file('event_theme'))){
					$image_name = str_random(20).'.png';
					$path = Storage::putFileAs('public/event_theme', $request->file('event_theme'), $image_name);
					$insert_array['event_theme_url'] = url('storage/event_theme/'.$image_name);
					$insert_array['event_theme_url']='http://54.206.13.240/sim_admin/storage/app/public/event_theme/'.$image_name;
					// $insert_array['event_theme_url']='http://175.176.186.116/webservices/sim_admin/storage/app/public/event_theme/'.$image_name;
					//print $insert_array['event_theme_url'];
					// $insert_array['event_theme_url'] = '';
				    DB::table('event_list')->where(['id'=>$request->input('event_id')])->update(['event_theme_url'=>$insert_array['event_theme_url']]);
				}
				
			if($user_type == 3){
				$insert_array['ticket_price'] = $request->input('ticket_price');
				$insert_array['dress_code'] = $request->input('dress_code');
				$insert_array['age_restrictions'] = $request->input('age_restrictions');
				$insert_array['id_Required'] = $request->input('id_Required');
				$insert_array['url'] = $request->input('url');
				$insert_array['post_type'] =1;
				$insert_array['music_int_id']=$request->input('music_interest');
				$insert_array['public_int_id']=$request->input('public_interest');
			}else{
				$insert_array['ticket_price'] = 0;
				$insert_array['dress_code'] = '';
				$insert_array['age_restrictions'] = 0;
				$insert_array['id_Required'] = '';
				$insert_array['url'] = '';
				$insert_array['post_type'] =2;
				$insert_array['music_int_id']=$request->input('music_interest');
				$insert_array['public_int_id']=$request->input('public_interest');
			}
			//$insert_id = EventList::insertGetId($insert_array);
			$event_date = $request->input('date');
			$get_start_time = $request->input('time');
			$get_end_time = $request->input('end_time');
			
			$event_start_date_time = date('Y-m-d H:i:s', strtotime("$event_date $get_start_time"));
			$event_end_date_time = date('Y-m-d H:i:s', strtotime("$event_date $get_end_time"));
			if(strtotime($event_start_date_time) > strtotime($event_end_date_time)){
				$event_end_date_time = date('Y-m-d H:i:s', strtotime("+1 days", strtotime($event_end_date_time)));
			}
						
						
			DB::table('event_list')->where(['id'=>$get_event->event_id])->update($insert_array);
			DB::table('event_schedule')->where(['id'=>$event_id])
								->update(['event_date'=>$event_date,
								'event_date2'=>$request->input('event_date'),
								'event_time'=>$request->input('time'),
								'end_time'=>$request->input('end_time'),
								'event_start_date_time' =>$event_start_date_time,
								'event_end_date_time' =>$event_end_date_time 
			                     ]);      
			//$guests = DB::table('invitations')->insert(['event_id'=>$insert_id,'sender_id'=>0,'receiver_id'=>$id,'request_status'=>1,'created_at'=>date("Y-m-d h:i:s"),'timestamp'=>round(microtime(true) * 1000)]);
			if($user_type == 3){
				$public_interest = explode(',',$request->input('public_interest'));
				$music_interest =  explode(',',$request->input('music_interest'));
				if(count($public_interest) > 0){
					DB::table('event_public_interest_list')->where(['event_id'=>$get_event->event_id])->delete();
					foreach($public_interest as $eachPublic){
						$check = PublicInterest::find($eachPublic);
						if(!empty($check)){
							EventPublicInterestList::insert(['event_id'=>$get_event->event_id,'public_interest_id'=>$eachPublic, 'updated_at'=>Date('Y-m-d H:i:s'),'created_at' => Date('Y-m-d H:i:s')]);
						}
					}
				}
				if(count($music_interest) > 0){
					DB::table('event_music_interest_list')->where(['event_id'=>$get_event->event_id])->delete();
					foreach($music_interest as $eachPublic){
						$check = MusicInterest::find($eachPublic);
						if(!empty($check)){
							EventMusicInterestList::insert(['event_id'=>$get_event->event_id,'music_interest_id'=>$eachPublic, 'updated_at'=>Date('Y-m-d H:i:s'),'created_at' => Date('Y-m-d H:i:s')]);
						}
					}
				}
			}
			#send invitation to friends at event create time
			$friend_ids = explode(',',$request->input('friend_id'));
			//print_r($friend_ids);die;
			if(!empty($friend_ids[0])){      
				foreach($friend_ids as $friend_id ){
					$check_request=DB::table('invitations')->where(function ($query) use ($id,$friend_id,$event_id){
												$query->where(['receiver_id'=>$friend_id])
												->where(['sub_event_id'=>$event_id]);
												})->orWhere(function($query) use ($id,$friend_id,$event_id){
												$query->where(['sender_id'=>$friend_id])
												//->where(['receiver_id'=>$id])
												->where(['sub_event_id'=>$event_id]);
												})->first();							
												
												
					if(empty($check_request)){									
						$get_friend_name=DB::table('users')->where(['id'=>$friend_id])->first();
						$get_friend = DB::table('invitations')->insert(['sender_id'=>$id,'receiver_id'=>$friend_id,'sub_event_id'=>$event_id,'event_id'=>$get_event->event_id,'created_at'=>date("Y-m-d h:i:s"),'timestamp'=>round(microtime(true) * 1000)]);
						#save invitation notification
						DB::table('notification_list')->insert(['user_id'=>$friend_id,'other_user_id'=>$id,'message'=>'you have received event invitation','notification_type'=>1,'timestamp'=>round(microtime(true) * 1000),'created_at' =>Date('Y-m-d H:i:s'),'updated_at' =>Date('Y-m-d H:i:s')]);
						#***************************
						$notify_count=$this->notification_count($friend_id);
						
						if($get_friend_name->device_type == 'I'){
								$message = array('sound' =>1,'message'=>'you have received event invitation',
								'notifykey'=>'invitation','data'=>'Mo-Tiv','title'=>'Mo-Tiv',
								'event_date'=>$get_event->event_date,'event_time'=>$get_event->event_time,'notify_count'=>$notify_count);
								$device_token=$get_friend_name->device_token;
								$dd=$this->send_iphone_notification($device_token,'you have received event invitation','invitation',$message);
						}   
						if($get_friend_name->device_type == 'A'){
							$message = array('sound' =>1,'message'=>'you have received event invitation',
							'notifykey'=>'invitation','data'=>'hello','title'=>'Mo-Tiv',
							'event_date'=>$get_event->event_date,'event_time'=>$get_event->event_time,'notify_count'=>$notify_count);
							$device_token=$get_friend_name->device_token;
							$this->send_android_notification($device_token,'you have received event invitation','invitation',$message);
						}
					}
					
                  					
				}
        	}
			
			if($check_event->repeat_interval != $request->input('repeat_interval')){
				#delete events if user change interval
				DB::table('event_schedule')->where(['event_id'=>$get_event->event_id])->delete();
				$music_interest=DB::table('invitations')->where(['sub_event_id'=>$request->input('event_id')])->delete();
				$check_post=DB::table('post_list')->where(['event_id'=>$request->input('event_id')])->first();
				if(!empty($check_post)){  
					DB::table('likes')->where(['post_id'=>$check_post->id])->delete();
					DB::table('comments')->where(['post_id'=>$check_post->id])->delete();
					DB::table('post_list')->where(['event_id'=>$request->input('event_id')])->delete();
				}
				#-----------------------------------------
				$event_date2=$request->input('event_date');
				$event_date=$request->input('date');
				
				if($request->input('repeat_interval')=='one_day'){
					$event_start_date_time = date('Y-m-d H:i:s', strtotime("$event_date $get_start_time"));
					$event_end_date_time = date('Y-m-d H:i:s', strtotime("$event_date $get_end_time"));
					if(strtotime($event_start_date_time) > strtotime($event_end_date_time)){
						$event_end_date_time = date('Y-m-d H:i:s', strtotime("+1 days", strtotime($event_end_date_time)));
					}
					$event_date=$request->input('date');
					$last_id=DB::table('event_schedule')->insertGetId(['event_id'=>$get_event->event_id,'event_date2'=>$event_date2,'user_id'=>$id,'event_date'=>$event_date,'event_time'=>$request->input('time'),'end_time'=>$request->input('end_time'),'created_at'=>Date('Y-m-d H:i:s'),'event_start_date_time' => $event_start_date_time,'event_end_date_time' => $event_end_date_time]);
				}elseif($request->input('repeat_interval')=='monthly'){
					$event_date=$request->input('date');
					for($i=1;$i<=12;$i++){
						$event_start_date_time = date('Y-m-d H:i:s', strtotime("$event_date $get_start_time"));
						$event_end_date_time = date('Y-m-d H:i:s', strtotime("$event_date $get_end_time"));
						if(strtotime($event_start_date_time) > strtotime($event_end_date_time)){
							$event_end_date_time = date('Y-m-d H:i:s', strtotime("+1 days", strtotime($event_end_date_time)));
						}
					  $last_id=DB::table('event_schedule')->insertGetId(['event_id'=>$get_event->event_id,'event_date2'=>$event_date2,'user_id'=>$id,'event_date'=>$event_date,'event_time'=>$request->input('time'),'end_time'=>$request->input('end_time'),'created_at'=>Date('Y-m-d H:i:s'),'event_start_date_time' => $event_start_date_time,'event_end_date_time' => $event_end_date_time]);
					  $get_date=DB::table('event_schedule')->where(['id'=>$last_id])->first();
					  $event_date=date('Y-m-d', strtotime("+1 months", strtotime($get_date->event_date)));
					  $event_date2=date('D j M',strtotime("+1 months", strtotime($get_date->event_date2))); 
					}  
				}elseif($request->input('repeat_interval')=='weekly'){
					$event_date=$request->input('date');
					for($i=1;$i<=48;$i++){  
						$event_start_date_time = date('Y-m-d H:i:s', strtotime("$event_date $get_start_time"));
						$event_end_date_time = date('Y-m-d H:i:s', strtotime("$event_date $get_end_time"));
						if(strtotime($event_start_date_time) > strtotime($event_end_date_time)){
							$event_end_date_time = date('Y-m-d H:i:s', strtotime("+1 days", strtotime($event_end_date_time)));
						}
					
					  $last_id=DB::table('event_schedule')->insertGetId(['event_id'=>$get_event->event_id,'event_date2'=>$event_date2,'user_id'=>$id,'event_date'=>$event_date,'event_time'=>$request->input('time'),'end_time'=>$request->input('end_time'),'created_at'=>Date('Y-m-d H:i:s'),'event_start_date_time' => $event_start_date_time,'event_end_date_time' => $event_end_date_time]);
					  $get_date=DB::table('event_schedule')->where(['id'=>$last_id])->first();
					  $event_date=date('Y-m-d', strtotime("+1 week", strtotime($get_date->event_date)));
					  $event_date2=date('D j M',strtotime("+1 week", strtotime($get_date->event_date2)));
					} 
				}elseif($request->input('repeat_interval')=='2_weekly'){
					$event_date=$request->input('date');     
					for($i=1;$i<=24;$i++){
						$event_start_date_time = date('Y-m-d H:i:s', strtotime("$event_date $get_start_time"));
						$event_end_date_time = date('Y-m-d H:i:s', strtotime("$event_date $get_end_time"));
						if(strtotime($event_start_date_time) > strtotime($event_end_date_time)){
							$event_end_date_time = date('Y-m-d H:i:s', strtotime("+1 days", strtotime($event_end_date_time)));
						}
					  $last_id=DB::table('event_schedule')->insertGetId(['event_id'=>$get_event->event_id,'user_id'=>$id,'event_date'=>$event_date,'event_date2'=>$event_date2,'event_time'=>$request->input('time'),'end_time'=>$request->input('end_time'),'created_at'=>Date('Y-m-d H:i:s'),'event_start_date_time' => $event_start_date_time,'event_end_date_time' => $event_end_date_time]);
					  $get_date=DB::table('event_schedule')->where(['id'=>$last_id])->first();
					  $event_date=date('Y-m-d', strtotime("+2 week", strtotime($get_date->event_date)));
					  $event_date2=date('D j M',strtotime("+2 week", strtotime($get_date->event_date2)));
					} 
				}
			}
			#event updation notification
			$guests = DB::table('invitations')
					->leftJoin('users', 'users.id', '=', 'invitations.receiver_id')
					->where(['request_status'=>1,'sub_event_id'=>$event_id])
					//->where(['request_status'=>1,'event_id'=>$get_event->event_id])
					->get();
			 //echo '<pre>';		
           //print_r($guests);					
			if((count($guests)>0)){  
				foreach($guests as $guests){
				#save invitation notification  
					DB::table('notification_list')->insert(['user_id'=>$guests->id,'other_user_id'=>$id,'message'=>$check_event->event_name.':'.'Event detail has been updated','notification_type'=>1,'timestamp'=>round(microtime(true) * 1000),'created_at' =>Date('Y-m-d H:i:s'),'updated_at' =>Date('Y-m-d H:i:s')]);
					#***************************  
					$notify_count=$this->notification_count($guests->id);
					if($guests->device_type == 'I'){
							$message = array('sound' =>1,'message'=>$check_event->event_name.':'.'Event detail has been updated',
							'notifykey'=>'invite_invitation','data'=>'Mo-Tiv','title'=>'Mo-Tiv',
							'event_date'=>$get_event->event_date,'event_time'=>$get_event->event_time,'notify_count'=>$notify_count);
							$device_token=$guests->device_token;
							$dd=$this->send_iphone_notification($device_token,$check_event->event_name.':'.'Event detail has been updated','invite_invitation',$message);
					    
					}     
					if($guests->device_type == 'A'){
						$message = array('sound' =>1,'message'=>$check_event->event_name.':'.'Event detail has been updated',
						'notifykey'=>'invite_invitation','data'=>'hello','title'=>'Mo-Tiv',
						'event_date'=>$get_event->event_date,'event_time'=>$get_event->event_time,'notify_count'=>$notify_count);
						$device_token=$guests->device_token;
						$rr=$this->send_android_notification($device_token,$check_event->event_name.':'.'Event detail has been updated','invite_invitation',$message);
					 
					}
				}
			}
			
			
			#notification to event owner if event update by co admin
			if($id != $check_event->user_id){
					#save invitation notification  
					DB::table('notification_list')->insert(['user_id'=>$check_event->user_id,'other_user_id'=>$id,'message'=>$check_event->event_name.':'.'Event detail has been updated','notification_type'=>1,'timestamp'=>round(microtime(true) * 1000),'created_at' =>Date('Y-m-d H:i:s'),'updated_at' =>Date('Y-m-d H:i:s')]);
					#***************************  
					$event_owner=DB::table('users')->where(['id'=>$check_event->user_id])->first();
					$notify_count=$this->notification_count($check_event->user_id);
					if($event_owner->device_type == 'I'){
							$message = array('sound' =>1,'message'=>$check_event->event_name.':'.'Event detail has been updated',
							'notifykey'=>'invite_invitation','data'=>'Mo-Tiv','title'=>'Mo-Tiv',
							'event_date'=>$get_event->event_date,'event_time'=>$get_event->event_time,'notify_count'=>$notify_count);
							$device_token=$event_owner->device_token;
							$dd=$this->send_iphone_notification($device_token,$check_event->event_name.':'.'Event detail has been updated','invite_invitation',$message);
					    
					}     
					if($event_owner->device_type == 'A'){
						$message = array('sound' =>1,'message'=>$check_event->event_name.':'.'Event detail has been updated',
						'notifykey'=>'invite_invitation','data'=>'hello','title'=>'Mo-Tiv',
						'event_date'=>$get_event->event_date,'event_time'=>$get_event->event_time,'notify_count'=>$notify_count);
						$device_token=$event_owner->device_token;
						$rr=$this->send_android_notification($device_token,$check_event->event_name.':'.'Event detail has been updated','invite_invitation',$message);
					 
					}
			}		
			
			
			
			#event updation notification
			$sub_admins = DB::table('event_sub_admins')
					->leftJoin('users', 'users.id', '=', 'event_sub_admins.user_id')
					->where(['event_id'=>$get_event->event_id])
					->get();
			 //echo '<pre>';		
           //print_r($guests);					
			if((count($sub_admins)>0)){  
				foreach($sub_admins as $sub_admin){
				#save invitation notification  
					DB::table('notification_list')->insert(['user_id'=>$sub_admin->id,'other_user_id'=>$id,'message'=>$check_event->event_name.':'.'Event detail has been updated','notification_type'=>1,'timestamp'=>round(microtime(true) * 1000),'created_at' =>Date('Y-m-d H:i:s'),'updated_at' =>Date('Y-m-d H:i:s')]);
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
			$this->responseOk('Event Updated Successfully','');
		
		
	}
	
	
	/********************************************************************************************/
	public function checkEmailExist(Request $request){
		$validator = Validator::make($request->all(), [
			'email' => 'required|email',
		]);
		if($validator->fails()){
			$this->responseWithError($validator->errors()->first());
			exit;
		}
		$check_email = User::where('email',$request->input('email'))->first();
		if(empty($check_email)){
			return $this->responseOk('Email does not exist','');
		}else{
			return $this->responseWithError('Email already exist');
		}
	}
	/********************************************************************************************/
	public function createPost(Request $request){
		$id=$request->input('user_id');
		if(empty($id)){
			$user_data = $this->checkUserExist();
			$id=$user_data->id;
			if($user_data->blockStatus==1){
				$this->responseWithblock('You are blocked by admin');
			}
		}  
		
		$user_type = $request->input('user_type');
		if(!empty($user_type) && is_numeric($user_type)){
			// 1=>admin, 2=> user, 3 => Organizer
			// extra parameters - video
			if($user_type == 2){
				$validator = Validator::make($request->all(), [
					'text' =>'required',
					'event_id' =>'required|numeric|exists:event_schedule,id',
					'post_media_type' => 'required|numeric', // 1 => Image, 2 => video, 3 => none
				]);
			}else if($user_type == 3){
				$validator = Validator::make($request->all(), [
					'text' =>'required',
					'event_id' =>'required|numeric|exists:event_schedule,id',
					'post_media_type' => 'required|numeric', // 1 => Image, 2 => video, 3 => none
				]);
			}else{
				$this->responseWithError('user type field is not match');
			}
			
			if($validator->fails()){
				$this->responseWithError($validator->errors()->first());
				exit;
			}
			$insert_array = [
				'event_id' => $request->input('event_id'),
				'text' => $request->input('text'),
				'post_media_type' => $request->input('post_media_type'),
				'submit_by' => $user_type, //1=>admin, 2=> user, 3 => Organizer
				'status' => 1,
				'user_id' =>$id,
				'updated_at' => Date('Y-m-d H:i:s'),
				'created_at' => Date('Y-m-d H:i:s'),
			];
			
			if($request->input('post_media_type') == 2 && empty($request->file('image')) && empty($request->file('video'))){
				$this->responseWithError('image and vide both field is required');
			}
			
			if($request->input('post_media_type') == 1 && empty($request->file('image'))){
				$this->responseWithError('image field is required');
			}
			  
			if((!empty($request->file('image')) && $request->input('post_media_type') == 1) || (!empty($request->file('image')) && $request->input('post_media_type') == 2)){
					$image_name = str_random(20).'.png';
					$path = Storage::putFileAs('public/post_media', $request->file('image'), $image_name);
					// $insert_array['post_image_url'] = asset('storage/post_media/'.$image_name);
					// $insert_array['post_image_url']='http://54.206.13.240/sim_admin/storage/app/public/post_media/'.$image_name;
					// $insert_array['post_image_url']='http://175.176.186.116/webservices/sim_admin/storage/app/public/post_media/'.$image_name;
					$baseUrl = url('/');
					$baseUrl = str_replace('/public','/',$baseUrl);
					$insert_array['post_image_url'] = $baseUrl.'storage/app/'.$path;
			}else{
				$insert_array['post_image_url'] = '';
			}
			
			if(!empty($request->file('video')) && $request->input('post_media_type') == 2){
				$image_name = str_random(20).'.mp4';
				$path = Storage::putFileAs('public/post_media', $request->file('video'), $image_name);
				$insert_array['post_video_url'] = asset('storage/post_media/'.$image_name);
				$insert_array['post_video_url']='http://54.206.13.240/sim_admin/storage/app/public/post_media/'.$image_name;
			}else{
				$insert_array['post_video_url'] = '';
			}
			$insert_id = PostList::insertGetId($insert_array);
			
			
			$get_event=DB::table('event_schedule')->where(['id'=>$request->input('event_id')])->first();
			$get_event_name=DB::table('event_list')->where(['id'=>$get_event->event_id])->first();
			$get_friend_name=DB::table('users')->where(['id'=>$get_event->user_id])->first();
			if($get_event->user_id != $id){
				$get_reciever_name=DB::table('users')->where(['id'=>$id])->first();	
              /*save notification*/			
				DB::table('notification_list')->insert(['user_id'=>$get_event->user_id,'other_user_id'=>$id,'message'=>$get_reciever_name->name.' '.'post on your event','notification_type'=>2,'timestamp'=>round(microtime(true) * 1000),'created_at' =>Date('Y-m-d H:i:s'),'updated_at' =>Date('Y-m-d H:i:s')]);
				/******/
				$notify_count=$this->notification_count($get_event->user_id);
				
				if($get_friend_name->device_type == 'I'){
						$message = array('sound' =>1,'message'=>$get_reciever_name->name.' '.'post on your event',
						'notifykey'=>'event_post','data'=>'Mo-Tiv','title'=>$get_reciever_name->name,'event_id'=>(int)$request->input('event_id'),
						'event_date'=>$get_event->event_date,'event_time'=>$get_event->event_time,'event_name'=>$get_event_name->event_name,'notify_count'=>$notify_count);
						$device_token=$get_friend_name->device_token;
						$dd=$this->send_iphone_notification($device_token,$get_reciever_name->name.' '.'post on your event','event_post',$message);
				}   
				if($get_friend_name->device_type == 'A'){
					$message = array('sound' =>1,'message'=>$get_reciever_name->name.' '.'post on your event',
					'notifykey'=>'event_post','data'=>'hello','title'=>$get_reciever_name->name,'event_id'=>(int)$request->input('event_id'),
					'event_date'=>$get_event->event_date,'event_time'=>$get_event->event_time,'event_name'=>$get_event_name->event_name,'notify_count'=>$notify_count);
					$device_token=$get_friend_name->device_token;
					$this->send_android_notification($device_token,$get_reciever_name->name.' '.'post on your event','event_post',$message);
				}
				
			}
			$this->responseOk('Post submitted Successfully','');
		}else{
			$this->responseWithError('user type field is required');
		}
	}
	/********************************************************************************************/
	public function updateProfile(Request $request){
		$user_data = $this->checkUserExist();
		// print_r($user_data->toArray());exit;
		$validator = Validator::make($request->all(), [
			'name' =>'required',
			'email' =>'required|email',
			'phone_number' => 'nullable|digits_between:7,15',
		]);
		if($user_data->blockStatus==1){
			$this->responseWithblock('You are blocked by admin');
		}
		if($validator->fails()){
			$this->responseWithError($validator->errors()->first());
			exit;
		}
		$check_email = User::where('email',$request->input('email'))->where('id','!=',$user_data->id)->first();
		if(!empty($check_email)){
			$this->responseWithError('Email already exist.');
		}
		$check_user_email = User::where('email',$request->input('email'))->where('id','=',$user_data->id)->first();
		//print_r($check_user_email);die;
		if(empty($check_user_email)){
			$email_verification_token = str_random('40');
			$update_refresh_token = User::where(['id' =>$user_data->id])->update(['email_verification_token' =>$email_verification_token,
			'email' =>$request->input('email'),
			'status' =>2]
			);   
			$url = url('user/verify/'.$email_verification_token);
			$user_data = User::find($user_data->id);
			try{
				Mail::send('email_verify',['url' =>$url,'user_data' =>$user_data], 
				function ($m) use ($user_data) {
					$m->from(env('MAIL_FROM'), 'Mo-Tiv');
					$m->to($user_data->email,'App User');
					// $m->cc('deftsofttesting786@gmail.com','App User');
					$m->subject('Email verification link');
				});
			}catch(\Exception $ex){
				 $this->responseOk('Email verification link has been send to your new email id','');
			}
		}
		$insert_array = [
			'name' => $request->input('name'),
			'email' => $request->input('email'),
			'phone_number' => !empty($request->input('phone_number')) ? $request->input('phone_number'): '',
			'updated_at' => Date('Y-m-d H:i:s'),
		];
		if(!empty($request->file('image'))){
				$image_name = str_random(20).'.png';
				$path = Storage::putFileAs('public/user_images', $request->file('image'), $image_name);
				// $insert_array['image_url'] = url('storage/user_images/'.$image_name);
				// $insert_array['image_url']='http://54.206.13.240/sim_admin/storage/app/public/user_images/'.$image_name;
				// $insert_array['image_url']='http://175.176.186.116/webservices/sim_admin/storage/app/public/user_images/'.$image_name;
				$baseUrl = url('/');
				$baseUrl = str_replace('/public','/',$baseUrl);
				$insert_array['image_url'] = $baseUrl.'storage/app/'.$path;
		}
		$update_data = User::Where('id',$user_data->id)->update($insert_array);
		// if($update_data){
			$update_user_detail = User::Where('id',$user_data->id)->first();
			$this->responseOk('Profile Update Successfully.',$update_user_detail);
		// }else{
			// $this->responseWithError('Oops Something Wrong.');
		// }
	}
	/********************************************************************************************/
	public function contactUs(Request $request){
		$user_data = $this->checkUserExist();
		if($user_data->blockStatus==1){
			$this->responseWithblock('You are blocked by admin');
		}
		$validator = Validator::make($request->all(), [
			'text' =>'required',
		]);
		if($validator->fails()){
			$this->responseWithError($validator->errors()->first());
			exit;
		}
		$insert = ContactUs::insert(['user_id' => $user_data->id, 'text' =>$request->input('text'),'created_at'=>Date('Y-m-d H:i:s'),'updated_at'=>Date('Y-m-d H:i:s')]);
		//print $insert;die;
		if($insert){
			$text_message=$request->input('text');
			$insert_data['name'] = $user_data->name;
			$insert_data['email_verification_token'] = str_random('40');
			$url = url('user/verify/'.$insert_data['email_verification_token']);
			try{
				Mail::send('email_feedback',['url' =>$url,'text_message'=>$text_message,'user_data' => $insert_data], function ($m) use ($insert_data) {
					$m->from(env('MAIL_FROM'), 'MoTiv');
					$m->to('Motiv.uk@gmail.com','App User');
					// $m->cc('deftsofttesting786@gmail.com','App User');
					$m->subject('Feedback Email');
				});
			}catch(\Exception $ex){
				 //print $ex->getMessage();die;
				 $this->responseOk('Contact us request submitted Successfully','');
			}
			$this->responseOk('Contact us request submitted Successfully','');
		}else{
			$this->responseWithError('Oops Something wrong');
		}
		  
	}
	/********************************************************************************************/
	public function userNotificationList(Request $request){
		$user_data = $this->checkUserExist();
		if($user_data->blockStatus==1){
			$this->responseWithblock('You are blocked by admin');
		}
		$get_data = $user_data->notificationList;
		if(count($get_data) > 0){
			$this->responseOk('Notification List',$get_data);
		}else{
			$this->responseWithError('Notification list not found');
		}
	}
	/********************************************************************************************/
	 public function eachEventList(Request $request){
		$id=$request->input('user_id');
		if(empty($id)){
			$user_data = $this->checkUserExist();
			$id=$user_data->id;
			if($user_data->blockStatus==1){
				$this->responseWithblock('You are blocked by admin');
			}
		}
		
		$get_event_id=DB::table('event_schedule')->where(['id'=>$request->input('event_id')])->first();
		if(!empty($get_event_id)){
			$check_event_id=DB::table('event_list')->where(['id'=>$get_event_id->event_id,'status'=>2])->first();
			if(empty($check_event_id)){
			  $this->responseWithError('This event has been blocked/deleted by admin');
			}
		}else{
			$this->responseWithError('This event has been blocked/deleted by admin');
		}
		$eachEvent= EventList::
			leftJoin('event_schedule', 'event_schedule.event_id', '=', 'event_list.id')
			->where(['event_schedule.id' =>$request->input('event_id')])->first();
		//echo '<pre>';
		// print_r($eachEvent);die;
		$event_data = $eachEvent;
		$event_data->post_list_count = $eachEvent->postList()->count();
		$event_data->post_list = $eachEvent->postList()->take(10)->get();
		$event_data->guest_count=DB::table('invitations')->where(['sub_event_id'=>$eachEvent->id,'request_status'=>1])->count();
		$event_data->invited_users = DB::table('invitations')
					->leftJoin('users', 'users.id', '=', 'invitations.receiver_id')
					->where(['sub_event_id'=>$request->input('event_id')])
					->select('users.id','users.name','users.image_url')
					->get();
		$event_data->sub_admins = DB::table('event_sub_admins')
					->leftJoin('users', 'users.id', '=', 'event_sub_admins.user_id')
					->where(['event_id'=>$check_event_id->id])
					->select('users.id','users.name','users.image_url')
					->get();
        $check_edit_status = DB::table('event_sub_admins')
					->where(['event_id'=>$check_event_id->id,'user_id'=>$id])
					->first();
         if(!empty($check_edit_status)){
			 $eachEvent->edit_status=1;  #user can edit
		 }else{
			 $eachEvent->edit_status=2; #user can not edit event
		 }					
					
		$check_event=DB::table('invitations')->where(['sub_event_id'=>$eachEvent->id,'receiver_id'=>$id,'request_status'=>1])->first();
		if(!empty($check_event)){
			$eachEvent->attend_status=1;  #attend
		}else{
			if($id == $eachEvent->user_id){
				$eachEvent->attend_status=1;
			}else{
				$eachEvent->attend_status=2; #not attend
			}
		} 
		$invitation_id=DB::table('invitations')->where(['sub_event_id'=>$eachEvent->id,'receiver_id'=>$id,'request_status'=>2])->first();
		if(!empty($invitation_id)){
			$eachEvent->invitation_id=$invitation_id->id;
		}
		
		if(!empty($eachEvent->music_int_id)){
			$eachEvent->music_interest_id=$eachEvent->music_int_id;	
		}else{
			$eachEvent->music_interest_id='';	
		}
		
		if(!empty($eachEvent->public_int_id)){
			$eachEvent->public_interest_id=$eachEvent->public_int_id;	
		}else{
			$eachEvent->public_interest_id='';	
		}
		$eachEvent->ticket_available_count=$this->get_tickets($eachEvent->event_id,'ticket_count');
		$eachEvent->ticket_amount=$this->get_tickets($eachEvent->event_id,'price');
		if(!empty($event_data)){
			$this->responseOk('Each Event List',$event_data);
		}else{
			$this->responseWithError('Event list not found');
		}
	} 
	
	/********************************************************************************************/
	public function eventList_old(Request $request){
		$id=$request->input('user_id');   
		if(empty($id)){
			$user_data = $this->checkUserExist();
			$id=$user_data->id;
			if($user_data->blockStatus==1){
			  $this->responseWithblock('You are blocked by admin');
		    }
		}
		#status=>1 not aprove 2 approved
		$now = Carbon::now();
		 $date=$now->toDateString();
		//$subDay=$now->subDay();
		$event_time = $this->is_require($request->input('event_time'),'event_time');
		$end_dt=date('Y-m-d');
		$date_range=date('Y-m-d', strtotime("+2 week", strtotime($end_dt)));	
		if($event_time == 'current'){  
		$invitations=DB::table('invitations')
					->where(function ($query) use ($id){
						$query->where(['sender_id'=>$id]);
						$query->where(['request_status'=>1]);
					})->orWhere(function($query) use ($id){
						$query->where(['receiver_id'=>$id]);
						$query->where(['request_status'=>1]);
					})->select('sub_event_id')
					 ->distinct('sub_event_id')
					 ->pluck('sub_event_id');
		$get_event= EventList::
			leftJoin('event_schedule', 'event_schedule.event_id', '=', 'event_list.id')
			->where(['status' =>2])
			->whereNotIn('event_list.id',$this->get_event_block_list($id))
			->where(function ($query) use ($id,$date){
				$query->where(['event_list.user_id'=>$id]);
				$query->where(['submit_by'=>2]);
				$query->whereDate('event_schedule.event_date',$date);
			})->orWhere(function($query) use ($id,$date){
				$query->where(['submit_by'=>3,'status'=>2]);
				$query->whereDate('event_schedule.event_date',$date);
				$query->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id));  
			})->orWhere(function($query) use ($invitations,$date,$id){
				$query->whereIn('event_schedule.id',$invitations);
				$query->where(['submit_by'=>2]);
				$query->whereDate('event_schedule.event_date',$date);
				$query->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id));
			})->orderBy('event_schedule.event_time','ASC')
		      ->paginate(10);   
		//echo '<pre>';
		//print_r($get_event);die;	  
		}elseif($event_time == 'past'){
			$invitations=DB::table('invitations')
					->where(function ($query) use ($id){
						$query->where(['sender_id'=>$id]);
						$query->where(['request_status'=>1]);
					})->orWhere(function($query) use ($id){
						$query->where(['receiver_id'=>$id]);
						$query->where(['request_status'=>1]);
					})->select('sub_event_id')
					 ->distinct('sub_event_id')
					 ->pluck('sub_event_id');
			$get_event= EventList::where(['status' =>2])
			->whereNotIn('event_list.id',$this->get_event_block_list($id))
			->leftJoin('event_schedule', 'event_schedule.event_id', '=', 'event_list.id')
			->where(function ($query) use ($id,$date){
				$query->where(['event_list.user_id'=>$id]);
				$query->where(['submit_by'=>2]);
				$query->whereDate('event_schedule.event_date','<',$date);
			})->orWhere(function($query)use ($id,$date){
				$query->where(['submit_by'=>3,'status'=>2]);
				$query->whereDate('event_schedule.event_date','<',$date);
				$query->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id));
			})->orWhere(function($query) use ($invitations,$date,$id){
				$query->whereIn('event_schedule.id',$invitations);
				$query->where(['submit_by'=>2]);
				$query->whereDate('event_schedule.event_date','<',$date);
				$query->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id));
			})->orderBy('event_schedule.event_time','ASC')
		      ->paginate(10);
			  
		}elseif($event_time == 'upcoming'){
			$invitations=DB::table('invitations')
					->where(function ($query) use ($id){
						$query->where(['sender_id'=>$id]);
						$query->where(['request_status'=>1]);
					})->orWhere(function($query) use ($id){
						$query->where(['receiver_id'=>$id]);
						$query->where(['request_status'=>1]);
					})->select('sub_event_id')
					 ->distinct('sub_event_id')
					 ->pluck('sub_event_id');
			$get_event= EventList::where(['status' =>2])
			->whereNotIn('event_list.id',$this->get_event_block_list($id))
			->leftJoin('event_schedule', 'event_schedule.event_id', '=', 'event_list.id')
			->where(function ($query) use ($id,$date,$date_range){
				$query->where(['event_list.user_id'=>$id]);
				$query->where(['submit_by'=>2]);
				$query->whereDate('event_schedule.event_date','>',$date);
				$query->whereBetween('event_schedule.event_date',[$date,$date_range]);
				
			})->orWhere(function($query)use ($id,$date,$date_range){
				$query->where(['submit_by'=>3,'status'=>2]);
				$query->whereDate('event_schedule.event_date','>',$date);
				$query->whereBetween('event_schedule.event_date',[$date,$date_range]);
				$query->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id));
			})->orWhere(function($query) use ($invitations,$date,$date_range,$id){
				$query->whereIn('event_schedule.id',$invitations);
				$query->where(['submit_by'=>2]);
				$query->whereDate('event_schedule.event_date','>',$date);
				$query->whereBetween('event_schedule.event_date',[$date,$date_range]);
				$query->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id));
			})->orderBy('event_schedule.event_time','ASC')
		      ->paginate(10);
			  
		}
		foreach($get_event as $eachEvent){
			$eachEvent->postList->take(10);
			$eachEvent->post_list_count = $eachEvent->postList->count();
			
			$eachEvent->guest_count=DB::table('invitations')->where(['event_id'=>$eachEvent->event_id,'sub_event_id'=>$eachEvent->id,'request_status'=>1])->count();
			$event_music_interest_list=DB::table('event_music_interest_list')->where(['event_id'=>$eachEvent->event_id])->first();
			  
			$names=array();
            $music_interests=explode(",",$eachEvent->music_int_id);
			if(count($music_interests)>0){
			 	foreach	($music_interests as $music_interests){
						$get_music_interest = DB::table('music_interest')->where(['id'=>$music_interests])->first();
						if(!empty($get_music_interest)){
							$names[]=$get_music_interest->name;
						}
				}	
				if(empty($names)){
					$names=array();
				} 
			    $eachEvent->music_interest_id=implode(",",$names);
			}			
			        
			$event_public_interest_list=DB::table('event_public_interest_list')->where(['event_id'=>$eachEvent->event_id])->first();
			if(!empty($event_public_interest_list)){
				$public_interest=DB::table('public_interest')->where(['id'=>$event_public_interest_list->public_interest_id])->first();
				$eachEvent->public_interest_id=$public_interest->name;
			}else{    
				$eachEvent->public_interest_id="";
			}     
			$check_event=DB::table('invitations')->where(['event_id'=>$eachEvent->event_id,'sub_event_id'=>$eachEvent->id,'receiver_id'=>$id,/* 'request_status'=>1 */])->first();
			if(!empty($check_event)){
					$eachEvent->attend_status=1;
			}else{      
				if($id == $eachEvent->user_id){
					$eachEvent->attend_status=1;
				}else{
					$eachEvent->attend_status=2;
				}
			}		     
		}       
		if(count($get_event) > 0){
			$this->responseOk('Event List',$get_event);
		}else{
			$this->responseWithError('No more event found');
		}
	}
	
	/*28-5-2018*/
	
	public function eventList_02_06_2018(Request $request){
		$id=$request->input('user_id');   
		if(empty($id)){
			$user_data = $this->checkUserExist();
			$id=$user_data->id;
			if($user_data->blockStatus==1){
			  $this->responseWithblock('You are blocked by admin');
		    }
		}
		#status=>1 not aprove 2 approved
		$now = Carbon::now();
		 $date=$now->toDateString();
		//$subDay=$now->subDay();
		$event_time = $this->is_require($request->input('event_time'),'event_time');
		$end_dt=date('Y-m-d');
		$time=date('H:i:s');
		//print $time;
		$date_range=date('Y-m-d', strtotime("+2 week", strtotime($end_dt)));	
		if($event_time == 'current'){  
		$invitations=DB::table('invitations')
					->where(function ($query) use ($id){
						$query->where(['sender_id'=>$id]);
						$query->where(['request_status'=>1]);
					})->orWhere(function($query) use ($id){
						$query->where(['receiver_id'=>$id]);
						$query->where(['request_status'=>1]);
					})->select('sub_event_id')
					 ->distinct('sub_event_id')
					 ->pluck('sub_event_id');
		$get_event= EventList::
			leftJoin('event_schedule', 'event_schedule.event_id', '=', 'event_list.id')
			->where(['status' =>2])
			->whereNotIn('event_list.id',$this->get_event_block_list($id))
			->where(function ($query) use ($id,$date,$time){
				$query->where(['event_list.user_id'=>$id]);
				$query->where(['submit_by'=>2]);
				$query->whereDate('event_schedule.event_date',$date);
				$query->whereTime('event_schedule.end_time','>=',$time);
			})->orWhere(function($query) use ($id,$date,$time){
				$query->where(['submit_by'=>3,'status'=>2]);
				$query->whereDate('event_schedule.event_date',$date);
				$query->whereTime('event_schedule.end_time','>=',$time);
				$query->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id));  
			})->orWhere(function($query) use ($invitations,$date,$time,$id){
				$query->whereIn('event_schedule.id',$invitations);
				$query->where(['submit_by'=>2]);
				$query->whereDate('event_schedule.event_date',$date);
				$query->whereTime('event_schedule.end_time','>=',$time);
				$query->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id));
			})->orderBy('event_schedule.event_time','ASC')
		      ->paginate(10);   
		//echo '<pre>';
		//print_r($get_event);die;	  
		}elseif($event_time == 'past'){
			$invitations=DB::table('invitations')
					->where(function ($query) use ($id){
						$query->where(['sender_id'=>$id]);
						$query->where(['request_status'=>1]);
					})->orWhere(function($query) use ($id){
						$query->where(['receiver_id'=>$id]);
						$query->where(['request_status'=>1]);
					})->select('sub_event_id')
					 ->distinct('sub_event_id')
					 ->pluck('sub_event_id');
			$get_event= EventList::where(['status' =>2])
			->whereNotIn('event_list.id',$this->get_event_block_list($id))
			->leftJoin('event_schedule', 'event_schedule.event_id', '=', 'event_list.id')
			->where(function ($query) use ($id,$date){
				$query->where(['event_list.user_id'=>$id]);                  //event submited by user
				$query->where(['submit_by'=>2]);
				$query->whereDate('event_schedule.event_date','<',$date);
			})->orwhere(function ($query) use ($id,$date,$time){
				$query->where(['event_list.user_id'=>$id]);                  //event submited by user same date but end time passed
				$query->where(['submit_by'=>2]);
				$query->whereDate('event_schedule.event_date','=',$date);
				$query->whereTime('event_schedule.end_time','<',$time);
			})->orWhere(function($query)use ($id,$date){
				$query->where(['submit_by'=>3,'status'=>2]);               //event submited by organizer
				$query->whereDate('event_schedule.event_date','<',$date);
				$query->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id));
			})->orWhere(function($query)use ($id,$date,$time){
				$query->where(['submit_by'=>3,'status'=>2]);
				$query->whereDate('event_schedule.event_date','=',$date);      //event submited by organizer same date but end time passed
				$query->whereTime('event_schedule.end_time','<',$time);
				$query->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id));
			})->orWhere(function($query) use ($invitations,$date,$id){
				$query->whereIn('event_schedule.id',$invitations);
				$query->where(['submit_by'=>2]);                         //event submited by other but invited 
				$query->whereDate('event_schedule.event_date','<',$date);
				$query->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id));
			})->orWhere(function($query) use ($invitations,$date,$id,$time){
				$query->whereIn('event_schedule.id',$invitations);                   
				$query->where(['submit_by'=>2]);                             //event submited by other but invited same date but end time passed
				$query->whereDate('event_schedule.event_date','=',$date);
				$query->whereTime('event_schedule.end_time','<',$time);
				$query->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id));
			})->orderBy('event_schedule.event_time','ASC')
		      ->paginate(10);
			  
		}elseif($event_time == 'upcoming'){
			$invitations=DB::table('invitations')
					->where(function ($query) use ($id){
						$query->where(['sender_id'=>$id]);
						$query->where(['request_status'=>1]);
					})->orWhere(function($query) use ($id){
						$query->where(['receiver_id'=>$id]);
						$query->where(['request_status'=>1]);
					})->select('sub_event_id')
					 ->distinct('sub_event_id')
					 ->pluck('sub_event_id');
			$get_event= EventList::where(['status' =>2])
			->whereNotIn('event_list.id',$this->get_event_block_list($id))
			->leftJoin('event_schedule', 'event_schedule.event_id', '=', 'event_list.id')
			->where(function ($query) use ($id,$date,$date_range){
				$query->where(['event_list.user_id'=>$id]);
				$query->where(['submit_by'=>2]);
				$query->whereDate('event_schedule.event_date','>',$date);
				$query->whereBetween('event_schedule.event_date',[$date,$date_range]);
				
			})->orWhere(function($query)use ($id,$date,$date_range){
				$query->where(['submit_by'=>3,'status'=>2]);
				$query->whereDate('event_schedule.event_date','>',$date);
				$query->whereBetween('event_schedule.event_date',[$date,$date_range]);
				$query->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id));
			})->orWhere(function($query) use ($invitations,$date,$date_range,$id){
				$query->whereIn('event_schedule.id',$invitations);
				$query->where(['submit_by'=>2]);
				$query->whereDate('event_schedule.event_date','>',$date);
				$query->whereBetween('event_schedule.event_date',[$date,$date_range]);
				$query->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id));
			})->orderBy('event_schedule.event_time','ASC')
		      ->paginate(10);
			  
		}
		foreach($get_event as $eachEvent){
			$eachEvent->postList->take(10);
			$eachEvent->post_list_count = $eachEvent->postList->count();
			
			$eachEvent->guest_count=DB::table('invitations')->where(['event_id'=>$eachEvent->event_id,'sub_event_id'=>$eachEvent->id,'request_status'=>1])->count();
			$event_music_interest_list=DB::table('event_music_interest_list')->where(['event_id'=>$eachEvent->event_id])->first();
			  
			$names=array();
            $music_interests=explode(",",$eachEvent->music_int_id);
			if(count($music_interests)>0){
			 	foreach	($music_interests as $music_interests){
						$get_music_interest = DB::table('music_interest')->where(['id'=>$music_interests])->first();
						if(!empty($get_music_interest)){
							$names[]=$get_music_interest->name;
						}
				}	
				if(empty($names)){
					$names=array();
				} 
			    $eachEvent->music_interest_id=implode(",",$names);
			}			
			        
			$event_public_interest_list=DB::table('event_public_interest_list')->where(['event_id'=>$eachEvent->event_id])->first();
			if(!empty($event_public_interest_list)){
				$public_interest=DB::table('public_interest')->where(['id'=>$event_public_interest_list->public_interest_id])->first();
				$eachEvent->public_interest_id=$public_interest->name;
			}else{    
				$eachEvent->public_interest_id="";
			}     
			$check_event=DB::table('invitations')->where(['event_id'=>$eachEvent->event_id,'sub_event_id'=>$eachEvent->id,'receiver_id'=>$id,/* 'request_status'=>1 */])->first();
			if(!empty($check_event)){
					$eachEvent->attend_status=1;
			}else{      
				if($id == $eachEvent->user_id){
					$eachEvent->attend_status=1;
				}else{
					$eachEvent->attend_status=2;
				}
			}		     
		}       
		if(count($get_event) > 0){
			$this->responseOk('Event List',$get_event);
		}else{
			$this->responseWithError('No more event found');
		}
	}
	/********************************************************************************************/
	
	public function eventList(Request $request){
		$id=$request->input('user_id');   
		if(empty($id)){
			$user_data = $this->checkUserExist();
			$id=$user_data->id;
			if($user_data->blockStatus==1){
			  $this->responseWithblock('You are blocked by admin');
		    }
		}
		#status=>1 not aprove 2 approved
		$now = Carbon::now();
		$date=$now->toDateString();
		$match_date=date('Y-m-d H:i:s');
		//$subDay=$now->subDay();
		$event_time = $this->is_require($request->input('event_time'),'event_time');
		$end_dt=date('Y-m-d');
		$time=date('H:i:s');
		//print $time;
		$date_range=date('Y-m-d', strtotime("+2 week", strtotime($end_dt)));	
		$match_date_range=date('Y-m-d', strtotime("+2 week", strtotime($end_dt)));	
		$invitations=DB::table('invitations')
					->where(function ($query) use ($id){
						$query->where(['sender_id'=>$id]);
						$query->where(['request_status'=>1]);
					})->orWhere(function($query) use ($id){
						$query->where(['receiver_id'=>$id]);
						$query->where(['request_status'=>1]);
					})->select('sub_event_id')
					 ->distinct('sub_event_id')
					 ->pluck('sub_event_id');
		
		if($event_time == 'current'){  
			$get_event= EventList::
			leftJoin('event_schedule', 'event_schedule.event_id', '=', 'event_list.id')
			->where(['status' =>2])
			->whereNotIn('event_list.id',$this->get_event_block_list($id))
			->where(function ($query) use ($id,$match_date,$time,$date){
				$query->where(['event_list.user_id'=>$id]);
				$query->where(['submit_by'=>2]);
				$query->where('event_schedule.event_start_date_time','<',$match_date);
				$query->where('event_schedule.event_end_date_time','>=',$match_date);
			})->orWhere(function($query) use ($id,$date,$time,$match_date){
				$query->where(['submit_by'=>3,'status'=>2]);
				$query->whereDate('event_schedule.event_date',$date);
				$query->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id));
				$query->whereNotIn('event_schedule.user_id',$this->get_user_block_by_admin($id));
				$query->where('event_schedule.event_start_date_time','<',$match_date);
				$query->where('event_schedule.event_end_date_time','>=',$match_date);
			})->orWhere(function($query) use ($invitations,$date,$time,$id,$match_date){
				$query->whereIn('event_schedule.id',$invitations);
				$query->where(['submit_by'=>2]);  
				$query->where('event_schedule.event_start_date_time','<',$match_date);
				$query->where('event_schedule.event_end_date_time','>=',$match_date);
				$query->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id));
				$query->whereNotIn('event_schedule.user_id',$this->get_user_block_by_admin($id));
			})->orderBy('event_schedule.event_start_date_time','ASC')
		      ->paginate(10);   
		//echo '<pre>';
		//print_r($get_event);die;	  
		}elseif($event_time == 'past'){
			$get_event= EventList::where(['status' =>2])
			->whereNotIn('event_list.id',$this->get_event_block_list($id))
			->leftJoin('event_schedule', 'event_schedule.event_id', '=', 'event_list.id')
			->where(function ($query) use ($id,$match_date,$date){
				$query->where(['event_list.user_id'=>$id]);                  //event submited by user
				$query->where(['submit_by'=>2]);
				$query->where('event_schedule.event_end_date_time','<',$match_date);
			})->orwhere(function ($query) use ($id,$match_date,$time,$date){
				$query->where(['event_list.user_id'=>$id]);                  //event submited by user same date but end time passed
				$query->where(['submit_by'=>2]);
				$query->where('event_schedule.event_end_date_time','<',$match_date);
			})->orWhere(function($query)use ($id,$match_date,$date){
				$query->where(['submit_by'=>3,'status'=>2]);               //event submited by organizer
				$query->where('event_schedule.event_end_date_time','<',$match_date);
				$query->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id));
				$query->whereNotIn('event_schedule.user_id',$this->get_user_block_by_admin($id));
			})->orWhere(function($query)use ($id,$match_date,$time,$date){
				$query->where(['submit_by'=>3,'status'=>2]);
				$query->where('event_schedule.event_end_date_time','<',$match_date);     //event submited by organizer same date but end time passed
				$query->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id));
				$query->whereNotIn('event_schedule.user_id',$this->get_user_block_by_admin($id));
			})->orWhere(function($query) use ($invitations,$match_date,$id,$date){
				$query->whereIn('event_schedule.id',$invitations);
				$query->where(['submit_by'=>2]);                         //event submited by other but invited 
				$query->where('event_schedule.event_end_date_time','<',$match_date);
				$query->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id));
				$query->whereNotIn('event_schedule.user_id',$this->get_user_block_by_admin($id));
			})->orWhere(function($query) use ($invitations,$match_date,$id,$time,$date){
				$query->whereIn('event_schedule.id',$invitations);                   
				$query->where(['submit_by'=>2]);                             //event submited by other but invited same date but end time passed
				$query->where('event_schedule.event_end_date_time','<',$match_date);
				$query->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id));
				$query->whereNotIn('event_schedule.user_id',$this->get_user_block_by_admin($id));
			})->orderBy('event_schedule.event_start_date_time','ASC')
		      ->paginate(10);
			  
		}elseif($event_time == 'upcoming'){
			$get_event= EventList::where(['status' =>2])
			->whereNotIn('event_list.id',$this->get_event_block_list($id))
			->leftJoin('event_schedule', 'event_schedule.event_id', '=', 'event_list.id')
			->where(function ($query) use ($id,$date,$date_range,$match_date_range,$match_date){
				$query->where(['event_list.user_id'=>$id]);
				$query->where(['submit_by'=>2]);
				$query->where('event_schedule.event_start_date_time','>',$match_date);
				$query->whereBetween('event_schedule.event_start_date_time',[$match_date,$match_date_range]);
			})->orWhere(function($query)use ($id,$date,$date_range,$match_date_range,$match_date){
				$query->where(['submit_by'=>3,'status'=>2]);
				$query->where('event_schedule.event_start_date_time','>',$match_date);
				$query->whereBetween('event_schedule.event_start_date_time',[$match_date,$match_date_range]);
				$query->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id));
				$query->whereNotIn('event_schedule.user_id',$this->get_user_block_by_admin($id));
			})->orWhere(function($query) use ($invitations,$date,$date_range,$id,$match_date_range,$match_date){
				$query->whereIn('event_schedule.id',$invitations);
				$query->where(['submit_by'=>2]);
				$query->where('event_schedule.event_start_date_time','>',$match_date);
				$query->whereBetween('event_schedule.event_start_date_time',[$match_date,$match_date_range]);
				$query->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id));
				$query->whereNotIn('event_schedule.user_id',$this->get_user_block_by_admin($id));
			})->orderBy('event_schedule.event_start_date_time','ASC')
		      ->paginate(10);
			  
		}elseif($event_time == 'invitations'){
			$get_event= EventList::where(['status' =>2])
			->whereNotIn('event_list.id',$this->get_event_block_list($id))
			->leftJoin('event_schedule', 'event_schedule.event_id', '=', 'event_list.id')
			->where(function($query) use ($invitations,$match_date,$id,$date){
				$query->whereIn('event_schedule.id',$invitations);
				$query->where(['submit_by'=>2]);                         //event submited by other but invited 
				$query->where('event_schedule.event_end_date_time','<',$match_date);
				$query->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id));
				$query->whereNotIn('event_schedule.user_id',$this->get_user_block_by_admin($id));
			})->orWhere(function($query) use ($invitations,$match_date,$id,$time,$date){
				$query->whereIn('event_schedule.id',$invitations);                   
				$query->where(['submit_by'=>2]);                             //event submited by other but invited same date but end time passed
				$query->where('event_schedule.event_end_date_time','<',$match_date);
				$query->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id));
				$query->whereNotIn('event_schedule.user_id',$this->get_user_block_by_admin($id));
			})->orderBy('event_schedule.event_start_date_time','ASC')
		      ->paginate(10);
		
		}elseif($event_time == 'favourite'){
			$favourites= FavouriteEvent::where(['user_id'=>$id])->select('sub_event_id')
						 ->distinct('sub_event_id')
						 ->pluck('sub_event_id');	
			$get_event= EventList::where(['status' =>2])
			->whereNotIn('event_list.id',$this->get_event_block_list($id))
			->leftJoin('event_schedule', 'event_schedule.event_id', '=', 'event_list.id')
			->where(function($query) use ($favourites,$id){
				$query->whereIn('event_schedule.id',$favourites);
				$query->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id));
				$query->whereNotIn('event_schedule.user_id',$this->get_user_block_by_admin($id));
			})->orderBy('event_schedule.event_start_date_time','ASC')
		      ->paginate(10);
                 
		}else{
			 $this->responseWithblock('Please enter a valid keyword');
		}

		foreach($get_event as $eachEvent){
			$eachEvent->postList->take(10);
			$eachEvent->post_list_count = $eachEvent->postList->count();
			
			$eachEvent->guest_count=DB::table('invitations')->where(['event_id'=>$eachEvent->event_id,'sub_event_id'=>$eachEvent->id,'request_status'=>1])->count();
			$event_music_interest_list=DB::table('event_music_interest_list')->where(['event_id'=>$eachEvent->event_id])->first();
			  
			$names=array();
            $music_interests=explode(",",$eachEvent->music_int_id);
			if(count($music_interests)>0){
			 	foreach	($music_interests as $music_interests){
						$get_music_interest = DB::table('music_interest')->where(['id'=>$music_interests])->first();
						if(!empty($get_music_interest)){
							$names[]=$get_music_interest->name;
						}
				}	
				if(empty($names)){
					$names=array();
				} 
			    $eachEvent->music_interest_id=implode(",",$names);
			}			
			        
			$event_public_interest_list=DB::table('event_public_interest_list')->where(['event_id'=>$eachEvent->event_id])->first();
			if(!empty($event_public_interest_list)){
				$public_interest=DB::table('public_interest')->where(['id'=>$event_public_interest_list->public_interest_id])->first();
				$eachEvent->public_interest_id=$public_interest->name;
			}else{    
				$eachEvent->public_interest_id="";
			}     
			$check_event=DB::table('invitations')->where(['event_id'=>$eachEvent->event_id,'sub_event_id'=>$eachEvent->id,'receiver_id'=>$id,/* 'request_status'=>1 */])->first();
			if(!empty($check_event)){
					$eachEvent->attend_status=1;
			}else{      
				if($id == $eachEvent->user_id){
					$eachEvent->attend_status=1;
				}else{
					$eachEvent->attend_status=2;
				}
			}
			
			$eachEvent->ticket_available_count=$this->get_tickets($eachEvent->event_id,'ticket_count');
			$eachEvent->ticket_price=$this->get_tickets($eachEvent->event_id,'price');

		}       
		if(count($get_event) > 0){
			$this->responseOk('Event List',$get_event);
		}else{
			$this->responseWithError('No more event found');
		}
	}

	public function addFavourite(Request $request){
		//die('ddd');
        $user_data = $this->checkUserExist();
		$id=$user_data->id;
		if($user_data->blockStatus==1){
		  return $this->responseWithblock('You are blocked by admin');
	    }
        $validator = Validator::make($request->all(), [
        	'event_id'=>'required|exists:event_schedule,id'
        ]);  
        if($validator->fails()){
			return $this->responseWithError($validator->errors()->first());
		}
        $get_event=DB::table('event_schedule')->where(['id'=>$request->event_id])->first();
        $save_data['user_id']=$id;
        $save_data['event_id']=$get_event->event_id;
        $save_data['sub_event_id']=$request->event_id;
        $save_data['created_at']=Date('Y-m-d H:i:s');
        FavouriteEvent::updateOrCreate(['user_id'=>$id,'sub_event_id'=>$request->event_id],$save_data);
   		return $this->responseOk('Event added to favourite successfully','');
    }

	public function eventListFun($user_id){
		$id=$user_id;   
		#status=>1 not aprove 2 approved
		$now = Carbon::now();
		 $date=$now->toDateString();
		 $match_date=date('Y-m-d H:i:s');
		//$subDay=$now->subDay();
		//$event_time = $this->is_require($request->input('event_time'),'event_time');
		$end_dt=date('Y-m-d');
		$time=date('H:i:s');
		//print $time;
		$date_range=date('Y-m-d', strtotime("+2 week", strtotime($end_dt)));	
		$match_date_range=date('Y-m-d', strtotime("+2 week", strtotime($end_dt)));	
		
			$invitations=DB::table('invitations')
						->where(function ($query) use ($id){
							$query->where(['sender_id'=>$id]);
							$query->where(['request_status'=>1]);
						})->orWhere(function($query) use ($id){
							$query->where(['receiver_id'=>$id]);
							$query->where(['request_status'=>1]);
						})->select('sub_event_id')
						 ->distinct('sub_event_id')
						 ->pluck('sub_event_id');
			$get_event= EventList::
				leftJoin('event_schedule', 'event_schedule.event_id', '=', 'event_list.id')
				->where(['status' =>2])
				->whereNotIn('event_list.id',$this->get_event_block_list($id))
				->where(function ($query) use ($id,$match_date,$time,$date){
					$query->where(['event_list.user_id'=>$id]);
					$query->where(['submit_by'=>2]);
					//$query->where('event_schedule.event_start_date_time','<',$match_date);
					//$query->where('event_schedule.event_end_date_time','>=',$match_date);
				})->orWhere(function($query) use ($id,$date,$time,$match_date){
					$query->where(['submit_by'=>3,'status'=>2]);
					$query->whereDate('event_schedule.event_date',$date);
					
					$query->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id));
					$query->whereNotIn('event_schedule.user_id',$this->get_user_block_by_admin($id));
					//$query->where('event_schedule.event_start_date_time','<',$match_date);
					//$query->where('event_schedule.event_end_date_time','>=',$match_date);
				})->orWhere(function($query) use ($invitations,$date,$time,$id,$match_date){
					$query->whereIn('event_schedule.id',$invitations);
					$query->where(['submit_by'=>2]);  
				//	$query->where('event_schedule.event_start_date_time','<',$match_date);
				///	$query->where('event_schedule.event_end_date_time','>=',$match_date);
					$query->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id));
					$query->whereNotIn('event_schedule.user_id',$this->get_user_block_by_admin($id));
				})->orderBy('event_schedule.event_start_date_time','ASC')
			      ->paginate(10);   
			
		foreach($get_event as $eachEvent){
			$eachEvent->postList->take(10);
			$eachEvent->post_list_count = $eachEvent->postList->count();
			
			$eachEvent->guest_count=DB::table('invitations')->where(['event_id'=>$eachEvent->event_id,'sub_event_id'=>$eachEvent->id,'request_status'=>1])->count();
			$event_music_interest_list=DB::table('event_music_interest_list')->where(['event_id'=>$eachEvent->event_id])->first();
			  
			$names=array();
            $music_interests=explode(",",$eachEvent->music_int_id);
			if(count($music_interests)>0){
			 	foreach	($music_interests as $music_interests){
						$get_music_interest = DB::table('music_interest')->where(['id'=>$music_interests])->first();
						if(!empty($get_music_interest)){
							$names[]=$get_music_interest->name;
						}
				}	
				if(empty($names)){
					$names=array();
				} 
			    $eachEvent->music_interest_id=implode(",",$names);
			}			
			        
			$event_public_interest_list=DB::table('event_public_interest_list')->where(['event_id'=>$eachEvent->event_id])->first();
			if(!empty($event_public_interest_list)){
				$public_interest=DB::table('public_interest')->where(['id'=>$event_public_interest_list->public_interest_id])->first();
				$eachEvent->public_interest_id=$public_interest->name;
			}else{    
				$eachEvent->public_interest_id="";
			}     
			$check_event=DB::table('invitations')->where(['event_id'=>$eachEvent->event_id,'sub_event_id'=>$eachEvent->id,'receiver_id'=>$id,/* 'request_status'=>1 */])->first();
			if(!empty($check_event)){
					$eachEvent->attend_status=1;
			}else{      
				if($id == $eachEvent->user_id){
					$eachEvent->attend_status=1;
				}else{
					$eachEvent->attend_status=2;
				}
			}		     
		}       
		return $get_event;
		
	}
	
	public function postList(Request $request){
	     //$user_data = $this->checkUserExist();
		$id=$request->input('user_id');
		if(empty($id)){  
			$user_data = $this->checkUserExist();
			$id=$user_data->id;
			if($user_data->blockStatus==1){
				$this->responseWithblock('You are blocked by admin');
			}
		}
		$get_event=DB::table('event_schedule')->where(['id'=>$request->input('event_id')])->first();
		if(!empty($get_event)){
			$check_event_id=DB::table('event_list')->where(['id'=>$get_event->event_id,'status'=>2])->first();
			if(empty($check_event_id)){
			  $this->responseWithError('This event has been blocked/deleted by admin');
			}
		}else{
			$this->responseWithError('This event has been blocked/deleted by admin');
		}
		$get_post = PostList::where(['status' =>1,'event_id' =>$request->input('event_id')])->orderBy('id', 'desc')->paginate(10);
		//print_r($get_post);die;
		foreach($get_post as $eachPost){
            $eachPost->user_detail=User::where(['id'=>$eachPost->user_id])->select('image_url','name','id')->first();
		   
		    $eachPost->comment_count=CommentList::where(['post_id'=>$eachPost->id])->count();
			$eachPost->like_count=Like::where(['post_id'=>$eachPost->id,'like_status'=>1])->count();
			$check_like=Like::where(['post_id'=>$eachPost->id,'user_id'=>$id])->first();
			if(!empty($check_like)){
				$eachPost->like_status=(int)$check_like->like_status;
			}else{
				$eachPost->like_status=2;
			}
			//$data[]=$eachPost;
		}
		
		if(count($get_post) > 0){
			$this->responseOk('Post List',$get_post);
		}else{
			$this->responseWithError('No more post found');
		}
	}
	/********************************************************************************************/
	public function SearchFriends(Request $request){
		$id=$request->input('user_id');
		if(empty($id)){
			$user_data = $this->checkUserExist();
			$id=$user_data->id;
		}
		if($user_data->blockStatus==1){
			$this->responseWithblock('You are blocked by admin');
		}		
		$name=$request->input('name');
		if(!empty($name)){
			$find_users = User::where('name','LIKE',"%{$name}%")->select('id','name','image_url','email')
								->where('role','=',2)->where('id','!=',$id)  
								->whereNotIn('id',$this->get_user_block_list($id))  
								->paginate(10);
			foreach($find_users as $each_friend){
				$friend_id=$each_friend->id;
				$check_request=DB::table('friends')->where(function ($query) use ($id,$friend_id){
													$query->where(['sender_id'=>$id])
													->where(['receiver_id'=>$friend_id]);
													//->where(['request_status'=>1]);
											})->orWhere(function($query) use ($id,$friend_id){
													$query->where(['sender_id'=>$friend_id])
													->where(['receiver_id'=>$id]);
												//->where(['request_status'=>1]);													
											})->first();
				if(!empty($check_request)){
					if($check_request->request_status ==1){
						$each_friend->friend_status=1;
					}elseif($check_request->request_status ==2){
						$each_friend->friend_status=2;
						$each_friend->request_id=$check_request->id;
					}
				}else{
					if($id ==$friend_id){
						$each_friend->friend_status=1;
					}else{
						$each_friend->friend_status=3;
					}
				}
			}		
		}else{
			$find_users = User::select('id','name','image_url','email')->where('role','=',2)
												->where('id','!=',$id)
												->whereNotIn('id',$this->get_user_block_list($id))  
												->paginate(10);
			foreach($find_users as $each_friend){
				$friend_id=$each_friend->id;
				$check_request=DB::table('friends')->where(function ($query) use ($id,$friend_id){
													$query->where(['sender_id'=>$id])
													->where(['receiver_id'=>$friend_id]);
													//->where(['request_status'=>1]);
												})->orWhere(function($query) use ($id,$friend_id){
													$query->where(['sender_id'=>$friend_id])
													->where(['receiver_id'=>$id]);
													//->where(['request_status'=>1]);													
												})->first();
				if(!empty($check_request)){
					if($check_request->request_status ==1){
						$each_friend->friend_status=1;
					}elseif($check_request->request_status ==2){
						$each_friend->friend_status=2;
						$each_friend->request_id=$check_request->id;
					}
				}else{
					if($id ==$friend_id){
						$each_friend->friend_status=1;
					}else{
						$each_friend->friend_status=3;
					}
				}
            }			
		}			
		if(count($find_users) > 0){
			//$result=['result'=>'Success','message'=>'Friend List','data'=>$find_users];
			//return response()->json($result);
			$this->responseOk('Friend List',$find_users);
		}else{
			$this->responseWithError('No friend found');
		}
	}
	/********************************************************************************************/
	public function friendList(Request $request){
		$id=$request->input('user_id');
		if(empty($id)){
			$user_data = $this->checkUserExist();
			$id=$user_data->id;
			if($user_data->blockStatus==1){
				$this->responseWithblock('You are blocked by admin');
			}
		}
		$get_friend	=Friend::where(function ($query) use ($id){
									$query->where(['sender_id'=>$id])
									->where(['request_status'=>1]);
								})->orWhere(function($query) use ($id){
									$query->where(['receiver_id'=>$id])
									->where(['request_status'=>1]);													
								})->select('id as request_id','sender_id','receiver_id')
								  ->get();
		foreach($get_friend as $eachGet_friend){
			if($eachGet_friend->sender_id == $id){
				$eachGet_friend->senderDetail=User::where('id','=',$eachGet_friend->receiver_id)->select('id','name','image_url','email')->first();
			}else{
				$eachGet_friend->senderDetail=User::where('id','=',$eachGet_friend->sender_id)->select('id','name','image_url','email')->first();
			}
		}
		if(count($get_friend) > 0){
			$result=['result'=>'Success','message'=>'Friend List','data'=>$get_friend];
			return response()->json($result);
		}else{
			$this->responseWithError('You don’t have any Friend');
		}  
	}
	/********************************************************************************************/
	public function sendRequest(Request $request){
		$id=$request->input('user_id');
		if(empty($id)){
			$user_data = $this->checkUserExist();
			$id=$user_data->id;
		}
		if($user_data->blockStatus==1){
			$this->responseWithblock('You are blocked by admin');
		}
		$receiver_id=$request->input('receiver_id');
		$validator = Validator::make($request->all(),[
			'receiver_id' =>'required|numeric|exists:users,id',
			]);
		if($validator->fails()){
			$this->responseWithError($validator->errors()->first());
			exit;
		}
		if($id == $request->input('receiver_id')){
			$this->responseWithError('You cant send request yourself');
			exit;
		}
		$get_friend_name=User::where(['id'=>$request->input('receiver_id')])->first();
		if($id == $receiver_id){
			$this->responseWithError('You cant send request yourself');
		}
		$check_friend	=Friend::where(function ($query) use ($id,$receiver_id){
												$query->where(['sender_id'=>$id])
												->where(['receiver_id'=>$receiver_id]);
											})->orWhere(function($query) use ($id,$receiver_id){
												$query->where(['sender_id'=>$receiver_id])
												->where(['receiver_id'=>$id]);													
											})->first();
		
		if(empty($check_friend)){
		$request_id = FriendList::insertGetId(['sender_id'=>$id,'receiver_id'=>$request->input('receiver_id'),'timestamp'=>round(microtime(true) * 1000)]);
		/*save requset notification*/
		DB::table('notification_list')->insert(['user_id'=>$request->input('receiver_id'),'other_user_id'=>$id,'message'=>'you have received friend request','notification_type'=>1,'timestamp'=>round(microtime(true) * 1000),'created_at' =>Date('Y-m-d H:i:s'),'updated_at' =>Date('Y-m-d H:i:s')]);
		/***************************/
		}else{
			$this->responseWithError('Friend request already sent to this user');
			exit;
		}
		$notify_count=$this->notification_count($get_friend_name->id);
		if($get_friend_name->device_type == 'I'){
					$message = array('sound' =>1,'message'=>'you have received friend request',
					'notifykey'=>'friend_request','data'=>'Mo-Tiv','title'=>'Mo-Tiv','friend_id'=>$id,'request_id'=>$request_id,'notify_count'=>$notify_count);
					$device_token=$get_friend_name->device_token;
					$dd=$this->send_iphone_notification($device_token,'you have received friend request','friend_request',$message);
		}   
		if($get_friend_name->device_type == 'A'){
			$message = array('sound' =>1,'message'=>'you have received friend request',
			'notifykey'=>'friend_request','data'=>'hello','title'=>'Mo-Tiv','friend_id'=>$id,'request_id'=>$request_id,'notify_count'=>$notify_count);
			$device_token=$get_friend_name->device_token;
			$this->send_android_notification($device_token,'you have received friend request','friend_request',$message);
		} 
		//$this->responseOk('Friend request sent to '.$get_friend_name->name.' Successfully ','');
		$data="";
		echo json_encode(['result'=>'Success','message'=>'Friend request sent to '.$get_friend_name->name.' Successfully','data'=>$data,'request_id'=>$request_id]);exit;
		
	}
	/********************************************************************************************/
	public function pendingList(Request $request){
		$id=$request->input('user_id');
		if(empty($id)){
			$user_data = $this->checkUserExist();
			$id=$user_data->id;
		}
		if($user_data->blockStatus==1){
			$this->responseWithblock('You are blocked by admin');
		}
		$get_friend = FriendList::where(['request_status'=>2,'receiver_id'=>$id])
					->select('id as request_id','sender_id','receiver_id')
					->get();
		foreach($get_friend as $eachGet_friend){
			$eachGet_friend->senderDetail;
		}
		if(count($get_friend) > 0){
			$result=['result'=>'Success','message'=>'Friend List','data'=>$get_friend];
			return response()->json($result);
		}else{
			$this->responseWithError('You don’t have any Friend Request');
		}
	}
    /********************************************************************************************/ 
	public function acceptDecline(Request $request){ 
			$validator = Validator::make($request->all(),[
			'request_id' =>'required',
			'accept_status' =>'required',
		]);
		if($validator->fails()){
			$this->responseWithError($validator->errors()->first());
			exit;
		}
        $check_id=DB::table('friends')->where(['id'=>$request->input('request_id')])->first();
        if(empty($check_id)){
			$this->responseWithError('Request may be deleted/ Cancelled by user');
		} 		
		$update_data = FriendList::where('id',$request->input('request_id'))
					->update(['request_status'=>$request->input('accept_status')]);
		$get_user=DB::table('friends')->where(['id'=>$request->input('request_id')])->first();			
		$get_friend_name=DB::table('users')->where(['id'=>$get_user->sender_id])->first();	
		$get_reciever_name=DB::table('users')->where(['id'=>$get_user->receiver_id])->first();				
		if($request->input('accept_status')==1){
			DB::table('notification_list')->insert(['user_id'=>$get_user->sender_id,'other_user_id'=>$get_user->receiver_id,'message'=>$get_reciever_name->name.' '. 'accepted your friend request','notification_type'=>1,'timestamp'=>round(microtime(true) * 1000),'created_at' =>Date('Y-m-d H:i:s'),'updated_at' =>Date('Y-m-d H:i:s')]);
			$notify_count=$this->notification_count($get_user->sender_id);
			if($get_friend_name->device_type == 'I'){
					$message = array('sound' =>1,'message'=>$get_reciever_name->name.' '. 'accepted your friend request',
					'notifykey'=>'accept_friend','data'=>'Mo-Tiv','title'=>$get_reciever_name->name,'friend_id'=>$get_user->receiver_id,'request_id'=>(int)$request->input('request_id'),'notify_count'=>$notify_count);
					$device_token=$get_friend_name->device_token;
					$dd=$this->send_iphone_notification($device_token,$get_reciever_name->name.' '. 'accepted your friend request','accept_friend',$message);
			}   
			if($get_friend_name->device_type == 'A'){
				$message = array('sound' =>1,'message'=>$get_reciever_name->name.' '. 'accepted your friend request',
				'notifykey'=>'accept_friend','data'=>'Mo-Tiv','title'=>$get_reciever_name->name,'friend_id'=>$get_user->receiver_id,'request_id'=>(int)$request->input('request_id'),'notify_count'=>$notify_count);
				$device_token=$get_friend_name->device_token;
				$this->send_android_notification($device_token,$get_reciever_name->name.' '. 'accepted your friend request','accept_friend',$message);
			}
			
			
			$this->responseOk('Request accepted successfully','');
		}else{
			$update_data = FriendList::where('id',$request->input('request_id'))->delete();
			DB::table('notification_list')->insert(['user_id'=>$get_user->sender_id,'other_user_id'=>$get_user->receiver_id,'message'=>$get_reciever_name->name.' '. 'rejected your friend request','notification_type'=>1,'timestamp'=>round(microtime(true) * 1000),'created_at' =>Date('Y-m-d H:i:s'),'updated_at' =>Date('Y-m-d H:i:s')]);			  
			
			$notify_count=$this->notification_count($get_user->sender_id);
			
			if($get_friend_name->device_type == 'I'){
					$message = array('sound' =>1,'message'=>$get_reciever_name->name.' '. 'rejected your friend request',
					'notifykey'=>'reject_friend','data'=>'Mo-Tiv','title'=>$get_reciever_name->name,'friend_id'=>$get_user->receiver_id,'request_id'=>(int)$request->input('request_id'),'notify_count'=>$notify_count);
					$device_token=$get_friend_name->device_token;
					$dd=$this->send_iphone_notification($device_token,$get_reciever_name->name.' '. 'rejected your friend request','reject_friend',$message);
			}   
			if($get_friend_name->device_type == 'A'){
				$message = array('sound' =>1,'message'=>$get_reciever_name->name.' '. 'rejected your friend request',
				'notifykey'=>'reject_friend','data'=>'Mo-Tiv','title'=>$get_reciever_name->name,'friend_id'=>$get_user->receiver_id,'request_id'=>(int)$request->input('request_id'),'notify_count'=>$notify_count);
				$device_token=$get_friend_name->device_token;
				$this->send_android_notification($device_token,$get_reciever_name->name.' '. 'rejected your friend request','reject_friend',$message);
			}
			
			$this->responseOk('Request declined successfully','');
		}
	}
	/********************************************************************************************/ 
	public function commentList(Request $request){
		//$user_data = $this->checkUserExist();
		$validator = Validator::make($request->all(), [
			'post_id' =>'required|numeric|exists:post_list,id',
		]);
		if($validator->fails()){
			$this->responseWithError($validator->errors()->first());
			exit;
		}
		$get_comments = CommentList::where(['post_id'=>$request->input('post_id')])
						->get();
		foreach($get_comments as $each_get_comments){
			$each_get_comments->userInfo;
		}
		if(count($get_comments) > 0){
			$this->responseOk('Friend List',$get_comments);
		}else{
			$this->responseWithError('No Comments');
		}
	}
	/********************************************************************************************/ 
	public function addComment(Request $request){
		$id=$request->input('user_id');
		if(empty($id)){
			$user_data = $this->checkUserExist();
			$id=$user_data->id;
		}
		if($user_data->blockStatus==1){
			$this->responseWithblock('You are blocked by admin');
		}
		$validator = Validator::make($request->all(),[
			'post_id' =>'required|numeric|exists:post_list,id',
		    'comment' =>'required',
		]);
		if($validator->fails()){
			$this->responseWithError($validator->errors()->first());
			exit;
		}
		$comment = CommentList::insert(['post_id'=>$request->input('post_id'),
				'user_id'=>$id,
				'comment'=>$request->input('comment'),
				'timestamp'=>round(microtime(true) * 1000)]);
		$get_post=DB::table('post_list')->where(['id'=>$request->input('post_id')])->first();
		$get_event=DB::table('event_schedule')->where(['id'=>$get_post->event_id])->first();		
		$get_friend_name=DB::table('users')->where(['id'=>$get_post->user_id])->first();
		if($get_post->user_id !=$id){
			$get_reciever_name=DB::table('users')->where(['id'=>$id])->first();	
			/*save notification*/			
		    DB::table('notification_list')->insert(['user_id'=>$get_post->user_id,'other_user_id'=>$id,'message'=>$get_reciever_name->name.' '.'Commented on your post','notification_type'=>2,'timestamp'=>round(microtime(true) * 1000),'created_at' =>Date('Y-m-d H:i:s'),'updated_at' =>Date('Y-m-d H:i:s')]);		
			/************/
			$notify_count=$this->notification_count($get_post->user_id);
			
			if($get_friend_name->device_type == 'I'){
					$message = array('sound' =>1,'message'=>$get_reciever_name->name.' '.'Commented on your post',
					'notifykey'=>'comment','data'=>'Mo-Tiv','title'=>$get_reciever_name->name,'post_id'=>$get_post->id,
					'event_date'=>$get_event->event_date,'event_time'=>$get_event->event_time,'notify_count'=>$notify_count);
					$device_token=$get_friend_name->device_token;
					$dd=$this->send_iphone_notification($device_token,$get_reciever_name->name.' '.'Commented on your post','comment',$message);
			}   
			if($get_friend_name->device_type == 'A'){
				$message = array('sound' =>1,'message'=>$get_reciever_name->name.' '.'Commented on your post',
				'notifykey'=>'comment','data'=>'hello','title'=>$get_reciever_name->name,'post_id'=>$get_post->id,
				'event_date'=>$get_event->event_date,'event_time'=>$get_event->event_time,'notify_count'=>$notify_count);
				$device_token=$get_friend_name->device_token;
				$this->send_android_notification($device_token,$get_reciever_name->name.' '.'Commented on your post','comment',$message);
			}
			
		}			
		$this->responseOk('Comment added successfully','');
		
	}
	/********************************************************************************************/ 
	public function likePost(Request $request){
		//$user_data = $this->checkUserExist();
		$id=$request->input('user_id');
		if(empty($id)){  
			$user_data = $this->checkUserExist();
			$id=$user_data->id;
		}
		if($user_data->blockStatus==1){
			$this->responseWithblock('You are blocked by admin');
		}
		$validator = Validator::make($request->all(),[
			'post_id' =>'required|numeric|exists:post_list,id',
		    'like_status' =>'required',
		]);
		if($validator->fails()){
			$this->responseWithError($validator->errors()->first());
			exit;  
		}
		$check_status = Like::where(['user_id'=>$id,'post_id'=>$request->input('post_id')])->first();
		$get_post=DB::table('post_list')->where(['id'=>$request->input('post_id')])->first();
		$event_id=$get_post->event_id;
		$get_event=DB::table('event_schedule')->where(['id'=>$event_id])->first();
		$check_attend_status=DB::table('invitations')->where(function ($query) use ($id,$event_id){
												$query->where(['sender_id'=>$id])
												->where(['sub_event_id'=>$event_id]);
												})->orWhere(function($query) use ($id,$event_id){
												$query->where(['receiver_id'=>$id])
												->where(['sub_event_id'=>$event_id]);
												})->first();
		if(!empty($check_attend_status)){
			$attend_status=1;
		}else{
			$attend_status=2;
		}
		
		$get_user=DB::table('users')->where(['id'=>$id])->first();
		$get_event_name=DB::table('event_list')->where(['id'=>$get_event->event_id])->first();
		if(empty($check_status)){
				$like = Like::insert(['post_id'=>$request->input('post_id'),
									'user_id'=>$id,
									'like_status'=>$request->input('like_status'),
									'timestamp'=>round(microtime(true) * 1000)]);
			if($get_post->user_id !=$id){
				$get_friend_name=DB::table('users')->where(['id'=>$get_post->user_id])->first();	
				/*save notification*/			
				DB::table('notification_list')->insert(['user_id'=>$get_post->user_id,'other_user_id'=>$id,'message'=>$get_user->name.' '.'Liked your post','notification_type'=>2,'timestamp'=>round(microtime(true) * 1000),'created_at' =>Date('Y-m-d H:i:s'),'updated_at' =>Date('Y-m-d H:i:s')]);
				/******/
				$notify_count=$this->notification_count($get_post->user_id);
				if($get_friend_name->device_type == 'I'){
						$message = array('sound' =>1,'message'=>$get_user->name.' '.'Liked your post',
						'notifykey'=>'like_post','data'=>'Mo-Tiv','title'=>$get_user->name,'event_id'=>$get_post->event_id,'attend_status'=>$attend_status,
						'event_date'=>$get_event->event_date,'event_time'=>$get_event->event_time,'event_name'=>$get_event_name->event_name,'notify_count'=>$notify_count);
						$device_token=$get_friend_name->device_token;
						$dd=$this->send_iphone_notification($device_token,$get_user->name.' '.'Liked your post','like_post',$message);
				}   
				if($get_friend_name->device_type == 'A'){
					$message = array('sound' =>1,'message'=>$get_user->name.' '.'Liked your post',
					'notifykey'=>'like_post','data'=>'hello','title'=>$get_user->name,'event_id'=>$get_post->event_id,'attend_status'=>$attend_status,
					'event_date'=>$get_event->event_date,'event_time'=>$get_event->event_time,'event_name'=>$get_event_name->event_name,'notify_count'=>$notify_count);
					$device_token=$get_friend_name->device_token;
					$this->send_android_notification($device_token,$get_user->name.' '.'Liked your post','like_post',$message);
				}
				
			}
            $this->responseOk('Post liked successfully','');			
		}elseif($check_status->like_status ==1){ 
				$like = Like::where('id',$check_status->id)->update(['like_status'=>2]);
				$this->responseOk('Post disliked successfully','');
		}elseif($check_status->like_status ==2){
			$like = Like::where('id',$check_status->id)->update(['like_status'=>1]);
			if($get_post->user_id !=$id){
				$get_friend_name=DB::table('users')->where(['id'=>$get_post->user_id])->first();
				/*save notification*/			
				DB::table('notification_list')->insert(['user_id'=>$get_post->user_id,'other_user_id'=>$id,'message'=>$get_user->name.' '.'Liked your post','notification_type'=>2,'timestamp'=>round(microtime(true) * 1000),'created_at' =>Date('Y-m-d H:i:s'),'updated_at' =>Date('Y-m-d H:i:s')]);
				/******/				
				$notify_count=$this->notification_count($get_post->user_id);
				if($get_friend_name->device_type == 'I'){
						$message = array('sound' =>1,'message'=>$get_user->name.' '.'Liked your post',
						'notifykey'=>'like_post','data'=>'Mo-Tiv','title'=>$get_friend_name->name,'event_id'=>$get_post->event_id,'attend_status'=>$attend_status,
						'event_date'=>$get_event->event_date,'event_time'=>$get_event->event_time,'event_name'=>$get_event_name->event_name,'notify_count'=>$notify_count);
						$device_token=$get_friend_name->device_token;
						$dd=$this->send_iphone_notification($device_token,$get_user->name.' '.'Liked your post','like_post',$message);
				}   
				if($get_friend_name->device_type == 'A'){
					$message = array('sound' =>1,'message'=>$get_user->name.' '.'Liked your post',
					'notifykey'=>'like_post','data'=>'hello','title'=>'Mo-Tiv','event_id'=>$get_post->event_id,'attend_status'=>$attend_status,
					'event_date'=>$get_event->event_date,'event_time'=>$get_event->event_time,'event_name'=>$get_event_name->event_name,'notify_count'=>$notify_count);
					$device_token=$get_friend_name->device_token;
					$this->send_android_notification($device_token,$get_user->name.' '.'Liked your post','like_post',$message);
				}
				
			}
			$this->responseOk('Post liked successfully','');			
		}
		
	}
	/********************************************************************************************/ 
	public function cancel_request(Request $request){
	//	$user_data = $this->checkUserExist();
		$request_id = $this->is_require($request->input('request_id'),'request_id');
		$check_request=FriendList::where(['id' =>$request_id])->first();
		if(empty($check_request)){
			return $this->responseWithError('Request not found/ cancelled by user');
		}
		$comment = FriendList::where(['id' =>$request_id])->delete();
		$this->responseOk('Request cancelled successfully','');
	}
	/********************************************************************************************/ 
	public function search_invite(Request $request){
		$id=$request->input('user_id');
		if(empty($id)){
			$user_data = $this->checkUserExist();
			$id=$user_data->id;
			if($user_data->blockStatus==1){
				$this->responseWithblock('You are blocked by admin');
			}
		}
		
		$name = $request->input('name');
		$event_id = $request->input('event_id');
		$event_id = $this->is_require($request->input('event_id'),'event_id');
		if(!empty($name)){
			$friends=DB::table('friends')
					->where(function ($query) use ($id){
						$query->where(['sender_id'=>$id]);
						$query->whereNotIn('sender_id',$this->get_user_block_list($id));
						$query->where(['request_status'=>1]);
					})->orWhere(function($query) use ($id){
						$query->where(['receiver_id'=>$id]);
						$query->whereNotIn('receiver_id',$this->get_user_block_list($id));
						$query->where(['request_status'=>1]);
					})->get();
			foreach($friends as $friend){
				if($friend->sender_id !=$id){
					$record[]=$friend->sender_id;
				}
				if($friend->receiver_id !=$id){
					$record[]=$friend->receiver_id;
				}
			}
           	if(empty($record)){
				$record=array();
			}	
			$find_users = User::whereIn('id',$record)->where('name','LIKE',"%{$name}%")->paginate(10); 
				foreach($find_users as $each_friend){
				$friend_id=$each_friend->id;
				$check_request=DB::table('invitations')->where(function ($query) use ($id,$friend_id,$event_id) {
												//$query->where(['sender_id'=>$id])
												$query->where(['receiver_id'=>$friend_id])
												->where(['sub_event_id'=>$event_id]);
										})->orWhere(function($query) use ($id,$friend_id,$event_id){
												//$query->where(['sender_id'=>$friend_id])
												$query->where(['receiver_id'=>$friend_id])
												->where(['sub_event_id'=>$event_id]);
												//->where(['request_status'=>1]);													
										})->first();
				
				#1 invite succes,2= invite pending,3=not invite yet,4=invite by other
				if(!empty($check_request)){
					if($check_request->request_status ==1){
						$each_friend->invite_status=1;
					}elseif(($check_request->sender_id == $id) && ($check_request->request_status ==2)){
						$each_friend->invite_status=2;
						$each_friend->invitation_id=$check_request->id;
					}else{
						$each_friend->invite_status=4;
					}
				}else{
					/* if($id ==$friend_id){
						$each_friend->invite_status=1;
					}else{ */
					   $each_friend->invite_status=3;
					//}
				}
			}		
		}else{
			$friends=DB::table('friends')
					->where(function ($query) use ($id){
						$query->where(['sender_id'=>$id]);
						$query->where(['request_status'=>1]);
					})->orWhere(function($query) use ($id){
						$query->where(['receiver_id'=>$id]);
						$query->where(['request_status'=>1]);
					})->get();
			foreach($friends as $friend){
				if($friend->sender_id !=$id){
					$record[]=$friend->sender_id;
				}
				if($friend->receiver_id !=$id){
					$record[]=$friend->receiver_id;
				}
			}
			if(empty($record)){
				$record=array();
			}
         	$find_users = User::whereIn('id',$record)->paginate(10); 
			foreach($find_users as $each_friend){
				$friend_id=$each_friend->id;
				$check_request=DB::table('invitations')->where(function ($query) use ($id,$friend_id,$event_id) {
											//$query->where(['sender_id'=>$id])
												$query->where(['receiver_id'=>$friend_id])
												->where(['sub_event_id'=>$event_id]);
												//->where(['request_status'=>1]);
										})->orWhere(function($query) use ($id,$friend_id,$event_id){
											//$query->where(['sender_id'=>$friend_id])
												$query->where(['receiver_id'=>$friend_id])
												->where(['sub_event_id'=>$event_id]);
												//->where(['request_status'=>1]);													
										})->first();
				if(!empty($check_request)){
					if($check_request->request_status ==1){
						$each_friend->invite_status=1;
					}elseif(($check_request->sender_id == $id) && ($check_request->request_status ==2)){
						$each_friend->invite_status=2;
						$each_friend->invitation_id=$check_request->id;
					}else{
						$each_friend->invite_status=4;
					}
				}else{
					/* if($id ==$friend_id){
						$each_friend->invite_status=1;
					}else{ */
					   $each_friend->invite_status=3;
					//}
				}
			}			
		}
     	if(count($find_users) > 0){
			$this->responseOk('Friend List',$find_users);
		}else{
			$this->responseWithError('');
		}
	}
	/********************************************************************************************/
	public function send_invitation(Request $request){
		$id=$request->input('user_id');
		if(empty($id)){
			$user_data = $this->checkUserExist();
			$id=$user_data->id;
			if($user_data->blockStatus==1){
				$this->responseWithblock('You are blocked by admin');
			}
		}
		
		$friend_id=$request->input('receiver_id');
		$validator = Validator::make($request->all(),[
			'receiver_id' =>'required|numeric|exists:users,id',
			]);
		if($validator->fails()){
			$this->responseWithError($validator->errors()->first());
			exit;
		}
		if($id == $request->input('receiver_id')){
			$this->responseWithError('You cant Invite yourself');
			exit;
		}
				
		$get_event=DB::table('event_schedule')->where(['id'=>$request->input('event_id')])->first();
		if(!empty($get_event)){
			$check_event_id=DB::table('event_list')->where(['id'=>$get_event->event_id,'status'=>2])->first();
			if(empty($check_event_id)){
			  $this->responseWithError('This event has been blocked/deleted by admin');
			}
		}else{
			$this->responseWithError('This event has been blocked/deleted by admin');
		}
		$event_id=$request->input('event_id');
		$check_request=DB::table('invitations')->where(function ($query) use ($id,$friend_id,$event_id){
												$query->where(['receiver_id'=>$friend_id])
												->where(['sub_event_id'=>$event_id]);
												})->orWhere(function($query) use ($id,$friend_id,$event_id){
												$query->where(['sender_id'=>$friend_id])
												//->where(['receiver_id'=>$id])
												->where(['sub_event_id'=>$event_id]);
												})->first();
		//dd($check_request);die;
		$get_friend_name=User::where(['id'=>$request->input('receiver_id')])->first();										
		if(!empty($check_request)){									
				$this->responseOk('This user already invited for this event','');
		}else{	
			$get_friend = DB::table('invitations')->insert(['sender_id'=>$id,'receiver_id'=>$request->input('receiver_id'),'event_id'=>$get_event->event_id,'sub_event_id'=>$request->input('event_id'),'created_at'=>date("Y-m-d h:i:s"),'timestamp'=>round(microtime(true) * 1000)]);
			/*save invitation notification*/
			DB::table('notification_list')->insert(['user_id'=>$request->input('receiver_id'),'other_user_id'=>$id,'message'=>'You have received event invitation','notification_type'=>1,'timestamp'=>round(microtime(true) * 1000),'created_at' =>Date('Y-m-d H:i:s'),'updated_at' =>Date('Y-m-d H:i:s')]);
	        /***************************/		
		}
		
			$get_friend_name=DB::table('users')->where(['id'=>$friend_id])->first();	
			$notify_count=$this->notification_count($friend_id);
			if($get_friend_name->device_type == 'I'){
					$message = array('sound' =>1,'message'=>'You have received event invitation',
					'notifykey'=>'invitation','data'=>'Mo-Tiv','title'=>'Mo-Tiv',
					'event_date'=>$get_event->event_date,'event_time'=>$get_event->event_time,'notify_count'=>$notify_count);
					$device_token=$get_friend_name->device_token;
					$dd=$this->send_iphone_notification($device_token,'You have received event invitation','invitation',$message);
			}   
			if($get_friend_name->device_type == 'A'){
				$message = array('sound' =>1,'message'=>'You have received event invitation',
				'notifykey'=>'invitation','data'=>'hello','title'=>'Mo-Tiv',
				'event_date'=>$get_event->event_date,'event_time'=>$get_event->event_time,'notify_count'=>$notify_count);
				$device_token=$get_friend_name->device_token;
				$this->send_android_notification($device_token,'You have received event invitation','invitation',$message);
			}
			$this->responseOk('Invitation sent to '.$get_friend_name->name.' Successfully ','');
	}
	/********************************************************************************************/
	public function cancel_invitation(Request $request){
	//	$user_data = $this->checkUserExist();
		$invitation_id = $this->is_require($request->input('invitation_id'),'invitation_id');
		$check_invitation=DB::table('invitations')->where(['id' =>$invitation_id])->first();
		if(empty($check_invitation)){
			return $this->responseWithError('Event may be deleted/ Cancelled by user');
		}
		$comment = DB::table('invitations')->where(['id' =>$invitation_id])->delete();
		$this->responseOk('Invitation cancelled successfully','');
	}
	/********************************************************************************************/
	public function invitation_list(Request $request){
		$id=$request->input('user_id');
		if(empty($id)){
			$user_data = $this->checkUserExist();
			$id=$user_data->id;
		}
		//dd($id);
		$current_date = Date('Y-m-d H:i');
		$invitations=DB::table('invitations')/* ->where(function ($query) use ($id){
												   $query->where(['sender_id'=>$id])
													->where(['request_status'=>2]);
											})->or */
											->join('event_schedule','event_schedule.id','=','invitations.sub_event_id')
											->whereNotIn('receiver_id',$this->get_user_block_list($id)) 
											->whereNotIn('invitations.event_id',$this->get_event_block_list($id))
											->where('event_schedule.event_end_date_time','>=',$current_date)
											->Where(function($query) use ($id){
												   $query->where(['receiver_id'=>$id])
												   ->where(['request_status'=>2]);
											})->select('invitations.event_id','sub_event_id as event_id','invitations.id as invitation_id','receiver_id','sender_id')
												->orderBy('invitations.id', 'desc')
												->get();
		
		foreach($invitations as $invitation){
			//$invitation->event = DB::table('event_list')->where(['id'=>$invitation->event_id])->first();
			
			 $invitation->event	= DB::table('event_list')
						 ->leftJoin('event_schedule', 'event_schedule.event_id', '=', 'event_list.id')
						 ->where('event_schedule.id',$invitation->event_id)
						 // ->where('event_schedule.event_end_date_time','>=',$current_date)
						 ->first();  
			$invitation->user_detail=DB::table('users')->where(['id'=>$invitation->sender_id])->select('name','email','image_url')->first();
			$invitation->guest_count=DB::table('invitations')->where(['event_id'=>$invitation->event_id,'request_status'=>1])->count();
			$data[]=$invitation;
		}
		if(count($invitations) >0){
			$result=['result'=>'Success','message'=>'Event List','data'=>$data];
			return response()->json($result);
		}else{
			$this->responseWithError('No Invitation found');
		}
	}
	/********************************************************************************************/
	public function accept_decline_invitation(Request $request){ 
	   // $invitation_id = $this->is_require($request->input('invitation_id'),'invitation_id');
	    $invitation_id = $request->input('invitation_id');
		if(empty($invitation_id)){
			$this->responseWithError('Invitation may be deleted/ Cancelled by user');
		}
		$accept_status = $this->is_require($request->input('accept_status'),'accept_status');
		$check_id=DB::table('invitations')->where(['id'=>$invitation_id])->first();
		if(empty($check_id)){
				$this->responseWithError('Invitation may be deleted/ Cancelled by user');
		}
		$update_data = DB::table('invitations')->where('id',$invitation_id)->update(['request_status'=>$request->input('accept_status')]);
		$get_friend_name=DB::table('users')->where(['id'=>$check_id->sender_id])->first();
		$get_reciever_name=DB::table('users')->where(['id'=>$check_id->receiver_id])->first();
		$get_event=DB::table('event_schedule')->where(['id'=>$check_id->sub_event_id])->first();		
		if($request->input('accept_status')==1){
			DB::table('notification_list')->insert(['user_id'=>$check_id->sender_id,'other_user_id'=>$check_id->receiver_id,'message'=>$get_reciever_name->name.' '.'accepted your event invitation','notification_type'=>1,'timestamp'=>round(microtime(true) * 1000),'created_at' =>Date('Y-m-d H:i:s'),'updated_at' =>Date('Y-m-d H:i:s')]);
			
			$notify_count=$this->notification_count($check_id->sender_id);
			
			if($get_friend_name->device_type == 'I'){
					$message = array('sound' =>1,'message'=>$get_reciever_name->name.' '.'accepted your event invitation',
					'notifykey'=>'accept_invitation','data'=>'Mo-Tiv','title'=>$get_reciever_name->name,'event_id'=>$check_id->sub_event_id,
					'event_date'=>$get_event->event_date,'event_time'=>$get_event->event_time,'notify_count'=>$notify_count);
					$device_token=$get_friend_name->device_token;
					$dd=$this->send_iphone_notification($device_token,$get_reciever_name->name.' '.'accepted your event invitation','accept_invitation',$message);
			}   
			if($get_friend_name->device_type == 'A'){
				$message = array('sound' =>1,'message'=>$get_reciever_name->name.' '.'accepted your event invitation',
				'notifykey'=>'accept_invitation','data'=>'Mo-Tiv','title'=>$get_reciever_name->name,'event_id'=>$check_id->sub_event_id,
				'event_date'=>$get_event->event_date,'event_time'=>$get_event->event_time,'notify_count'=>$notify_count);
				$device_token=$get_friend_name->device_token;
				$this->send_android_notification($device_token,$get_reciever_name->name.' '.'accepted your event invitation','accept_invitation',$message);
			}
			
			$this->responseOk('Invitation accepted successfully','');
		}else{
			$update_data =DB::table('invitations')->where('id',$invitation_id)->delete();
			DB::table('notification_list')->insert(['user_id'=>$check_id->sender_id,'other_user_id'=>$check_id->receiver_id,'message'=>$get_reciever_name->name.' '.'rejected your event invitation','notification_type'=>1,'timestamp'=>round(microtime(true) * 1000),'created_at' =>Date('Y-m-d H:i:s'),'updated_at' =>Date('Y-m-d H:i:s')]);			
			$notify_count=$this->notification_count($check_id->sender_id);
			
			if($get_friend_name->device_type == 'I'){
					$message = array('sound' =>1,'message'=>$get_reciever_name->name.' '.'rejected your event invitation',
					'notifykey'=>'reject_invitation','data'=>'Mo-Tiv','title'=>$get_reciever_name->name,'event_id'=>$check_id->sub_event_id,
					'event_date'=>$get_event->event_date,'event_time'=>$get_event->event_time,'notify_count'=>$notify_count);
					$device_token=$get_friend_name->device_token;
					$dd=$this->send_iphone_notification($device_token,$get_reciever_name->name.' '.'rejected your event invitation','reject_invitation',$message);
			}   
			if($get_friend_name->device_type == 'A'){
				$message = array('sound' =>1,'message'=>$get_reciever_name->name.' '.'rejected your event invitation',
				'notifykey'=>'reject_invitation','data'=>'hello','title'=>$get_reciever_name->name,'event_id'=>$check_id->sub_event_id,
				'event_date'=>$get_event->event_date,'event_time'=>$get_event->event_time,'notify_count'=>$notify_count);
				$device_token=$get_friend_name->device_token;
				$this->send_android_notification($device_token,$get_reciever_name->name.' '.'rejected your event invitation','reject_invitation',$message);
			}
			
			$this->responseOk('Invitation declined successfully','');
		}
	}
	/********************************************************************************************/
	public function get_music_interest_list(Request $request){
     	$id=$request->input('user_id');
		if(empty($id)){
			$user_data = $this->checkUserExist();
			$id=$user_data->id;
		}
		if($user_data->blockStatus==1){
			$this->responseWithblock('You are blocked by admin');
		}
		$get_list=DB::table('users')->where(['id' =>$id])->first();
		$music_interests =  explode(',',$get_list->music_interest);
		
		foreach($music_interests as $music_interests){
			$music_interests=DB::table('music_interest')->where(['id'=>$music_interests])->select('id','name')->first();
		    if(!empty($music_interests)){		  
			$data[]=array('id'=>$music_interests->id,
		                'name'=>$music_interests->name);
		    } 
		}
        if(empty($data)){
			$data=array();
		}	    
		//$this->responseOk('Interest List',$data1);
		 echo json_encode(['result'=>'Success','message'=>'Interest List','data'=>$data]);exit;
	}
	/********************************************************************************************/
	public function update_music_interest_list(Request $request){
     	$id=$request->input('user_id');
		if(empty($id)){
			$user_data = $this->checkUserExist();
			$id=$user_data->id;
		}
		if($user_data->blockStatus==1){
			$this->responseWithblock('You are blocked by admin');
		}
		//$music_interest_id = $request->input('music_interest_id');
		$check_invitation=DB::table('users')->where(['id'=>$id])->update(['music_interest'=>$request->input('music_interest_id')]);
		$this->responseOk('Music interest list updated successfully','');
	}
	/********************************************************************************************/
	public function get_public_interest_list_old(Request $request){
     	$user_data = $this->checkUserExist();
		$check_invitation=DB::table('user_public_interest')->where(['user_id' =>$user_data->id])->select('public_interest_id')->get();
		foreach($check_invitation as $check_invitation){
			$check_invitation->interest_name=DB::table('public_interest')->where(['id' =>$check_invitation->public_interest_id])->select('name')->first();
		   
		   $data[]=$check_invitation;
		}
		$this->responseOk('Interest List',$data);
	}
	/********************************************************************************************/
	public function get_public_interest_list(Request $request){
     	$user_data = $this->checkUserExist();
		if($user_data->blockStatus==1){
			$this->responseWithblock('You are blocked by admin');
		}
		$get_list=DB::table('users')->where(['id' =>$user_data->id])->first();
		$public_interest =  explode(',',$get_list->public_interest);
		//print_r($public_interest);die;
		foreach($public_interest as $public_interest){
			$music_interests=DB::table('public_interest')->where(['id'=>$public_interest])->select('id','name')->first();
			if(!empty($music_interests)){		  
				$data[]=array('id'=>$music_interests->id,
		               'name'=>$music_interests->name);
		    } 
		}
		if(empty($data)){
			$data=array();
		}
		echo json_encode(['result'=>'Success','message'=>'Interest List','data'=>$data]);exit;
	}
	/********************************************************************************************/
	public function update_public_interest_list(Request $request){
	   	$user_data = $this->checkUserExist();
		if($user_data->blockStatus==1){
			$this->responseWithblock('You are blocked by admin');
		}
		$public_interest_id = $this->is_require($request->input('public_interest_id'),'public_interest_id');
		$check_invitation=DB::table('users')->where(['id'=>$user_data->id])->update(['public_interest'=>$public_interest_id]);
		$this->responseOk('Public interest list updated successfully',''); 
		
	}
	/********************************************************************************************/
	public function guest_list_old_27_08_2018(Request $request){
	   	$id=$request->input('user_id');
		if(empty($id)){
			$user_data = $this->checkUserExist();
			$id=$user_data->id;
			if($user_data->blockStatus==1){
				$this->responseWithblock('You are blocked by admin');
			}
		}
		
	    $event_id = $this->is_require($request->input('event_id'),'event_id');
		$get_event=DB::table('event_schedule')->where(['id'=>$request->input('event_id')])->first();
		if(!empty($get_event)){
			$check_event_id=DB::table('event_list')->where(['id'=>$get_event->event_id,'status'=>2])->first();
			if(empty($check_event_id)){
			  $this->responseWithError('This event has been blocked/deleted by admin');
			}
		}else{
			$this->responseWithError('This event has been blocked/deleted by admin');
		}
			$guests = DB::table('invitations')
					->leftJoin('users', 'users.id', '=', 'invitations.receiver_id')
					->where(['request_status'=>1,'sub_event_id'=>$event_id])
					 ->whereNotIn('receiver_id',$this->get_user_block_list($id))
					->select('users.name','users.email','users.image_url','users.id','event_id')
					->get();
					
			if(count($guests) > 0){	
			     foreach($guests as $guests){
					 $friend_id=$guests->id;
                    $chk_chat_id = DB::table('friends')
				   			->where(function($query) use ($id,$friend_id){
									  $query->where('sender_id',$id);
									  $query->where('receiver_id',$friend_id);
									  $query->where('request_status',1);
									})
								->orWhere(function($query) use ($friend_id,$id){
									 $query->where('sender_id',$friend_id);
									 $query->where('receiver_id',$id);
									  $query->where('request_status',1);
									})->first();
									
					 if(!empty($chk_chat_id)){
						$guests->friend_status = 1; #Yes 
					 }else{
						$guests->friend_status = 2; #No  
					 }
                 $check_sub_admin=DB::table('event_sub_admins')->where(['event_id'=>$guests->event_id,'user_id'=>$friend_id])->first();
				 if(!empty($check_sub_admin)){
					 $guests->co_admin = 1; #yes 
				 }else{
					 $guests->co_admin = 2; #No 
				 }
					 
                  $data[]=$guests; 					 
				 }
				$this->responseOk('Guest list',$data); 
			}else{
				$this->responseWithError('No guest found');
			}
		
	}

	public function guest_list(Request $request){
	   	$id=$request->input('user_id');
		if(empty($id)){
			$user_data = $this->checkUserExist();
			$id=$user_data->id;
			if($user_data->blockStatus==1){
				$this->responseWithblock('You are blocked by admin');
			}
		}
		
	    $event_id = $this->is_require($request->input('event_id'),'event_id');
		$get_event=DB::table('event_schedule')->where(['id'=>$request->input('event_id')])->first();
		if(!empty($get_event)){
			$check_event_id=DB::table('event_list')->where(['id'=>$get_event->event_id,'status'=>2])->first();
			if(empty($check_event_id)){
			  $this->responseWithError('This event has been blocked/deleted by admin');
			}
		}else{
			$this->responseWithError('This event has been blocked/deleted by admin');
		}
			$guests = DB::table('invitations')
					->leftJoin('users', 'users.id', '=', 'invitations.receiver_id')
					->where(['request_status'=>1,'sub_event_id'=>$event_id])
					 ->whereNotIn('receiver_id',$this->get_user_block_list($id))
					->select('users.name','users.email','users.image_url','users.id','event_id')
					->get();
					    
			if(count($guests) > 0){	
			    //print_r($guests);die;
			     foreach($guests as $guests){
					 $friend_id=$guests->id;
                    $chk_chat_id = DB::table('friends')
				   			->where(function($query) use ($id,$friend_id){
									  $query->where('sender_id',$id);
									  $query->where('receiver_id',$friend_id);
									 // $query->where('request_status',1);
									})
								->orWhere(function($query) use ($friend_id,$id){
									 $query->where('sender_id',$friend_id);
									 $query->where('receiver_id',$id);
									//  $query->where('request_status',1);
									})->first();
									
					/*  if(!empty($chk_chat_id)){
						$guests->friend_status = 1; #Yes 
					 }else{
						$guests->friend_status = 2; #No  
					 } */
			    if(!empty($chk_chat_id)){
					
					if($chk_chat_id->request_status ==1){
						$guests->friend_status=1;           #1=>accpeted
					}elseif($chk_chat_id->request_status ==2){
						$guests->friend_status=2;           #2=>pending    
						$guests->request_id=$chk_chat_id->id;
						$guests->sender_id=$chk_chat_id->sender_id;           
						$guests->receiver_id=$chk_chat_id->receiver_id; 
					}
				}else{
					if($id ==$friend_id){
						$guests->friend_status=1;
					}else{
						$guests->friend_status=3;    #3=>not send request yet
					}
				}	 
					
                $check_sub_admin=DB::table('event_sub_admins')->where(['event_id'=>$guests->event_id,'user_id'=>$friend_id])->first();
					if(!empty($check_sub_admin)){
					 $guests->co_admin = 1; #yes 
				}else{
					 $guests->co_admin = 2; #No 
				}
					 
                  $data[]=$guests; 					 
				}
				$this->responseOk('Guest list',$data); 
			}else{
				$this->responseWithError('No guest found');
			}
		
	}

	/***********************************************attend_unattend_event*********************************************/
	public function attend_event(Request $request){
	    $id=$request->input('user_id');
		if(empty($id)){
			$user_data = $this->checkUserExist();
			$id=$user_data->id;
			if($user_data->blockStatus==1){
				$this->responseWithblock('You are blocked by admin');
			}
		}
		
		$event_id = $this->is_require($request->input('event_id'),'event_id');
		$get_event=DB::table('event_schedule')->where(['id'=>$event_id])->first();
		if(!empty($get_event)){
			$check_event_id=DB::table('event_list')->where(['id'=>$get_event->event_id,'status'=>2])->first();
			if(empty($check_event_id)){
			  $this->responseWithError('This event has been blocked/deleted by admin');
			}
		}else{
			$this->responseWithError('This event has been blocked/deleted by admin');
		}
		
		$check_user=DB::table('invitations')->where(['receiver_id'=>$id,'sub_event_id'=>$event_id])->first();
		if(empty($check_user)){
			$guests = DB::table('invitations')->insert(['event_id'=>$get_event->event_id,'sub_event_id'=>$event_id,'sender_id'=>0,'receiver_id'=>$id,'request_status'=>1,'created_at'=>date("Y-m-d h:i:s"),'timestamp'=>round(microtime(true) * 1000)]);
			$get_friend_name=DB::table('users')->where(['id'=>$get_event->user_id])->first();
			$attend_by=DB::table('users')->where(['id'=>$id])->first();
			DB::table('notification_list')->insert(['user_id'=>$get_event->user_id,'other_user_id'=>$id,'message'=>$attend_by->name." ".'Attending your event','notification_type'=>1,'timestamp'=>round(microtime(true) * 1000),'created_at' =>Date('Y-m-d H:i:s'),'updated_at' =>Date('Y-m-d H:i:s')]);
			
			$notify_count=$this->notification_count($get_event->user_id);
			
			if($get_friend_name->device_type == 'I'){
					$message = array('sound' =>1,'message'=>$attend_by->name." ".'Attending your event',
					'notifykey'=>'attend_event','data'=>'Mo-Tiv','title'=>$attend_by->name,'event_id'=>(int)$request->input('event_id'),'notify_count'=>$notify_count);
					$device_token=$get_friend_name->device_token;
					$dd=$this->send_iphone_notification($device_token,$attend_by->name." ".'Attending your event','attend_event',$message);
			}   
			if($get_friend_name->device_type == 'A'){
				$message = array('sound' =>1,'message'=>$attend_by->name." ".'Attending your event',
				'notifykey'=>'attend_event','data'=>'Mo-Tiv','title'=>$attend_by->name,'event_id'=>(int)$request->input('event_id'),'notify_count'=>$notify_count);
				$device_token=$get_friend_name->device_token;
				$this->send_android_notification($device_token,$attend_by->name." ".'Attending your event','attend_event',$message);
			}
			
		    $this->responseOk('You are now attending this event',''); 
		}else{
			 $check_user=DB::table('invitations')->where(['receiver_id'=>$id,'sub_event_id'=>$event_id])->delete();
		     $this->responseOk('Event attend status cancelled successfully','');
		}
		
	}
	/********************************************************************************************/
	public function search_event_old(Request $request){
		$id=$request->input('user_id');
		if(empty($id)){
			$user_data = $this->checkUserExist();
			$id=$user_data->id;
			if($user_data->blockStatus==1){
				$this->responseWithblock('You are blocked by admin');
			}
		}
		$name = $request->input('name');
		if(!empty($name)){
			//$events=DB::table('event_list')->where('event_name','LIKE',"%{$name}%")->where(['submit_by'=>3,'status'=>2])->get();
			$now = Carbon::now();
			$subDay=$now->subDay('1');
		    $date=$now->toDateTimeString();
			$end_dt=date('Y-m-d H:i:s');
		    $date_range=date('Y-m-d H:i:s', strtotime("+30 day", strtotime($end_dt)));
			$events=DB::table('event_list')
					->leftJoin('event_schedule', 'event_schedule.event_id', '=', 'event_list.id')
					->where(['submit_by'=>3,'status'=>2])
					->where('event_name','LIKE',"%{$name}%")
					->whereBetween('event_schedule.event_start_date_time',[$date,$date_range])
					->whereNotIn('event_list.id',$this->get_event_block_list($id))
					->get();  
					
			if(count($events)>0){
				foreach($events as $eachEvent){ 
				    $event_public_interest_list=DB::table('event_public_interest_list')->where(['event_id'=>$eachEvent->event_id])->first();
					if(!empty($event_public_interest_list)){
						$public_interest_id=$event_public_interest_list->public_interest_id;
					}else{
						$public_interest_id=5;
					}
					$event_music_interest_list=DB::table('event_music_interest_list')->where(['event_id'=>$eachEvent->event_id])->first();
					if(!empty($event_music_interest_list)){
						$music_interest_id=$event_music_interest_list->music_interest_id;
					}else{
						$music_interest_id=5;
					}
					$get_user=DB::table('users')->where(['id'=>$id])->first();
					//if((strpos($get_user->public_interest,(string)$public_interest_id) !== false) or (strpos($get_user->music_interest,(string)$music_interest_id) !== false)){
						$public_name=DB::table('public_interest')->where(['id'=>$public_interest_id])->first();   
						if(!empty($public_name)){
						$eachEvent->public_interest_name=$public_name->name;
						}else{
							$eachEvent->public_interest_name="";
						}
						$music_name=DB::table('music_interest')->where(['id'=>$music_interest_id])->first();   
						if(!empty($music_name)){
						$eachEvent->music_interest_name=$music_name->name;
						}else{
							$eachEvent->music_interest_name="";
						}
						$data[]=$eachEvent;
					//}
				}
			}else{
				$data = array();
			}
		}else{
			$now = Carbon::now();
			$subDay=$now->subDay('1');
		    $date=$now->toDateTimeString();
			$end_dt=date('Y-m-d H:i:s');
		    $date_range=date('Y-m-d H:i:s', strtotime("+30 day", strtotime($end_dt)));
			 // echo $date_range;
			//$events=DB::table('event_list')->where(['submit_by'=>3,'status'=>2])->get();
			$events=DB::table('event_list')    
					->leftJoin('event_schedule', 'event_schedule.event_id', '=', 'event_list.id')
					->where(['submit_by'=>3,'status'=>2])
					// ->groupBy('event_schedule.event_id')
					->whereBetween('event_schedule.event_date',[$date,$date_range])
					->orderBy('event_schedule.event_date')
					->whereNotIn('event_list.id',$this->get_event_block_list($id))
					->get();
					//die;
					if(count($events) > 0){			
						foreach($events as $event){
							$event_public_interest_list=DB::table('event_public_interest_list')->where(['event_id'=>$event->event_id])->first();
							if(!empty($event_public_interest_list)){
								$public_interest_id=$event_public_interest_list->public_interest_id;
							}else{
								$public_interest_id=500;
							}
							$event_music_interest_list=DB::table('event_music_interest_list')->where(['event_id'=>$event->event_id])->first();
							if(!empty($event_music_interest_list)){
								$music_interest_id=$event_music_interest_list->music_interest_id;
							}else{
								$music_interest_id=500;
							}   
							$get_user=DB::table('users')->where(['id'=>$id])->first();
							//$id=$event->public_interest_id;
							//if((strpos($get_user->public_interest,(string)$public_interest_id) !== false) or (strpos($get_user->music_interest,(string)$music_interest_id) !== false)){
								$public_name=DB::table('public_interest')->where(['id'=>$public_interest_id])->first();   
								if(!empty($public_name)){
								$event->public_interest_name=$public_name->name;
								}else{
									$event->public_interest_name="";
								}
								$music_name=DB::table('music_interest')->where(['id'=>$music_interest_id])->first();   
								if(!empty($music_name)){
								$event->music_interest_name=$music_name->name;
								}else{
									$event->music_interest_name="";
								}
								$data[]=$event;  
							//}
							
						}
					}else{
						$data = array();
					}	
		}
		if(!empty($name)){
			$invitations=DB::table('invitations')
						->where(function ($query) use ($id){
							$query->where(['sender_id'=>$id]);
							$query->where(['request_status'=>1]);
						})->orWhere(function($query) use ($id){
							$query->where(['receiver_id'=>$id]);
							$query->where(['request_status'=>1]);
						})->select('event_id')->distinct('event_id')->pluck('event_id');
			if(count($invitations) > 0){		
				$get_event = EventList::where(['status' =>1])
					->whereNotIn('event_list.id',$this->get_event_block_list($id))
					->where(function ($query) use ($id){
						$query->where(['user_id'=>$id]);
						$query->where(['submit_by'=>2]);
					})->orWhere(function($query) use ($id){
						$query->where(['submit_by'=>3,'status'=>2]);
					})->orWhere(function($query) use ($invitations){
						$query->whereIn('id',$invitations);
						$query->where(['submit_by'=>2]);
					})->orderBy('event_time','ASC')
					  ->get();
				 // print_r($invitations);exit; 
					if(count($get_event) > 0){
						foreach($get_event as $eachEvent){ 
							$event_public_interest_list=DB::table('event_public_interest_list')->where(['event_id'=>$eachEvent->id])->first();
							if(!empty($event_public_interest_list)){
								$public_interest_id=$event_public_interest_list->public_interest_id;
							}else{
								$public_interest_id=500;
							}
							$event_music_interest_list=DB::table('event_music_interest_list')->where(['event_id'=>$eachEvent->id])->first();
							if(!empty($event_music_interest_list)){
								$music_interest_id=$event_music_interest_list->music_interest_id;
							}else{
								$music_interest_id=500;
							}
							$get_user=DB::table('users')->where(['id'=>$id])->first();
						//$id=$event->public_interest_id;
							//if((strpos($get_user->public_interest,(string)$public_interest_id) !== false) or (strpos($get_user->music_interest,(string)$music_interest_id) !== false)){
								$public_name=DB::table('public_interest')->where(['id'=>$public_interest_id])->first();   
								if(!empty($public_name)){
								$eachEvent->public_interest_name=$public_name->name;
								}else{
									$eachEvent->public_interest_name="";
								}
								$music_name=DB::table('music_interest')->where(['id'=>$music_interest_id])->first();   
								if(!empty($music_name)){
								$eachEvent->music_interest_name=$music_name->name;
								}else{
									$eachEvent->music_interest_name="";
								}
								$data2[]=$eachEvent;
							//}
						}
					}else{
						$data2 = array();
					}
				}else{
					$data2 = array();
				}
		}else{
			$data2 = array();
		}
				
				
				if(count($data2) > 0){
					$record = $data2;
				}else{
					if(count($data) > 0){
						$record = $data;
					}else{
						$record = array();
					}
				}
				
		if(count($events)>0){
			///$this->responseOk('Event list',$record);
			 http_response_code(200);
			 echo json_encode(['result'=>'Success','message'=>'Event list','data'=>$record]);
			 exit;
		}else{
			$this->responseWithError('No More event found');
		}
		 
	}

	public function search_event(Request $request){
		$id=$request->input('user_id');  
		if(empty($id)){
			$user_data = $this->checkUserExist();
			$id=$user_data->id;
			if($user_data->blockStatus==1){
				$this->responseWithblock('You are blocked by admin');
			}
		}
		$name = $request->input('name');
		$filter = $request->input('filter');
		$public_interest = $request->input('public_interest');
		$music_interest = $request->input('music_interest');
		$lat = $request->input('lat');
		$long = $request->input('long');
		$miles = $request->input('miles');
		if(!empty($filter)){
			$now = Carbon::now();
			$subDay=$now->subDay('1');
		    $date=$now->toDateTimeString();
			$end_dt=date('Y-m-d H:i:s');
		    $date_range=date('Y-m-d H:i:s', strtotime("+30 day", strtotime($end_dt)));
			$query=DB::table('event_list')
					->leftJoin('event_schedule', 'event_schedule.event_id', '=', 'event_list.id')
					->where(['submit_by'=>3,'status'=>2])
					->whereBetween('event_schedule.event_start_date_time',[$date,$date_range])
					->whereNotIn('event_list.id',$this->get_event_block_list($id));
			if(!empty($lat) && !empty($long)){
				//die;
				$query_distance = '(3959 * acos( cos( radians("'.$lat.'") ) * cos( radians( event_lat ) ) * cos( radians( event_long ) - radians("'.$long.'") ) + sin( radians("'.$lat.'") ) * sin( radians( event_lat ) ) ) )';
				$query->select(['event_schedule.*','event_list.*',DB::raw(($query_distance) .' AS distance')]);
				$query->where(DB::raw($query_distance),'<=',$miles)->orderBy('distance','ASC');
			}
			if(!empty($public_interest)){
				$public_interest_array= explode(',',$public_interest);
				$public_interest_event_ids=DB::table('event_public_interest_list')->whereIn('public_interest_id',$public_interest_array)->pluck('event_id');
               // print_r($public_interest_event_ids);die;			   
			   $query->whereIn('event_list.id',$public_interest_event_ids);
			}
			if(!empty($music_interest)){
				$music_interest_array= explode(',',$music_interest);
				$music_interest_ids=DB::table('event_music_interest_list')->whereIn('music_interest_id',$music_interest_array)->pluck('event_id');
			    $query->whereIn('event_list.id',$music_interest_ids);
			}
			$events= $query->get();
			if(count($events)>0){
				foreach($events as $eachEvent){ 
				    $event_public_interest_list=DB::table('event_public_interest_list')->where(['event_id'=>$eachEvent->event_id])->first();
					if(!empty($event_public_interest_list)){
						$public_interest_id=$event_public_interest_list->public_interest_id;
					}else{
						$public_interest_id=0;
					}
					$event_music_interest_list=DB::table('event_music_interest_list')->where(['event_id'=>$eachEvent->event_id])->first();
					if(!empty($event_music_interest_list)){
						$music_interest_id=$event_music_interest_list->music_interest_id;
					}else{
						$music_interest_id=0;
					}
					$get_user=DB::table('users')->where(['id'=>$id])->first();
						$public_name=DB::table('public_interest')->where(['id'=>$public_interest_id])->first();   
						if(!empty($public_name)){
						$eachEvent->public_interest_name=$public_name->name;
						}else{
							$eachEvent->public_interest_name="";
						}
						$music_name=DB::table('music_interest')->where(['id'=>$music_interest_id])->first();   
						if(!empty($music_name)){
						$eachEvent->music_interest_name=$music_name->name;
						}else{
							$eachEvent->music_interest_name="";
						}
						$data[]=$eachEvent;
				}
				 http_response_code(200);
				 echo json_encode(['result'=>'Success','message'=>'Event list','data'=>$data]);
				 exit;
			}else{
				$this->responseWithError('No More event found');
		
			}  		
		}
		if(!empty($name)){
			$now = Carbon::now();
			$subDay=$now->subDay('1');
		    $date=$now->toDateTimeString();
			$end_dt=date('Y-m-d H:i:s');
		    $date_range=date('Y-m-d H:i:s', strtotime("+30 day", strtotime($end_dt)));
			$events=DB::table('event_list')
					->leftJoin('event_schedule', 'event_schedule.event_id', '=', 'event_list.id')
					->where(['submit_by'=>3,'status'=>2])
					->where('event_name','LIKE',"%{$name}%")
					->whereBetween('event_schedule.event_start_date_time',[$date,$date_range])
					->whereNotIn('event_list.id',$this->get_event_block_list($id))
					->get();    
					
			if(count($events)>0){
				foreach($events as $eachEvent){ 
				    $event_public_interest_list=DB::table('event_public_interest_list')->where(['event_id'=>$eachEvent->event_id])->first();
					if(!empty($event_public_interest_list)){
						$public_interest_id=$event_public_interest_list->public_interest_id;
					}else{
						$public_interest_id=0;
					}
					$event_music_interest_list=DB::table('event_music_interest_list')->where(['event_id'=>$eachEvent->event_id])->first();
					if(!empty($event_music_interest_list)){
						$music_interest_id=$event_music_interest_list->music_interest_id;
					}else{
						$music_interest_id=0;
					}
					$get_user=DB::table('users')->where(['id'=>$id])->first();
						$public_name=DB::table('public_interest')->where(['id'=>$public_interest_id])->first();   
						if(!empty($public_name)){
						$eachEvent->public_interest_name=$public_name->name;
						}else{
							$eachEvent->public_interest_name="";
						}
						$music_name=DB::table('music_interest')->where(['id'=>$music_interest_id])->first();   
						if(!empty($music_name)){
						$eachEvent->music_interest_name=$music_name->name;
						}else{
							$eachEvent->music_interest_name="";
						}
						$data[]=$eachEvent;
				}
			}else{
				$data = array();
			}
		}else{
			$now = Carbon::now();
			$subDay=$now->subDay('1');
		    $date=$now->toDateTimeString();
			$end_dt=date('Y-m-d H:i:s');
		    $date_range=date('Y-m-d H:i:s', strtotime("+30 day", strtotime($end_dt)));
			$events=DB::table('event_list')    
					->leftJoin('event_schedule', 'event_schedule.event_id', '=', 'event_list.id')
					->where(['submit_by'=>3,'status'=>2])
					->whereBetween('event_schedule.event_date',[$date,$date_range])
					->orderBy('event_schedule.event_date')
					->whereNotIn('event_list.id',$this->get_event_block_list($id))
					->get();
					if(count($events) > 0){			
						foreach($events as $event){
							$event_public_interest_list=DB::table('event_public_interest_list')->where(['event_id'=>$event->event_id])->first();
							if(!empty($event_public_interest_list)){
								$public_interest_id=$event_public_interest_list->public_interest_id;
							}else{
								$public_interest_id=0;
							}
							$event_music_interest_list=DB::table('event_music_interest_list')->where(['event_id'=>$event->event_id])->first();
							if(!empty($event_music_interest_list)){
								$music_interest_id=$event_music_interest_list->music_interest_id;
							}else{
								$music_interest_id=0;
							}   
							$get_user=DB::table('users')->where(['id'=>$id])->first();
								$public_name=DB::table('public_interest')->where(['id'=>$public_interest_id])->first();   
								if(!empty($public_name)){
									$event->public_interest_name=$public_name->name;
								}else{
									$event->public_interest_name="";
								}
								$music_name=DB::table('music_interest')->where(['id'=>$music_interest_id])->first();   
								if(!empty($music_name)){
									$event->music_interest_name=$music_name->name;
								}else{
									$event->music_interest_name="";
								}
								$data[]=$event;  
						}
					}else{
						$data = array();
					}
		}
					if(count($data) > 0){
						$record = $data;
					}else{
						$record = array();
					}
		if(count($events)>0){
			 http_response_code(200);
			 echo json_encode(['result'=>'Success','message'=>'Event list','data'=>$record]);
			 exit;
		}else{
			$this->responseWithError('No More event found');
		}
		 
	}
	
	public function search_event_old_20180820(Request $request){
		$id=$request->input('user_id');  
		if(empty($id)){
			$user_data = $this->checkUserExist();
			$id=$user_data->id;
			if($user_data->blockStatus==1){
				$this->responseWithblock('You are blocked by admin');
			}
		}
		$name = $request->input('name');
		$filter = $request->input('filter');
		$public_interest = $request->input('public_interest');
		$music_interest = $request->input('music_interest');
		$lat = $request->input('lat');
		$long = $request->input('long');
		$miles = $request->input('miles');
		if(!empty($filter)){
			$now = Carbon::now();
			$subDay=$now->subDay('1');
		    $date=$now->toDateTimeString();
			$end_dt=date('Y-m-d H:i:s');
		    $date_range=date('Y-m-d H:i:s', strtotime("+30 day", strtotime($end_dt)));
			$query=DB::table('event_list')
					->leftJoin('event_schedule', 'event_schedule.event_id', '=', 'event_list.id')
					->where(['submit_by'=>3,'status'=>2])
					->whereBetween('event_schedule.event_start_date_time',[$date,$date_range])
					->whereNotIn('event_list.id',$this->get_event_block_list($id));
			if(!empty($lat) && !empty($long)){
				$query_distance = '(3959 * acos( cos( radians("'.$lat.'") ) * cos( radians( event_lat ) ) * cos( radians( event_long ) - radians("'.$long.'") ) + sin( radians("'.$lat.'") ) * sin( radians( event_lat ) ) ) )';
				$query->select(['event_schedule.*',DB::raw(($query_distance) .' AS distance')]);
				$query->where(DB::raw($query_distance),'<=',$miles)->orderBy('distance','ASC');
			}
			if(!empty($public_interest)){
				$public_interest_array= explode(',',$public_interest);
				$public_interest_event_ids=DB::table('event_public_interest_list')->whereIn('public_interest_id',$public_interest_array)->pluck('event_id');
               // print_r($public_interest_event_ids);die;			   
			   $query->whereIn('event_list.id