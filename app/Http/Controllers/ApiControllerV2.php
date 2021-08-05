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
Use App\Models\EventView;
Use App\Models\UserCard; 
use GuzzleHttp;
use Carbon\Carbon; 
Use App\Http\Controllers\StripeCustomClass;
use Hash;
use Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use DB;
use DateTime;
use GuzzleHttp\Exception\RequestException;
use QrCode;
date_default_timezone_set('Asia/Kolkata');
//date_default_timezone_set('Europe/London');

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
							'first_name' => 'required|max:50',
							'email' => 'required|unique:users',
							'password' => 'required|min:6',
							'phone_number' => 'nullable|digits_between:8,15',
						]);
					}else{
						$validator = Validator::make($request->all(), [
							'first_name' => 'required|max:50',
							'email' => 'required|unique:users',
							'password' => 'required|min:6',
							'phone_number' => 'nullable|digits_between:8,15',
						]);
					}
				}else{
					if($user_type == 2){
						$validator = Validator::make($request->all(), [
							'first_name' => 'required|max:50',
							'phone_number' => 'nullable|digits_between:8,15',
							'social_id' => 'required',
							'social_signup_type' => 'required', // Facebook or Twiter or Instagram
						]);
					}else{
						$validator = Validator::make($request->all(), [
							'first_name' => 'required|max:50',
							'phone_number' => 'nullable|digits_between:8,15',
							'social_id' => 'required',
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
		
		/*Shubham-01-03-19*/
		/*$create_customer = new StripeCustomClass();
		$result = $create_customer->createCustomer($request->email,$description = '');
        $insert_data['stripe_customer_id'] = $result['stripe_customer_id'];
		if($result['status'] == 2){
			return $this->responseWithError('Oops Something went wrong!'); exit;
		}*/
		/*Shubham-01-03-19*/

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
								$m->from(env('MAIL_FROM'), 'MoTiv');
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
					$return_data = json_decode($response->getBody(), true);
					if(!empty($return_data)){
						$update_refresh_token = User::where(['id' => $check_user_exist->id])->update(['refresh_token' =>$return_data['refresh_token'],'device_token'=>$device_token,'device_type'=>$device_type]);
						$check_user_exist->access_token = $return_data['access_token'];
						$check_user_exist->friend_count=DB::table('friends')->orwhere(['receiver_id' =>$check_user_exist->id])->where(['request_status' =>1])->orwhere(['sender_id'=>$check_user_exist->id])->where(['request_status'=>1])->count();
						$check_user_exist->request_count=FriendList::where(['receiver_id' =>$check_user_exist->id,'request_status' =>2])->count();
					}
					
					$this->responseOk('Login Successfully',$check_user_exist);
				}catch(RequestException $ex){
					$return_data = $ex->getMessage();
					$this->responseWithError('Oops something wrong. Please try again later '.$return_data);
				}
			}else{
				$this->responseWithError('Email or password is incorrect');
			}
		}else{
			$this->responseWithError('Email or password is incorrect');
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
		$request_count=FriendList::where(['receiver_id' =>$id,'request_status' =>2])->count();
		$data=array('friend_count'=>$friend_count,'request_count'=>$request_count);
		$result=['result'=>'Success','message'=>'Profile counts','data'=>$data];
		return response()->json($result);
	
	}
	/********************************************************************************************/
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
												})->orWhere(function($query) use ($id,$user_id){
													$query->where(['sender_id'=>$user_id])
													->where(['receiver_id'=>$id]);
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
	public function profile_settings(Request $request){
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
		$validator=Validator::make($request->all(),[
                   'type'=>['required',Rule::In([1,2])],  //1=>age,2=>notification
                   'value'=>['required',Rule::In([1,2])],  //1=>on,2=>off
		]);
		if($validator->fails()){
			$this->responseWithError($validator->errors()->first());
		}
        if($request->type == 1){       
         	$update_data['age_status']=$request->value;
        }else if($request->type == 2){
        	$update_data['notification_status']=$request->value;
        }
      	User::where(['id'=>$id])->update($update_data);
		$user_data=User::where(['id'=>$id])->select('age_status','notification_status')->first();
		return $this->responseOk('status updated successfully',$user_data);
	}
	/********************************************************************************************/
	public function musicInterestList(Request $request){
		$get_datas = MusicInterest::all();

		// foreach ($get_datas as $get_data) {
		// 	MusicInterest::processData($get_data);
		// }

		return $this->responseOk('Music interest List',$get_datas);
	}
	/********************************************************************************************/
	public function publicInterestList(Request $request){
		$get_datas = PublicInterest::all();
		// foreach ($get_datas as $get_data) {
			
		// PublicInterest::processData($get_data);
		// }
		return $this->responseOk('Public interest List',$get_datas);
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
	/*************************************changePassword*******************************************************/
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
	/************************************forgotPassword********************************************************/
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
					Mail::send('email_forget_new',['url' => $url,'user_data' => $user_data], function ($m) use ($user_data) {
						$m->from(env('MAIL_FROM'), 'MoTiv');  
						$m->to($user_data->email,'App User');
						// $m->cc('deftsofttesting786@gmail.com','App User');
						$m->subject('Forgot password link');
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
	/*********************************************createEvent***********************************************/
	public function createEvent(Request $request){
		$user_data = $this->checkUserExist();
		$id=$user_data->id;
		$user_type = $user_data->role;
			// 1=>admin, 2=> user, 3 => Organizer
			if($user_type == 2){
				$validator = Validator::make($request->all(), [
					'image' =>'mimes:jpeg,png',
					'event_name' =>'required',
					'location' => 'required',
					'event_lat' => 'required',
					'event_long' => 'required',
					'event_start_date_time' => 'required',
					'event_end_date_time' => 'required',
					'event_detail' => 'required',
					'repeat_interval' => 'required|in:one_day,monthly,weekly,2_weekly',
					'event_image_url' => 'required_with:event_video_url',
				    'event_image_url2' => 'required_with:event_video_url2',
				]);
				$event_status=2;
				$post_type =2;
			}else if($user_type == 3){
				$validator = Validator::make($request->all(), [
					'event_name' =>'required',
					'location' => 'required',
					'event_lat' => 'required',
					'event_long' => 'required',
					'event_start_date_time' => 'required',
					'event_end_date_time' => 'required',
					'dress_code' => 'required',
					'age_restrictions' => 'required',
					'id_Required' => 'required',
					'event_detail' => 'required',
					'repeat_interval' => 'required|in:one_day,monthly,weekly,2_weekly',
					'event_image_url' => 'required_with:event_video_url',
				    'event_image_url2' => 'required_with:event_video_url2',
				]);
				$event_status=1;
				$post_type=1;
			}

			if($validator->fails()){
				return	$this->responseWithError($validator->errors()->first());
			}
     		
			$request->event_media_type = 1;
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
				'event_media_type' => $request->event_media_type,
				'submit_by' => $user_type, //1=>admin, 2=> user, 3 => Organizer
				'status' =>$event_status,
				'user_id' =>$id,
				'updated_at' => Date('Y-m-d H:i:s'),
				'created_at' => Date('Y-m-d H:i:s'),
			];
			   $event_date2=date('D j M',strtotime($request->input('event_start_date_time'))); 
                /*******upload video******/
				if(!empty($request->file('event_video_url'))){
					$check_type  = $request->file('event_video_url')->getClientOriginalExtension();
					$image_name = str_random(20).'.'.$check_type;
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
					$check_type  = $request->file('event_video_url2')->getClientOriginalExtension();
					$image_name = str_random(20).'.'.$check_type;
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
					if(!empty($request->file('event_image_url'))){
						$image_name = str_random(20).'.png';
						$path = Storage::putFileAs('public/event_media', $request->file('event_image_url'),$image_name);
						$baseUrl = url('/');
						$baseUrl = str_replace('/public','/',$baseUrl);
						$insert_array['event_image_url'] = $baseUrl.'storage/app/'.$path;
					}	
				}
				if(empty($request->file('event_video_url2'))){
					if(!empty($request->file('event_image_url2'))){
						$image_name = str_random(20).'.png';
						$path = Storage::putFileAs('public/event_media',$request->file('event_image_url2'),$image_name);
						$baseUrl = url('/');
						$baseUrl = str_replace('/public','/',$baseUrl);
						$insert_array['event_image_url2'] = $baseUrl.'storage/app/'.$path;
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
			
				$insert_array['ticket_amount'] = !empty($request->input('ticket_amount')) ? $request->input('ticket_amount') : '0';
				$insert_array['dress_code'] = !empty($request->input('dress_code')) ? $request->input('dress_code') : '';
				$insert_array['age_restrictions'] = !empty($request->input('age_restrictions')) ? $request->input('age_restrictions') : '0';
				$insert_array['id_Required'] = !empty($request->input('id_Required')) ? $request->input('id_Required') : '';
				$insert_array['url'] = !empty($request->input('url')) ? $request->input('url') : '';
				$insert_array['music_int_id']=$request->input('music_interest');
				$insert_array['public_int_id']=$request->input('public_interest');
				$insert_array['post_type'] =$post_type;
			
			  
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

		$this->create_event_schedule($request->input('event_start_date_time'),$request->input('event_end_date_time'),$request->input('repeat_interval'),$insert_id,$id);
			#send invitation to friends at event create time
			$get_lat_id=DB::table('event_schedule')
			->whereRaw("event_schedule.id IN (select MIN(event_schedule.id) FROM event_schedule WHERE event_id = '$insert_id')")
			->first();
			$get_event=DB::table('event_list')->where(['id'=>$insert_id])->first();
			$friend_ids = explode(',',$request->input('friend_id'));
			if(!empty($friend_ids[0])){      
				foreach($friend_ids as $friend_id ){
					$check_friend_id=DB::table('users')->where(['id'=>$friend_id])->first();
					if(empty($check_friend_id)){
						$this->responseWithError('You have entered wrong friend id');
					}				
					$get_friend_name=DB::table('users')->where(['id'=>$friend_id])->first();
             		$get_friend = DB::table('invitations')->insert(['sender_id'=>$id,'receiver_id'=>$friend_id,'event_id'=>$insert_id,'sub_event_id'=>$get_lat_id->id,'created_at'=>date("Y-m-d h:i:s"),'timestamp'=>round(microtime(true) * 1000)]);
					#save invitation notification
					DB::table('notification_list')->insert(['user_id'=>$friend_id,'other_user_id'=>$id,'message'=>'you have received event invitation','notification_type'=>1,'timestamp'=>round(microtime(true) * 1000),'created_at' =>Date('Y-m-d H:i:s'),'updated_at' =>Date('Y-m-d H:i:s')]);
					#***************************
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
	}
	
	/********************************************************************************************/
	public function edit_event(Request $request){
		$user_data = $this->checkUserExist();
		$id=$user_data->id;
		$user_type=$user_data->role;
			// 1=>admin, 2=> user, 3 => Organizer
			if($user_type == 2){
				$validator = Validator::make($request->all(), [
					'event_id' =>'required|numeric|exists:event_schedule,id',
				    'event_name' =>'required',
					'location' => 'required',
					'event_lat' => 'required',
					'event_long' => 'required',
					'event_start_date_time' => 'required',
					'event_end_date_time' => 'required',
					'event_detail' => 'required',
					'repeat_interval' => 'required|in:one_day,monthly,weekly,2_weekly',
					'event_image_url' => 'required_with:event_video_url',
				    'event_image_url2' => 'required_with:event_video_url2',
				]);
				$event_status=2;
				$post_type =2;
			}elseif($user_type == 3){
				$validator = Validator::make($request->all(), [
				    'event_id' =>'required|numeric|exists:event_schedule,id',
					'event_name' =>'required',
					'location' =>'required',
					'event_lat' =>'required',
					'event_long' =>'required',
					'event_start_date_time' => 'required',
					'event_end_date_time' => 'required',
					'dress_code' =>'required',
					'age_restrictions' =>'required',
					'id_Required' => 'required',
					'event_detail' => 'required',
					'repeat_interval' => 'required|in:one_day,monthly,weekly,2_weekly',
					'event_image_url' => 'required_with:event_video_url',
				    'event_image_url2' => 'required_with:event_video_url2',
				
				]);
				$event_status=1;
				$post_type =1;
			}
			if($validator->fails()){
				$this->responseWithError($validator->errors()->first());
				exit;
			}
			$event_id=$request->input('event_id');
			$get_event=DB::table('event_schedule')->where(['id'=>$event_id])->first();
			
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
						$this->responseOk('This user already invited for this event','');
					}
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
				'event_media_type' => $request->event_media_type = 1,
				'submit_by' => $user_type, //1=>admin, 2=> user, 3 => Organizer
				'user_id' =>$id,
				'updated_at' => Date('Y-m-d H:i:s'),
				'created_at' => Date('Y-m-d H:i:s'),
			];  
			
				if(!empty($request->file('event_video_url'))){
					$check_type  = $request->file('event_video_url')->getClientOriginalExtension();
					$image_name = str_random(20).'.'.$check_type;
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
					$check_type  = $request->file('event_video_url2')->getClientOriginalExtension();
					$image_name = str_random(20).'.'.$check_type;
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
					if(!empty($request->file('event_image_url'))){
						$image_name = str_random(20).'.png';
						$path = Storage::putFileAs('public/event_media', $request->file('event_image_url'),$image_name);
						$baseUrl = url('/');
						$baseUrl = str_replace('/public','/',$baseUrl);
						$insert_array['event_image_url'] = $baseUrl.'storage/app/'.$path;
					}	
				}
				if(empty($request->file('event_video_url2'))){
					if(!empty($request->file('event_image_url2'))){
						$image_name = str_random(20).'.png';
						$path = Storage::putFileAs('public/event_media',$request->file('event_image_url2'),$image_name);
						$baseUrl = url('/');
						$baseUrl = str_replace('/public','/',$baseUrl);
						$insert_array['event_image_url2'] = $baseUrl.'storage/app/'.$path;
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
				
			$insert_array['ticket_amount'] = !empty($request->input('ticket_amount')) ? $request->input('ticket_amount') : '0';
			$insert_array['dress_code'] = !empty($request->input('dress_code')) ? $request->input('dress_code') : '';
			$insert_array['age_restrictions'] = !empty($request->input('age_restrictions')) ? $request->input('age_restrictions') : '0';
			$insert_array['id_Required'] = !empty($request->input('id_Required')) ? $request->input('id_Required') : '';
			$insert_array['url'] = !empty($request->input('url')) ? $request->input('url') : '';
			$insert_array['music_int_id']=$request->input('music_interest');
			$insert_array['public_int_id']=$request->input('public_interest');
			$insert_array['post_type'] =$post_type;
			
			$event_start_date_time = date('Y-m-d H:i:s', strtotime($request->input('event_start_date_time')));
			$event_end_date_time = date('Y-m-d H:i:s', strtotime($request->input('event_end_date_time')));
			if(strtotime($event_start_date_time) > strtotime($event_end_date_time)){
				$event_end_date_time = date('Y-m-d H:i:s', strtotime("+1 days", strtotime($event_end_date_time)));
			}
			DB::table('event_list')->where(['id'=>$get_event->event_id])->update($insert_array);
			DB::table('event_schedule')->where(['id'=>$event_id])
								->update([
									'event_date' => date('Y-m-d',(strtotime($request->input('event_start_date_time')))),
									'event_date2' => date('D j M',strtotime($request->input('event_start_date_time'))),
									'event_time' => date('H:i:s',(strtotime($request->input('event_start_date_time')))),
									'end_time' => date('H:i:s',(strtotime($request->input('event_end_date_time')))),
									'event_start_date_time' =>$event_start_date_time,
									'event_end_date_time' =>$event_end_date_time 
			                     ]);      
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
								$dd=$this->send_iphone_notification($device_token,'You have received event invitation','invitation',$message);
						}   
						if($get_friend_name->device_type == 'A'){
							$message = array('sound' =>1,'message'=>'You have received event invitation',
							'notifykey'=>'invitation','data'=>'hello','title'=>'Mo-Tiv',
							'event_date'=>$get_event->event_date,'event_time'=>$get_event->event_time,'notify_count'=>$notify_count);
							$device_token=$get_friend_name->device_token;
							$this->send_android_notification($device_token,'You have received event invitation','invitation',$message);
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
				/*$event_date2=$request->input('event_date');
				$event_date=$request->input('date');*/
				$this->create_event_schedule($request->input('event_start_date_time'),$request->input('event_end_date_time'),$request->input('repeat_interval'),$get_event->event_id,$id);
			}
			#event updation notification
			$guests = DB::table('invitations')
					->leftJoin('users', 'users.id', '=', 'invitations.receiver_id')
					->where(['request_status'=>1,'sub_event_id'=>$event_id])
					->get();
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
		$user_data = $this->checkUserExist();
		$id=$user_data->id;
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
					$baseUrl = url('/');
					$baseUrl = str_replace('/public','/',$baseUrl);
					$insert_array['post_image_url'] = $baseUrl.'storage/app/'.$path;
			}else{
				$insert_array['post_image_url'] = '';
			}
			
			if(!empty($request->file('video')) && $request->input('post_media_type') == 2){
				$check_type  = $request->file('video')->getClientOriginalExtension();
				$image_name = str_random(20).'.'.$check_type;
				$path = Storage::putFileAs('public/post_media', $request->file('video'), $image_name);
				$baseUrl = url('/');
				$baseUrl = str_replace('/public','/',$baseUrl);
				$insert_array['post_video_url'] = $baseUrl.'storage/app/'.$path;
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
						//$dd=$this->send_iphone_notification($device_token,$get_reciever_name->name.' '.'post on your event','event_post',$message);
				}   
				if($get_friend_name->device_type == 'A'){
					$message = array('sound' =>1,'message'=>$get_reciever_name->name.' '.'post on your event',
					'notifykey'=>'event_post','data'=>'hello','title'=>$get_reciever_name->name,'event_id'=>(int)$request->input('event_id'),
					'event_date'=>$get_event->event_date,'event_time'=>$get_event->event_time,'event_name'=>$get_event_name->event_name,'notify_count'=>$notify_count);
					$device_token=$get_friend_name->device_token;
					//$this->send_android_notification($device_token,$get_reciever_name->name.' '.'post on your event','event_post',$message);
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
			'about_me'	=>	'sometimes|nullable',
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
			'about_me'	=> $request->input('about_me') ? $request->input('about_me') : "",
		];
		if(!empty($request->file('image'))){
				$image_name = str_random(20).'.png';
				$path = Storage::putFileAs('public/user_images', $request->file('image'), $image_name);
				$baseUrl = url('/');
				$baseUrl = str_replace('/public','/',$baseUrl);
				$insert_array['image_url'] = $baseUrl.'storage/app/'.$path;
		}
		$update_data = User::Where('id',$user_data->id)->update($insert_array);
		$update_user_detail = User::Where('id',$user_data->id)->first();
		$this->responseOk('Profile Update Successfully.',$update_user_detail);
		
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
				Mail::send('email-feedback',['url' =>$url,'text_message'=>$text_message,'user_data' => $insert_data], function ($m) use ($insert_data) {
					$m->from(env('MAIL_FROM'), 'MoTiv');
					$m->to('kd@yopmail.com','App User');
					// $m->cc('deftsofttesting786@gmail.com','App User');
					$m->subject('Feedback Email');
				});
			}catch(\Exception $ex){
				$this->responseWithError('Oops Something wrong');
				 //$this->responseOk('Contact us request submitted Successfully','');
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
			$eachEvent->music_interest_id=DB::table('music_interest')->whereIn('id',explode(',', $eachEvent->music_int_id))->get();
		}else{
			$eachEvent->music_interest_id=array();	
		}
		
		if(!empty($eachEvent->public_int_id)){
			$eachEvent->public_interest_id=DB::table('public_interest')->whereIn('id',explode(',', $eachEvent->public_int_id))->get();
		}else{
			$eachEvent->public_interest_id=array();	
		}

		$eachEvent->ticket_available_count=$this->get_tickets($eachEvent->event_id,'ticket_count');
		$eachEvent->ticket_amount=$this->get_tickets($eachEvent->event_id,'price');
	    $event_views=EventView::where(['sub_event_id'=>$request->input('event_id')])->count();
		$eachEvent->event_views=$this->event_views($id,$request->input('event_id'));
		if(!empty($event_data)){
			$this->responseOk('Each Event List',$event_data);
		}else{
			$this->responseWithError('Event list not found');
		}
	} 
	
	/**************************************eventList******************************************************/
	public function eventList(Request $request){
		$user_data = $this->checkUserExist();
		$id=$user_data->id;
		#status=>1 not aprove 2 approved
		$now = Carbon::now();
		$date=$now->toDateString();
		$match_date=date('Y-m-d H:i:s');
		$event_time = $this->is_require($request->input('event_time'),'event_time');
		$end_dt=date('Y-m-d');
		$time=date('H:i:s');
		$date_range=date('Y-m-d', strtotime("+2 week", strtotime($end_dt)));	
		$match_date_range=date('Y-m-d', strtotime("+2 week", strtotime($end_dt)));	
		$filter = $request->input('filter');

		$public_interest = $request->input('public_interest');
		$music_interest = $request->input('music_interest');
		$lat = $request->input('lat');
		$long = $request->input('long');
		$miles = $request->input('miles');
		$event_date = $request->input('event_date');

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
			->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id))
			->whereNotIn('event_schedule.user_id',$this->get_user_block_by_admin($id))
			
			->where(function ($query) use ($id,$match_date,$time,$date){
				$query->where(['event_list.user_id'=>$id]);
				$query->where(['submit_by'=>2]);
				$query->where('event_schedule.event_start_date_time','<',$match_date);
				$query->where('event_schedule.event_end_date_time','>=',$match_date);
			})->orWhere(function($query) use ($id,$date,$time,$match_date){
				$query->where(['submit_by'=>3,'status'=>2]);
				$query->where('event_schedule.event_start_date_time','<',$match_date);
				$query->where('event_schedule.event_end_date_time','>=',$match_date);
			})->orWhere(function($query) use ($invitations,$date,$time,$id,$match_date){
				$query->whereIn('event_schedule.id',$invitations);
				$query->where('event_schedule.event_start_date_time','<',$match_date);
				$query->where('event_schedule.event_end_date_time','>=',$match_date);
			})->orderBy('event_schedule.event_start_date_time','ASC')
		      ->paginate(10);   
			if(!empty($filter)){
		        $event_ids = $this->current_events($id,$match_date,$invitations,$time,$date);		
		      	return $this->event_filter($id,$public_interest,$music_interest,$lat,$long,$miles,$event_date,$event_ids);
		  	}
  
		}elseif($event_time == 'past'){

			$get_event= EventList::where(['status' =>2])
			->whereNotIn('event_list.id',$this->get_event_block_list($id))
			->leftJoin('event_schedule', 'event_schedule.event_id', '=', 'event_list.id')
			->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id))
			->whereNotIn('event_schedule.user_id',$this->get_user_block_by_admin($id))
			->where(function ($query) use ($id,$match_date,$date){
				$query->where(['event_list.user_id'=>$id]);                  //event submited by user
				$query->where(['submit_by'=>2]);
				$query->where('event_schedule.event_end_date_time','<',$match_date);
			})->orWhere(function($query)use ($id,$match_date,$date){
				$query->where(['submit_by'=>3,'status'=>2]);               //event submited by organizer
				$query->where('event_schedule.event_end_date_time','<',$match_date);
			})->orWhere(function($query) use ($invitations,$match_date,$id,$date){
				$query->whereIn('event_schedule.id',$invitations);
				$query->where(['submit_by'=>2]);                         //event submited by other but invited 
				$query->where('event_schedule.event_end_date_time','<',$match_date);
			})->orderBy('event_schedule.event_start_date_time','ASC')
		      ->paginate(10);
			  
          	if(!empty($filter)){
		       $event_ids = $this->past_events($id,$match_date,$time,$date,$invitations);		
		       return $this->event_filter($id,$public_interest,$music_interest,$lat,$long,$miles,$event_date,$event_ids);
		  	}
		}elseif($event_time == 'upcoming'){
			//die($match_date.','.$match_date_range);
			$get_event= EventList::where(['status' =>2])
			->whereNotIn('event_list.id',$this->get_event_block_list($id))
			->leftJoin('event_schedule', 'event_schedule.event_id', '=', 'event_list.id')
			->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id))
			->whereNotIn('event_schedule.user_id',$this->get_user_block_by_admin($id))
			->where(function ($query) use ($id,$date,$date_range,$match_date_range,$match_date){
				$query->where(['event_list.user_id'=>$id]);
				$query->where(['submit_by'=>2]);
				$query->where('event_schedule.event_start_date_time','>',$match_date);
				$query->whereBetween('event_schedule.event_start_date_time',[$match_date,$match_date_range]);
			})->orWhere(function($query)use ($id,$date,$date_range,$match_date_range,$match_date){
				$query->where(['submit_by'=>3,'status'=>2]);
				$query->where('event_schedule.event_start_date_time','>',$match_date);
				$query->whereBetween('event_schedule.event_start_date_time',[$match_date,$match_date_range]);
			})->orWhere(function($query) use ($invitations,$date,$date_range,$id,$match_date_range,$match_date){
				$query->whereIn('event_schedule.id',$invitations);
				$query->where(['submit_by'=>2]);
				$query->where('event_schedule.event_start_date_time','>',$match_date);
				$query->whereBetween('event_schedule.event_start_date_time',[$match_date,$match_date_range]);				
			})->orderBy('event_schedule.event_start_date_time','ASC')
		      ->paginate(10);

		    if(!empty($filter)){
	        	$event_ids= $this->upcoming_events($id,$invitations,$date,$date_range,$match_date_range,$match_date);
		        return	$da=$this->event_filter($id,$public_interest,$music_interest,$lat,$long,$miles,$event_date,$event_ids);
          	}
			  
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

		    if(!empty($filter)){
	        	$event_ids= $this->favourite_events($id,$favourites);
		        return	$da=$this->event_filter($id,$public_interest,$music_interest,$lat,$long,$miles,$event_date,$event_ids);
          	} 
                 
		}else{
			 $this->responseWithblock('Please enter a valid keyword');
		}

		foreach($get_event as $eachEvent){
			$eachEvent->postList->take(10);
			$eachEvent->post_list_count = $eachEvent->postList->count();
			
			$eachEvent->guest_count=DB::table('invitations')->where(['event_id'=>$eachEvent->event_id,'sub_event_id'=>$eachEvent->id,'request_status'=>1])->count();
			$event_music_interest_list=DB::table('event_music_interest_list')->where(['event_id'=>$eachEvent->event_id])->first();
			  
			if(!empty($eachEvent->music_int_id)){
				$eachEvent->music_interest_id=DB::table('music_interest')->whereIn('id',explode(',', $eachEvent->music_int_id))->get();
			}else{
				$eachEvent->music_interest_id=array();	
			}

			if(!empty($eachEvent->public_int_id)){
				$eachEvent->public_interest_id=DB::table('public_interest')->whereIn('id',explode(',', $eachEvent->public_int_id))->get();
			}else{
				$eachEvent->public_interest_id=array();	
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
			
			$eachEvent->ticket_available_count = $this->get_ticketss($eachEvent->event_id,'ticket_count',$eachEvent->id);
			
			$eachEvent->ticket_price=$this->get_ticketss($eachEvent->event_id,'price',$eachEvent->id);
			$eachEvent->event_views=$this->event_views($id,$eachEvent->id);
			$eachEvent->favourite_status=$this->favourite_status($id,$eachEvent->id);

		}       
		if(count($get_event) > 0){
			$this->responseOk('Event List',$get_event);
		}else{
			$this->responseWithError('No more event found');
		}
	}
//----------------------------------//
		public function eventList_new(Request $request){
		$user_data = $this->checkUserExist();
		$id=$user_data->id;
		#status=>1 not aprove 2 approved
		$now = Carbon::now();
		$date=$now->toDateString();
		$match_date=date('Y-m-d H:i:s');
		$event_time = $this->is_require($request->input('event_time'),'event_time');
		$end_dt=date('Y-m-d');
		$time=date('H:i:s');
		$date_range=date('Y-m-d', strtotime("+2 week", strtotime($end_dt)));	
		$match_date_range=date('Y-m-d', strtotime("+2 week", strtotime($end_dt)));	
		$filter = $request->input('filter');

		$public_interest = $request->input('public_interest');
		$music_interest = $request->input('music_interest');
		$lat = $request->input('lat');
		$long = $request->input('long');
		$miles = $request->input('miles');
		$event_date = $request->input('event_date');

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
			->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id))
			->whereNotIn('event_schedule.user_id',$this->get_user_block_by_admin($id))
			
			->where(function ($query) use ($id,$match_date,$time,$date){
				$query->where(['event_list.user_id'=>$id]);
				$query->where(['submit_by'=>2]);
				$query->where('event_schedule.event_start_date_time','<',$match_date);
				$query->where('event_schedule.event_end_date_time','>=',$match_date);
			})->orWhere(function($query) use ($id,$date,$time,$match_date){
				$query->where(['submit_by'=>3,'status'=>2]);
				$query->where('event_schedule.event_start_date_time','<',$match_date);
				$query->where('event_schedule.event_end_date_time','>=',$match_date);
			})->orWhere(function($query) use ($invitations,$date,$time,$id,$match_date){
				$query->whereIn('event_schedule.id',$invitations);
				$query->where('event_schedule.event_start_date_time','<',$match_date);
				$query->where('event_schedule.event_end_date_time','>=',$match_date);
			})->orderBy('event_schedule.event_start_date_time','ASC')
		      ->paginate(10);   
			if(!empty($filter)){
		        $event_ids = $this->current_events($id,$match_date,$invitations,$time,$date);		
		      	return $this->event_filter($id,$public_interest,$music_interest,$lat,$long,$miles,$event_date,$event_ids);
		  	}
  
		}elseif($event_time == 'past'){

			$get_event= EventList::where(['status' =>2])
			->whereNotIn('event_list.id',$this->get_event_block_list($id))
			->leftJoin('event_schedule', 'event_schedule.event_id', '=', 'event_list.id')
			->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id))
			->whereNotIn('event_schedule.user_id',$this->get_user_block_by_admin($id))
			->where(function ($query) use ($id,$match_date,$date){
				$query->where(['event_list.user_id'=>$id]);                  //event submited by user
				$query->where(['submit_by'=>2]);
				$query->where('event_schedule.event_end_date_time','<',$match_date);
			})->orWhere(function($query)use ($id,$match_date,$date){
				$query->where(['submit_by'=>3,'status'=>2]);               //event submited by organizer
				$query->where('event_schedule.event_end_date_time','<',$match_date);
			})->orWhere(function($query) use ($invitations,$match_date,$id,$date){
				$query->whereIn('event_schedule.id',$invitations);
				$query->where(['submit_by'=>2]);                         //event submited by other but invited 
				$query->where('event_schedule.event_end_date_time','<',$match_date);
			})->orderBy('event_schedule.event_start_date_time','ASC')
		      ->paginate(10);
			  
          	if(!empty($filter)){
		       $event_ids = $this->past_events($id,$match_date,$time,$date,$invitations);		
		       return $this->event_filter($id,$public_interest,$music_interest,$lat,$long,$miles,$event_date,$event_ids);
		  	}
		}elseif($event_time == 'upcoming'){
			//die($match_date.','.$match_date_range);
			$get_event= EventList::where(['status' =>2])
			->whereNotIn('event_list.id',$this->get_event_block_list($id))
			->leftJoin('event_schedule', 'event_schedule.event_id', '=', 'event_list.id')
			->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id))
			->whereNotIn('event_schedule.user_id',$this->get_user_block_by_admin($id))
			->where(function ($query) use ($id,$date,$date_range,$match_date_range,$match_date){
				$query->where(['event_list.user_id'=>$id]);
				$query->where(['submit_by'=>2]);
				$query->where('event_schedule.event_start_date_time','>',$match_date);
				$query->whereBetween('event_schedule.event_start_date_time',[$match_date,$match_date_range]);
			})->orWhere(function($query)use ($id,$date,$date_range,$match_date_range,$match_date){
				$query->where(['submit_by'=>3,'status'=>2]);
				$query->where('event_schedule.event_start_date_time','>',$match_date);
				$query->whereBetween('event_schedule.event_start_date_time',[$match_date,$match_date_range]);
			})->orWhere(function($query) use ($invitations,$date,$date_range,$id,$match_date_range,$match_date){
				$query->whereIn('event_schedule.id',$invitations);
				$query->where(['submit_by'=>2]);
				$query->where('event_schedule.event_start_date_time','>',$match_date);
				$query->whereBetween('event_schedule.event_start_date_time',[$match_date,$match_date_range]);				
			})->orderBy('event_schedule.event_start_date_time','ASC')
		      ->paginate(10);

		    if(!empty($filter)){
	        	$event_ids= $this->upcoming_events($id,$invitations,$date,$date_range,$match_date_range,$match_date);
		        return	$da=$this->event_filter($id,$public_interest,$music_interest,$lat,$long,$miles,$event_date,$event_ids);
          	}
			  
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

		    if(!empty($filter)){
	        	$event_ids= $this->favourite_events($id,$favourites);
		        return	$da=$this->event_filter($id,$public_interest,$music_interest,$lat,$long,$miles,$event_date,$event_ids);
          	} 
                 
		}else{
			 $this->responseWithblock('Please enter a valid keyword');
		}

		foreach($get_event as $eachEvent){
			$eachEvent->postList->take(10);
			$eachEvent->post_list_count = $eachEvent->postList->count();
			
			$eachEvent->guest_count=DB::table('invitations')->where(['event_id'=>$eachEvent->event_id,'sub_event_id'=>$eachEvent->id,'request_status'=>1])->count();
			$event_music_interest_list=DB::table('event_music_interest_list')->where(['event_id'=>$eachEvent->event_id])->first();
			  
			if(!empty($eachEvent->music_int_id)){
				$eachEvent->music_interest_id=DB::table('music_interest')->whereIn('id',explode(',', $eachEvent->music_int_id))->get();
			}else{
				$eachEvent->music_interest_id=array();	
			}

			if(!empty($eachEvent->public_int_id)){
				$eachEvent->public_interest_id=DB::table('public_interest')->whereIn('id',explode(',', $eachEvent->public_int_id))->get();
			}else{
				$eachEvent->public_interest_id=array();	
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
			
			$eachEvent->ticket_available_count=$this->get_tickets1($eachEvent->event_id,'ticket_count');
			$eachEvent->ticket_price=$this->get_tickets1($eachEvent->event_id,'price');
			$eachEvent->event_views=$this->event_views($id,$eachEvent->id);
			$eachEvent->favourite_status=$this->favourite_status($id,$eachEvent->id);

		}       
		if(count($get_event) > 0){
			$this->responseOk('Event List',$get_event);
		}else{
			$this->responseWithError('No more event found');
		}
	}
/**************************************addFavourite******************************************************/
	public function addFavourite(Request $request){
        $user_data = $this->checkUserExist();
		$id=$user_data->id;
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
        $save_data['created_at']=Date('Y-m-d H :i:s');
        FavouriteEvent::updateOrCreate(['user_id'=>$id,'sub_event_id'=>$request->event_id],$save_data);
   		return $this->responseOk('Event added to favourite successfully','');
    }
/**************************************update_ticket******************************************************/
    public function update_ticket(Request $request){
	    $user_data = $this->checkUserExist();
		$id=$user_data->id;
	    $validator = Validator::make($request->all(), [
        	'ticket_id'=>'required|exists:tickets,id',
        	'ticket_title'=>'required',
        	'ticket_description'=>'required',
        	'ticket_amount'=>'required',
        	'ticket_quantity'=>'required',
        ]);  
        if($validator->fails()){
			return $this->responseWithError($validator->errors()->first());
		}
        $update_data['ticket_title']=$request->ticket_title;
        $update_data['ticket_description']=$request->ticket_description;
        $update_data['ticket_amount']=$request->ticket_amount;
        $update_data['ticket_quantity']=$request->ticket_quantity;
        $update_data['created_at']=Date('Y-m-d H:i:s');
        DB::table('tickets')->where(['id'=>$request->ticket_id])->update($update_data); 
   		return $this->responseOk('ticket updated successfully','');
    }
/**************************************create_guest_list_name******************************************************/
    public function create_guest_list_name(Request $request){
        $user_data = $this->checkUserExist();
		$id=$user_data->id;
	    $validator = Validator::make($request->all(), [
        	'event_id'=>'required|exists:event_schedule,id',
        	'guest_list_name'=>'required',
        	
        ]);  
        if($validator->fails()){
			return $this->responseWithError($validator->errors()->first());
		}
        $get_event=DB::table('event_schedule')->where(['id'=>$request->event_id])->first();
        $save_data['user_id']=$id;
        $save_data['event_id']=$get_event->event_id;
        $save_data['sub_event_id']=$request->event_id;
        $save_data['guest_list_name']=$request->guest_list_name;
        $save_data['created_at']=Date('Y-m-d H:i:s');
        $check_guest=DB::table('guest_list_name')->insertGetId($save_data);
     	return $this->responseOk('Guest list name created successfully',$check_guest);
    }
/**************************************get_guest_list_name******************************************************/
    public function get_guest_list_name(Request $request){
        $user_data = $this->checkUserExist();
		$id=$user_data->id;
	    $validator = Validator::make($request->all(), [
        	'guest_list_name_id'=>'required|exists:guest_list_name,id',
        ]);  
        if($validator->fails()){
			return $this->responseWithError($validator->errors()->first());
		}
        $guest_list_names=DB::table('guests')->where(['guest_list_name_id'=>$request->guest_list_name_id])->get();
     	return $this->responseOk('Guest list names',$guest_list_names);
    }
/**************************************add_guest******************************************************/
   	public function add_guest(Request $request){
	    $user_data = $this->checkUserExist();
		$id=$user_data->id;
	    $validator = Validator::make($request->all(), [
        	'add_guest'=>'required|',
        	'guest_list_name_id'=>'required|exists:guest_list_name,id',
        ]);  
        if($validator->fails()){
			return $this->responseWithError($validator->errors()->first());
		}
        $add_guests =json_decode($request->add_guest,true);
		foreach($add_guests as $add_guest){
			if(empty($add_guest['full_name'])){
				return $this->responseWithError('full_name is required');	
			}
			if(empty($add_guest['email'])){
				return $this->responseWithError('email is required');	
			}
			if(empty($add_guest['organisation'])){
				return $this->responseWithError('organisation is required');	
			}
		}
    	foreach ($add_guests as $key => $add_guest) {
			$save_data['guest_list_name_id']=$request->guest_list_name_id;
			$save_data['full_name']=$add_guest['full_name'];
	        $save_data['email']=$add_guest['email'];
	        $save_data['organisation']=$add_guest['organisation'];
	        $save_data['created_at']=Date('Y-m-d H:i:s');
	        $check_guest=DB::table('guests')->where(['guest_list_name_id'=>$request->guest_list_name_id,'email'=>$add_guest['email']])->first();
	        if(!empty($check_guest)){
	           return $this->responseWithError('You have already added this guest email'.':'.$add_guest['email']);	
	        }
	          DB::table('guests')->insertGetId($save_data);
        }
        return $this->responseOk('Guest added successfully','');
    }

	/**************************************postList******************************************************/
	public function postList(Request $request){
		$user_data = $this->checkUserExist();
		$id=$user_data->id;
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
		}
		if(count($get_post) > 0){
			$this->responseOk('Post List',$get_post);
		}else{
			$this->responseWithError('No more post found');
		}
	}
	/****************************************SearchFriends****************************************************/
	public function SearchFriends(Request $request){
		$user_data = $this->checkUserExist();
		$id=$user_data->id;
		$name=$request->input('name');
		if(!empty($name)){
			$find_users = User::where('name','LIKE',"%{$name}%")->select('id','name','image_url','email')
								->where('role','=',2)->where('id','!=',$id)  
								->whereNotIn('id',$this->get_user_block_list($id))  
								->paginate(10);
		}else{
			$find_users = User::select('id','name','image_url','email')
								->where('role','=',2)
								->where('id','!=',$id)
								->whereNotIn('id',$this->get_user_block_list($id))  
								->paginate(10);
		}
		foreach($find_users as $each_friend){
			$friend_id=$each_friend->id;
			$check_request=DB::table('friends')->where(function ($query) use ($id,$friend_id){
								$query->where(['sender_id'=>$id])
								->where(['receiver_id'=>$friend_id]);
								})->orWhere(function($query) use ($id,$friend_id){
								$query->where(['sender_id'=>$friend_id])
								->where(['receiver_id'=>$id]);
								})->first();
			if(!empty($check_request)){
				if($check_request->request_status ==1){
					$each_friend->friend_status=1;
					$each_friend->request_id = 0;
				}elseif($check_request->request_status ==2){
					$each_friend->friend_status=2;
					$each_friend->request_id=$check_request->id;
				}
			}else{
				if($id ==$friend_id){
					$each_friend->friend_status=1;
					$each_friend->request_id = 0;
				}else{
					$each_friend->friend_status=3;
					$each_friend->request_id = 0;
				}
			}
		}			
		if(count($find_users) > 0){
			$this->responseOk('Friend List',$find_users);
		}else{
			$this->responseWithError('No friend found');
		}
	}
	/***************************************friendList*****************************************************/
	public function friendList(Request $request){
		$user_data = $this->checkUserExist();
		$id=$user_data->id;
		$get_friend	=Friend::where(function ($query) use ($id){
									$query->where(['sender_id'=>$id])
									->where(['request_status'=>1]);
								})->orWhere(function($query) use ($id){
									$query->where(['receiver_id'=>$id])
									->where(['request_status'=>1]);													
								})->select('id as request_id','sender_id','receiver_id')
								  ->paginate(10);
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
	/******************************************sendRequest**************************************************/
	public function sendRequest(Request $request){
		$user_data = $this->checkUserExist();
		$id=$user_data->id;
		$receiver_id=$request->input('receiver_id');
		$validator = Validator::make($request->all(),[
			'receiver_id' =>'required|numeric|exists:users,id',
			]);
		if($validator->fails()){
			$this->responseWithError($validator->errors()->first());
			exit;
		}
		if($id == $request->input('receiver_id')){
			return	$this->responseWithError('You cant send request yourself');
		}
		$get_friend_name=User::where(['id'=>$request->input('receiver_id')])->first();
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
			return	$this->responseWithError('Friend request already sent to this user');
		}
		$notify_count=$this->notification_count($get_friend_name->id);
		if($get_friend_name->device_type == 'I'){
					$message = array('sound' =>1,'message'=>'You have received friend request',
					'notifykey'=>'friend_request','data'=>'Mo-Tiv','title'=>'Mo-Tiv','friend_id'=>$id,'request_id'=>$request_id,'notify_count'=>$notify_count);
					$device_token=$get_friend_name->device_token;
					$dd=$this->send_iphone_notification($device_token,'You have received friend request','friend_request',$message);
		}   
		if($get_friend_name->device_type == 'A'){
			$message = array('sound' =>1,'message'=>'You have received friend request',
			'notifykey'=>'friend_request','data'=>'hello','title'=>'Mo-Tiv','friend_id'=>$id,'request_id'=>$request_id,'notify_count'=>$notify_count);
			$device_token=$get_friend_name->device_token;
			$this->send_android_notification($device_token,'You have received friend request','friend_request',$message);
		} 
		$data="";
		echo json_encode(['result'=>'Success','message'=>'Friend request sent to '.$get_friend_name->name.' Successfully','data'=>$data,'request_id'=>$request_id]);exit;
		
	}
	/*******************************************pendingList*************************************************/
	public function pendingList(Request $request){
		$user_data = $this->checkUserExist();
		$id=$user_data->id;
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
    /********************************************acceptDecline************************************************/ 
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
	/*****************************************commentList***************************************************/ 
	public function commentList(Request $request){
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
	/****************************************addComment****************************************************/ 
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
					//$dd=$this->send_iphone_notification($device_token,$get_reciever_name->name.' '.'Commented on your post','comment',$message);
			}   
			if($get_friend_name->device_type == 'A'){
				$message = array('sound' =>1,'message'=>$get_reciever_name->name.' '.'Commented on your post',
				'notifykey'=>'comment','data'=>'hello','title'=>$get_reciever_name->name,'post_id'=>$get_post->id,
				'event_date'=>$get_event->event_date,'event_time'=>$get_event->event_time,'notify_count'=>$notify_count);
				$device_token=$get_friend_name->device_token;
				//$this->send_android_notification($device_token,$get_reciever_name->name.' '.'Commented on your post','comment',$message);
			}
			
		}			
		$this->responseOk('Comment added successfully','');
		
	}
	/*****************************************likePost***************************************************/ 
	public function likePost(Request $request){
		$user_data = $this->checkUserExist();
		$id=$user_data->id;
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
						//$dd=$this->send_iphone_notification($device_token,$get_user->name.' '.'Liked your post','like_post',$message);
				}   
				if($get_friend_name->device_type == 'A'){
					$message = array('sound' =>1,'message'=>$get_user->name.' '.'Liked your post',
					'notifykey'=>'like_post','data'=>'hello','title'=>$get_user->name,'event_id'=>$get_post->event_id,'attend_status'=>$attend_status,
					'event_date'=>$get_event->event_date,'event_time'=>$get_event->event_time,'event_name'=>$get_event_name->event_name,'notify_count'=>$notify_count);
					$device_token=$get_friend_name->device_token;
					//$this->send_android_notification($device_token,$get_user->name.' '.'Liked your post','like_post',$message);
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
						//$dd=$this->send_iphone_notification($device_token,$get_user->name.' '.'Liked your post','like_post',$message);
				}   
				if($get_friend_name->device_type == 'A'){
					$message = array('sound' =>1,'message'=>$get_user->name.' '.'Liked your post',
					'notifykey'=>'like_post','data'=>'hello','title'=>'Mo-Tiv','event_id'=>$get_post->event_id,'attend_status'=>$attend_status,
					'event_date'=>$get_event->event_date,'event_time'=>$get_event->event_time,'event_name'=>$get_event_name->event_name,'notify_count'=>$notify_count);
					$device_token=$get_friend_name->device_token;
					//$this->send_android_notification($device_token,$get_user->name.' '.'Liked your post','like_post',$message);
				}
				
			}
			$this->responseOk('Post liked successfully','');			
		}
		
	}
	/*****************************************************cancel_request***************************************/ 
	public function cancel_request(Request $request){
		$request_id = $this->is_require($request->input('request_id'),'request_id');
		$check_request=FriendList::where(['id' =>$request_id])->first();
		if(empty($check_request)){
			return $this->responseWithError('Request not found/ cancelled by user');
		}
		$comment = FriendList::where(['id' =>$request_id])->delete();
		$this->responseOk('Request cancelled successfully','');
	}
	/***********************************************search_invite*********************************************/ 
	public function search_invite(Request $request){
		$user_data = $this->checkUserExist();
		$id=$user_data->id;
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
	/**************************************send_invitation******************************************************/
	public function send_invitation(Request $request){
		$user_data = $this->checkUserExist();
		$id=$user_data->id;
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
			DB::table('notification_list')->insert(['user_id'=>$request->input('receiver_id'),'other_user_id'=>$id,'message'=>'You have received event invitation','notification_type'=>3,'timestamp'=>round(microtime(true) * 1000),'created_at' =>Date('Y-m-d H:i:s'),'updated_at' =>Date('Y-m-d H:i:s')]);
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
	/******************************************cancel_invitation**************************************************/
	public function cancel_invitation(Request $request){
		$invitation_id = $this->is_require($request->input('invitation_id'),'invitation_id');
		$check_invitation=DB::table('invitations')->where(['id' =>$invitation_id])->first();
		if(empty($check_invitation)){
			return $this->responseWithError('Event may be deleted/ Cancelled by user');
		}
		$comment = DB::table('invitations')->where(['id' =>$invitation_id])->delete();
		$this->responseOk('Invitation cancelled successfully','');
	}
	/***************************************invitation_list*****************************************************/
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
	/***********************************************accept_decline_invitation*********************************************/
	public function accept_decline_invitation(Request $request){ 
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
	/*******************************************get_user_interest_list*************************************************/
	public function get_user_interest_list(Request $request){
 		$user_data = $this->checkUserExist();
		$id=$user_data->id;
		$get_list=DB::table('users')->where(['id' =>$id])->first();
		$music_interests =  explode(',',$get_list->music_interest);
		
		foreach($music_interests as $music_interests){
			$music_interests=DB::table('music_interest')->where(['id'=>$music_interests])->first();
		    if(!empty($music_interests)){		  
			$data[]=array('id'=>$music_interests->id,
		                'name'=>$music_interests->name,
		                'image'=>$music_interests->image);
		    } 
		}
        if(empty($data)){
			$data=array();
		}


		$public_interest =  explode(',',$get_list->public_interest);
		//print_r($public_interest);die;
		foreach($public_interest as $public_interest){
			$music_interests=DB::table('public_interest')->where(['id'=>$public_interest])->first();
			if(!empty($music_interests)){		  
				$data2[]=array('id'=>$music_interests->id,
		               'name'=>$music_interests->name,
		               'image'=>$music_interests->image);
		    } 
		}
		if(empty($data2)){
			$data2=array();
		}
        
        $record=array(
        	'music_interest_id'=>$data,
        	'public_interest_id'=>$data2,
        );

		//$ttt=$this->get_public_interest_list();	    
		//print_r($ttt);die;
		 echo json_encode(['result'=>'Success','message'=>'Interest List','data'=>$record]);exit;
	}
	/*************************************update_music_interest_list*******************************************************/
	public function update_music_interest_list(Request $request){
 		$user_data = $this->checkUserExist();
		$id=$user_data->id;
		//$music_interest_id = $request->input('music_interest_id');
		$check_invitation=DB::table('users')->where(['id'=>$id])->update(['music_interest'=>$request->input('music_interest_id')]);
		$this->responseOk('Music interest list updated successfully','');
	}
	
	/******************************get_public_interest_list**************************************************************/
	public function get_public_interest_list(Request $request){
     	$user_data = $this->checkUserExist();
		$get_list=DB::table('users')->where(['id' =>$user_data->id])->first();
		$public_interest =  explode(',',$get_list->public_interest);
		//print_r($public_interest);die;
		foreach($public_interest as $public_interest){
			$music_interests=DB::table('public_interest')->where(['id'=>$public_interest])->first();
			if(!empty($music_interests)){		  
				$data[]=array('id'=>$music_interests->id,
		               'name'=>$music_interests->name,
		               'image'=>$music_interests->image);
		    } 
		}
		if(empty($data)){
			$data=array();
		}
		echo json_encode(['result'=>'Success','message'=>'Interest List','data'=>$data]);exit;
	}
	/**********************************update_public_interest_list**********************************************************/
	public function update_public_interest_list(Request $request){
	   	$user_data = $this->checkUserExist();
		$public_interest_id = $this->is_require($request->input('public_interest_id'),'public_interest_id');
		$check_invitation=DB::table('users')->where(['id'=>$user_data->id])->update(['public_interest'=>$public_interest_id]);
		$this->responseOk('Public interest list updated successfully',''); 
		
	}
	/***********************************guest_list*********************************************************/
	public function guest_list(Request $request){
	   	$user_data = $this->checkUserExist();
		$id=$user_data->id;
		
	    $event_id = $this->is_require($request->input('event_id'),'event_id');
		$get_event=DB::table('event_schedule')->where(['id'=>$request->input('event_id')])->first();
		//dd($get_event);
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
		$user_data = $this->checkUserExist();
		$id=$user_data->id;
		
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
	public function search_event_22_2_2019(Request $request){
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
		$date = $request->input('date');
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
						$eachEvent->ticket_available_count=$this->get_tickets($eachEvent->event_id,'ticket_count');
						$eachEvent->ticket_price=$this->get_tickets($eachEvent->event_id,'price');
						$eachEvent->event_views=$this->event_views($id,$eachEvent->id);
						$eachEvent->favourite_status=$this->favourite_status($id,$eachEvent->id);
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
						$eachEvent->ticket_available_count=$this->get_tickets($eachEvent->event_id,'ticket_count');
						$eachEvent->ticket_price=$this->get_tickets($eachEvent->event_id,'price');
						$eachEvent->event_views=$this->event_views($id,$eachEvent->id);
						$eachEvent->favourite_status=$this->favourite_status($id,$eachEvent->id);
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
								$event->ticket_available_count=$this->get_tickets($event->event_id,'ticket_count');
								$event->ticket_price=$this->get_tickets($event->event_id,'price');
								$event->event_views=$this->event_views($id,$event->id);
								$event->favourite_status=$this->favourite_status($id,$event->id);
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
	public function search_event(Request $request){
		$user_data = $this->checkUserExist();
		$id=$user_data->id;
		$name = $request->input('name');
		$filter = $request->input('filter');
		$public_interest = $request->input('public_interest');
		$music_interest = $request->input('music_interest');
		$lat = $request->input('lat');
		$long = $request->input('long');
		$miles = $request->input('miles');
		$event_date = $request->input('event_date');
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
				$query->select(['event_list.*','event_schedule.*',DB::raw(($query_distance) .' AS distance')]);
				$query->where(DB::raw($query_distance),'<=',$miles)->orderBy('distance','ASC');
			}
			if(!empty($public_interest)){
				$public_interest_array= explode(',',$public_interest);
				$public_interest_event_ids=DB::table('event_public_interest_list')->whereIn('public_interest_id',$public_interest_array)->pluck('event_id');
        	   $query->whereIn('event_list.id',$public_interest_event_ids);
			}
			if(!empty($music_interest)){
				$music_interest_array= explode(',',$music_interest);
				$music_interest_ids=DB::table('event_music_interest_list')->whereIn('music_interest_id',$music_interest_array)->pluck('event_id');
			    $query->whereIn('event_list.id',$music_interest_ids);
			}
			if(!empty($event_date)){
			   $query->where('event_schedule.event_date','=',date('Y-m-d',strtotime($event_date)));
			}

			if(!empty($name)){
			   $query->where('event_name','LIKE',"%{$name}%");
			}
			$events= $query->get();
			if(count($events)>0){
				foreach($events as $eachEvent){ 
				    if(!empty($eachEvent->music_int_id)){
						$eachEvent->music_interest_id=DB::table('music_interest')->whereIn('id',explode(',', $eachEvent->music_int_id))->get();
					}else{
						$eachEvent->music_interest_id=array();	
					}

					if(!empty($eachEvent->public_int_id)){
						$eachEvent->public_interest_id=DB::table('public_interest')->whereIn('id',explode(',', $eachEvent->public_int_id))->get();
					}else{
						$eachEvent->public_interest_id=array();	
					}
					$eachEvent->ticket_available_count=$this->get_tickets($eachEvent->event_id,'ticket_count');
					$eachEvent->ticket_price=$this->get_tickets($eachEvent->event_id,'price');
					$eachEvent->event_views=$this->event_views($id,$eachEvent->id);
					$eachEvent->favourite_status=$this->favourite_status($id,$eachEvent->id);
					$data[]=$eachEvent;
				}
				 http_response_code(200);
				 echo json_encode(['result'=>'Success','message'=>'Event list','data'=>$data]);
				 exit;
			}else{
				$this->responseWithError('No More event found');
		
			}  		
	}
	
	/*********************************notification_list***********************************************************/
	public function notification_list(Request $request){  
		$user_data = $this->checkUserExist();
		$id=$user_data->id;
		
		$notifications=DB::table('notification_list')
		->where(['user_id'=>$id])
		->orWhere(['notification_type' => 3,'user_id' => 1])
		->select('message','notification_type','timestamp','other_user_id','created_at')->orderBy('id','DESC')->get();
		      
		foreach($notifications as $notification){
			if(!empty($notification->other_user_id)){
				$notification->user_detail=DB::table('users')->where(['id'=>$notification->other_user_id])->select('name','image_url','id')->first();
			}else{
				$notification->user_detail=(object)array();
			}
		}
        DB::table('notification_list')->where(['user_id'=>$id])->update(['status'=>1]); 		
		if(count($notifications)>0){
			$result=['result'=>'Success','message'=>'Notification list','data'=>$notifications];
			return response()->json($result);
		}else{
			$this->responseWithError('No Notifications');
		}  
	}
	
	/********************************************************************************************/
	
	public function create_event_doc_list(Request $request){
		$get_music_list = MusicInterest::all();
		//return $this->responseOk('Music interest List',$get_data);
		$get_public_list = PublicInterest::all();
		$dress_codes=DB::table('dress_codes')->select('id','name')->get();
		$id_proofs=DB::table('id_proofs')->select('id','name')->get();
		$data=array(
		  'music_list'=>$get_music_list,
		  'public_list'=>$get_public_list,
		  'dress_codes'=>$dress_codes,
		  'id_proofs'=>$id_proofs
		);
		$result=['result'=>'Success','message'=>'Music interest List','data'=>$data];
		return response()->json($result);
	}
	
	/********************************************************************************************/
	public function send_message(Request $request){  
		$id=$request->input('user_id');
		if(empty($id)){
			$user_data = $this->checkUserExist();
			$id=$user_data->id;
		}
		#msg_type=(1=>text,2=>image,3=>video)
		$friend_id = $this->is_require($request->input('friend_id'),'friend_id');
        $get_friend=DB::table('users')->where(['id'=>$friend_id])->first();
        if(empty($get_friend)){
			$this->responseWithError('Friend id not exist');
		}		
		$message = $this->is_require($request->input('message'),'message');	
		$msg_type = $this->is_require($request->input('msg_type'),'msg_type');	
		$check_chat_id = DB::table('chat_list')
			->where(function($query) use ($id,$friend_id){
			  $query->where('user_id',$id);
			  $query->where('friend_id',$friend_id);
			})
		->orWhere(function($query) use ($friend_id,$id){
			 $query->where('user_id',$friend_id);
			 $query->where('friend_id',$id);
			})->first();
		if(empty($check_chat_id)){
			$chat_id=DB::table('chat_list')->insertGetId(['user_id'=>$id,'friend_id'=>$friend_id,'deleted_by'=>0,'created_at'=>Date('Y-m-d H:i:s')]);
		}else{
			$chat_id=$check_chat_id->id;
		}
		DB::table('messages')->insertGetId(['sender_id'=>$id,'receiver_id'=>$friend_id,'message'=>$message,'msg_type'=>$msg_type,'chat_id'=>$chat_id,'deleted_by'=>0,'timestamp'=>round(microtime(true) * 1000),'created_at'=>Date('Y-m-d H:i:s')]);
		$sender_detail=DB::table('users')->where(['id'=>$id])->first();
		$notify_count=$this->notification_count($friend_id);
		$msg_count = $this->messages_count($friend_id);
		//print $notify_count;die;
		if($get_friend->device_type == 'I'){
					$message = array('sound' =>1,'message'=>'You have received new message',
					'notifykey'=>'chat_msg','data'=>'Mo-Tiv','title'=>'Mo-Tiv','friend_id'=>$sender_detail->id,'friend_name'=>$sender_detail->name,'notify_count'=>$notify_count,'msg_count'=>$msg_count);
					$device_token=$get_friend->device_token;
					$dd=$this->send_iphone_notification($device_token,'You have received new message','chat_msg',$message);
		}   
		if($get_friend->device_type == 'A'){
			$message = array('sound' =>1,'message'=>'You have received new message',
			'notifykey'=>'chat_msg','data'=>'Mo-Tiv','title'=>'Mo-Tiv','friend_id'=>$sender_detail->id,'friend_name'=>$sender_detail->name,'notify_count'=>$notify_count,'msg_count'=>$msg_count);
			$device_token=$get_friend->device_token;
			$this->send_android_notification($device_token,'You have received new message','chat_msg',$message);
		}
		//$data->chat_id=$chat_id;
		//$data=array('chat_id'=>$chat_id);
		$data='';
		$result=['result'=>'Success','message'=>'Message send successfully','data'=>$data];
		return response()->json($result);
	}
	/********************************************************************************************/
	public function get_chat(Request $request){  
		$user_data = $this->checkUserExist();
		$id=$user_data->id;
		
		//$chat_id = $this->is_require($request->input('chat_id'),'chat_id');
		$friend_id = $this->is_require($request->input('friend_id'),'friend_id');
		$chk_chat_id = DB::table('chat_list')
		->where(function($query) use ($id,$friend_id){
			  $query->where('user_id',$id);
			  $query->where('friend_id',$friend_id);
			})
		->orWhere(function($query) use ($friend_id,$id){
			 $query->where('user_id',$friend_id);
			 $query->where('friend_id',$id);
			})->first();  
		
		if(empty($chk_chat_id)){
			$this->responseWithError('No chat found');
		}
		//echo '<pre>';
	//	print_r($chk_chat_id);die;
		$messages=DB::table('messages')->where(['chat_id'=>$chk_chat_id->id])->select('message','sender_id','receiver_id','msg_type','image','id','timestamp')->get();
		//print_r($messages);die;
		if(count($messages)>0){
			foreach($messages as $message){
				if($message->sender_id == $id){
				  $message->other_user_detail=DB::table('users')->where(['id'=>$message->receiver_id])->select('id','name','image_url')->first();
				}else{
				   $message->other_user_detail=DB::table('users')->where(['id'=>$message->sender_id])->select('id','name','image_url')->first();
				}
				$record[]=$message;
			}
         $messages_count=DB::table('messages')->where(['receiver_id'=>$id])->update(['read_status'=>1]);			
		}
		$result=['result'=>'Success','message'=>'Message send successfully','data'=>$record];
		return response()->json($result);
	}
	/********************************************************************************************/
	public function recent_chat(Request $request){
		$id=$request->input('user_id');
		if(empty($id)){
			$user_data = $this->checkUserExist();
			$id=$user_data->id;
		}
		$get_chat_data = DB::table('chat_list')
		->where(function($query) use ($id){
		   $query->where('user_id',$id);
		})
		->orWhere(function($query) use ($id){
			$query->where('friend_id',$id);
		})->pluck('id');
			 //print_r($get_chat_data);exit;
			if(count($get_chat_data) > 0){
				$get_chat = DB::table('messages')->whereIn('chat_id',$get_chat_data)
				->whereRaw("messages.id IN (select MAX(messages.id) FROM messages WHERE deleted_by = '0' GROUP BY chat_id)")
				// ->where('deleteBy','!=',$userId)
				->select('message','sender_id','receiver_id','timestamp')
				->orderBy('id','DESC')
				->get();
				foreach($get_chat as $eachUser){
					if($eachUser->sender_id == $id){
					  $eachUser->other_user_detail=DB::table('users')->where(['id'=>$eachUser->receiver_id])->select('id','name','image_url')->first();
					}else{
					   $eachUser->other_user_detail=DB::table('users')->where(['id'=>$eachUser->sender_id])->select('id','name','image_url')->first();
					}
				}
				$messages_count=DB::table('messages')->where(['receiver_id'=>$id])->update(['read_status'=>1]);
				if(count($get_chat)>0){
					$this->responseOk('Recent chat list',$get_chat);								
				}else{
					$this->responseWithError('No recent chat found');
				}
			}else{
				$this->responseWithError('No recent chat found');
			}
		   
	}
	/********************************************add_co_admin************************************************/
	public function add_co_admin(Request $request){
		$user_data = $this->checkUserExist();
		$id=$user_data->id;
	
		$sub_event_id = $this->is_require($request->input('event_id'),'event_id');
		$admin_id = $this->is_require($request->input('admin_id'),'admin_id');
		$check_co_admin_id=DB::table('users')->where(['id'=>$admin_id])->first();
		if(empty($check_co_admin_id)){
			$this->responseWithError('This id not exist');
		}
			$get_event=DB::table('event_schedule')->where(['id'=>$sub_event_id])->first();
			if(empty($get_event)){
				$this->responseWithError('Event id not exist');
			}
			$check_event=DB::table('event_list')->where(['id'=>$get_event->event_id])->first();
			
		$check_admin_exist=DB::table('event_sub_admins')->where(['event_id'=>$get_event->event_id,'user_id'=>$check_co_admin_id->id])->first();
		if(!empty($check_admin_exist)){
			$this->responseWithError('User already co-admin of this event');
		}else{
				$get_friend_name=DB::table('users')->where(['id'=>$admin_id])->first();
				#save sub admin notification
				DB::table('notification_list')->insert(['user_id'=>$admin_id,'other_user_id'=>$id,'message'=>$check_event->event_name.':'.'You are a sub admin of this event','notification_type'=>1,'timestamp'=>round(microtime(true) * 1000),'created_at' =>Date('Y-m-d H:i:s'),'updated_at' =>Date('Y-m-d H:i:s')]);
				#***************************
				$notify_count=$this->notification_count($admin_id);
				if($get_friend_name->device_type == 'I'){
						$message = array('sound' =>1,'message'=>$check_event->event_name.':'.'You are a sub admin of this event',
						'notifykey'=>'invite_invitation','data'=>'Mo-Tiv','title'=>'Mo-Tiv',
						'event_date'=>$get_event->event_date,'event_time'=>$get_event->event_time,'notify_count'=>$notify_count);
						$device_token=$get_friend_name->device_token;
						$dd=$this->send_iphone_notification($device_token,$check_event->event_name.':'.'You are a sub admin of this event','invite_invitation',$message);
				}     
				if($get_friend_name->device_type == 'A'){
					$message = array('sound' =>1,'message'=>$check_event->event_name.':'.'You are a sub admin of this event',
					'notifykey'=>'invite_invitation','data'=>'hello','title'=>'Mo-Tiv',
					'event_date'=>$get_event->event_date,'event_time'=>$get_event->event_time,'notify_count'=>$notify_count);
					$device_token=$get_friend_name->device_token;
					$this->send_android_notification($device_token,$check_event->event_name.':'.'You are a sub admin of this event','invite_invitation',$message);
				}
			DB::table('event_sub_admins')->insert(['user_id'=>$admin_id,'event_id'=>$get_event->event_id]);
			$this->responseOk('Co-admin created successfully',''); 
		}
		
	}
	/********************************************notification_count************************************************/
	public function notification_count($user_id){
		$notification_count=DB::table('notification_list')->where(['user_id'=>$user_id,'status'=>2])->count();
		return $notification_count;
	}
	/********************************************messages_count************************************************/
	public function messages_count($user_id){
		$messages_count=DB::table('messages')->where(['receiver_id'=>$user_id,'read_status'=>2])->count();
		return $messages_count;
	}
	/********************************************badge_count************************************************/
	public function badge_count(Request $request){
		$user_data = $this->checkUserExist();
		$id=$user_data->id;
			
		$notify_count = $this->notification_count($id);
		$msg_count = $this->messages_count($id);
		$record=array(
				'notify_count'=>$notify_count,
				'msg_count'=>$msg_count
				);
		$result=['result'=>'Success','message'=>'Badge count list','data'=>$record];
		return response()->json($result);
	}
	/********************************************block_unblock_user************************************************/
	public function block_unblock_user(Request $request){
		$id=$request->input('user_id');
		$validator = Validator::make($request->all(),[
			'friend_id' =>'required|numeric|exists:users,id',
		]);
		if($validator->fails()){
			$this->responseWithError($validator->errors()->first());
			exit;
		}
		if(empty($id)){
			$user_data = $this->checkUserExist();
			$id=$user_data->id;
			if($user_data->blockStatus==1){
				$this->responseWithblock('You are blocked by admin');
			}
		} 
		$friend_id=$request->input('friend_id');
		if($friend_id == $id){
			$this->responseWithError('You cant block yourself');
		}
		$check_block=DB::table('block_users')->where(['user_id'=>$id,'friend_id'=>$friend_id])->first();
		$check_friend	=Friend::where(function ($query) use ($id,$friend_id){
												$query->where(['sender_id'=>$id])
												->where(['receiver_id'=>$friend_id]);
											})->orWhere(function($query) use ($id,$friend_id){
												$query->where(['sender_id'=>$friend_id])
												->where(['receiver_id'=>$id]);													
											})->delete();
		if(!empty($check_block)){
			$check_block=DB::table('block_users')->where(['user_id'=>$id,'friend_id'=>$friend_id])->delete();
			$this->responseOk('User unblocked successfully',''); 
		}else{
			$save['user_id']=$id;
			$save['friend_id']=$friend_id;
			$save['created_at']=Date('Y-m-d H:i:s');
			$check_block=DB::table('block_users')->where(['user_id'=>$id,'friend_id'=>$friend_id])->insert($save);
		   $this->responseOk('User blocked successfully',''); 
		
		}
		
	}
	/********************************************report_event************************************************/
	public function report_event(Request $request){
		$validator = Validator::make($request->all(),[
			'event_id' =>'required|numeric|exists:event_schedule,id',
		    'message' =>'required',
		]);
		if($validator->fails()){
			$this->responseWithError($validator->errors()->first());
			exit;
		}
		$id=$request->input('user_id');
		if(empty($id)){
			$user_data = $this->checkUserExist();
			$id=$user_data->id;
			if($user_data->blockStatus==1){
				$this->responseWithblock('You are blocked by admin');
			}
		} 
		$event_id=$request->input('event_id');
		$get_event=DB::table('event_schedule')->where(['id'=>$event_id])->first();
		$save['user_id']=$id;
		$save['event_id']=$get_event->event_id;
		$save['sub_event_id']=$event_id;
		$save['message']=$request->input('message');  
		$save['created_at']=Date('Y-m-d H:i:s');
		$check_block=DB::table('event_reports')->where(['user_id'=>$id,'sub_event_id'=>$event_id])->first();
		if(!empty($check_block)){
			$this->responseWithError('You have already reported this event');
		}else{
			$check_block=DB::table('event_reports')->where(['user_id'=>$id,'friend_id'=>$event_id])->insert($save);
			$this->responseOk('Event reported successfully',''); 
		}
	}
	/********************************************report_event************************************************/
	public function block_user_list(Request $request){
		$user_data = $this->checkUserExist();
		$id=$user_data->id;

		$users = DB::table('block_users')
					->leftJoin('users', 'users.id', '=', 'block_users.friend_id')
					->where(['block_users.user_id'=>$id])
					->select('users.id','name','image_url','email')
					->paginate(10);  
		if(count($users)>0){
			$this->responseOk('Blocked user list',$users); 
		}else{
			$this->responseWithError('No blocked user found');
		}
	}
	
	/********************************************add_card************************************************/
	public function add_card(Request $request)
	{
		$user_id = Auth::User()->id;
		$validator = Validator::make($request->all(), [
			'card_number' => 'required',
			'stripe_card_id' => 'required'
		]);
		if($validator->fails()){
			return $this->responseWithError($validator->errors()->first());
		}
		$user_data = Auth::User();
		// charge Amount
		$card_proccess = new StripeCustomClass();
       $check_card = DB::table('user_cards')->where(['id'=>$user_id])->first();
		if( $check_card > 0){
			$save_card = DB::table('user_cards')->where(['user_id' => $user_id])->first();
			$result = $card_proccess->deleteCreditCard(Auth::User()->stripe_customer_id,$user_data->stripe_card_id);
		}else{
			$save_card['created_at'] = Date('Y-m-d H:i:s');
		}
	
		$result = $card_proccess->addCreditCard(Auth::User()->stripe_customer_id,$request->stripe_card_id);
		//return $result;
		if($result['status'] == 2){
			return $this->responseWithError('Oops Something went wrong during the store card. Please try again later!'); exit;
		}
		$save_card['user_id'] = $user_id;
		$save_card['card_number'] = $request->card_number;
		$save_card['stripe_card_id'] = $result['card_id'];
		$save_card['updated_at'] = Date('Y-m-d H:i:s');
		DB::table('user_cards')->insertGetId($save_card);
		$check_card = DB::table('user_cards')->where(['id'=>$user_id])->first();
		return $this->responseOk('Card saved  successfully',$check_card);
		
	}

   /********************************************get_cards************************************************/
	public function get_cards(Request $request)
	{
		$user_data = $this->checkUserExist();
		$id=$user_data->id;
			
		$get_cards=DB::table('user_cards')->where(['user_id'=>$id])->get();
		if(count($get_cards)>0){
			$this->responseOk('Card list',$get_cards); 
		}else{
			$this->responseWithError('No card found');
		}
	}
    /********************************************get_ticket_list************************************************/
	public function get_ticket_list(Request $request)
	{
	    $user_data = $this->checkUserExist();
		$id=$user_data->id;
		$validator = Validator::make($request->all(), [
			'event_id' => 'required|exists:event_schedule,id',
		]); 
		if($validator->fails()){
			return $this->responseWithError($validator->errors()->first());
		}
		$get_event_id=DB::table('event_schedule')->where(['id'=>$request->event_id])->first(); 
		$get_tickets=DB::table('tickets')->where(['event_id'=>$get_event_id->event_id])->get();

		if(count($get_tickets)>0){

            foreach ($get_tickets as $key => $get_ticket) {
            	if($get_ticket->ticket_status == 0){
					$this->responseWithError('Tickets has been closed.');
				}
            	$bought_tickets=DB::table('bought_tickets')->where(['ticket_id'=>$get_ticket->id])->sum('quantity');
            	$get_ticket->remaining_tickets=$get_ticket->ticket_quantity-$bought_tickets;	
            }
			$this->responseOk('Ticket list',$get_tickets); 
		}else{
			$this->responseWithError('No ticket found');
		}
	}
/********************************************get_ticket_list************************************************/
	public function save_card($card_number,$token)
	{
		$user_data = $this->checkUserExist();
		$card_proccess = new StripeCustomClass();
        $check_card = DB::table('user_cards')->where(['card_number'=>$card_number])->first();
		/*if($check_card > 0){
			$save_card = DB::table('user_cards')->where(['user_id' => $user_id])->first();
			//$result = $card_proccess->deleteCreditCard(Auth::User()->stripe_customer_id,$user_data->stripe_card_id);
		}else{
			$save_card['created_at'] = Date('Y-m-d H:i:s');
		}*/
	
		$result = $card_proccess->addCreditCard($user_data->stripe_customer_id,$token);
		//dd($result);die();
		if($result['status'] == 2){
			return $this->responseWithError('Oops Something went wrong during the store card. Please try again later!'); exit;
		}
		
		$save_card['user_id'] = $user_data->id;
		$save_card['card_number'] = $card_number;
		$save_card['stripe_card_id'] = $result['card_id'];
		$save_card['updated_at'] = Date('Y-m-d H:i:s');
		UserCard::updateOrCreate(['user_id'=>$user_data->id,'stripe_card_id'=>$result['card_id']],$save_card);
		return $result;
	}
    /********************************************buy_ticket************************************************/
	public function buy_ticket_old(Request $request)
	{
		$id=$request->input('user_id');
		if(empty($id)){
			$user_data = $this->checkUserExist();
			$id=$user_data->id;
			if($user_data->blockStatus==1){
				$this->responseWithblock('You are blocked by admin');
			}
		} 
		$validator = Validator::make($request->all(), [
			'ticket_id' =>'required|exists:tickets,id',
			'quantity' =>'required|',
			'amount' =>'required|',
			'payment_type' =>['required',Rule::In([1,2])], //1=>new card,2=>saved card
			'card_id' =>'required_if:payment_type,2',
			'card_number' =>'required_if:payment_type,1',
			'token' =>'required_if:payment_type,1',
			'card_type' =>['required',Rule::In([1,2])], //1=>debit,2=>credit
			'is_save' =>['required',Rule::In([1,2])],  //1=>yes,2=>no
		]);  
		if($validator->fails()){
			return $this->responseWithError($validator->errors()->first());
		}
        $amount=$request->amount;
        $token = !empty($request->input('token')) ? $request->input('token') : '';
        $card_id = !empty($request->input('card_id')) ? $request->input('card_id') : '';
        $card_number = !empty($request->input('card_number')) ? $request->input('card_number') : '';
        $payment_type=$request->payment_type;
      	$create_customer = new StripeCustomClass();
		if($request->is_save ==1){
        	$result=$this->save_card($card_number,$token); //if user save new card then payment type will 2 and send card id
            $card_id=$result['card_id'];
        	$payment_type=2; 
        }
      	$result = $create_customer->createCreditCardPayment($user_data->stripe_customer_id,$token,$amount,$payment_type,$user_data->email,'event_id',$card_id,$country='INR');
		if($result['status'] == 2){
			return $this->responseWithError('Oops Something went wrong during the payment. Please try again later!'); exit;
		}
		$save_data['user_id'] =$id;
		$save_data['ticket_id'] =$request->ticket_id;
		$save_data['quantity'] =$request->quantity;
		$save_data['amount'] =$request->amount;
		$save_data['transacation_id'] =$result['transacation_id'];
		$save_data['created_at'] =Date('Y-m-d H:i:s');
		DB::table('bought_tickets')->insertGetId($save_data);
		return $this->responseWithError('Ticket Bought successfully');

	}

	public function buy_ticket(Request $request)
	{
		$user_data = $this->checkUserExist();
		$id=$user_data->id;
	
		


		$validator = Validator::make($request->all(), [
			'event_id' =>'required|exists:event_schedule,id',
			'total_amount' =>'required|',
			'payment_type' =>['required',Rule::In([1,2])], //1=>new card,2=>saved card
			'card_id' =>'required_if:payment_type,2',
			'card_number' =>'required_if:payment_type,1',
			'token' =>'required_if:payment_type,1',
			'card_type' =>['required',Rule::In([1,2])], //1=>debit,2=>credit
			'is_save' =>['required',Rule::In([1,2])],  //1=>yes,2=>no
		]);  
		if($validator->fails()){
			return $this->responseWithError($validator->errors()->first());
		}
		$get_event=DB::table('event_schedule')->where(['id'=>$request->event_id])->first();
		$tickets=json_decode($request->tickets);
		foreach ($tickets as $key => $ticket) {
		    if(empty($ticket->ticket_id)){
				return $this->responseWithError('Please enter a ticket_id');
			}
			if(empty($ticket->quantity)){
				return $this->responseWithError('Please enter a quantity');
			}
			if(empty($ticket->amount)){
				return $this->responseWithError('Please enter a amount');
			}
		    $check_ticket=DB::table('tickets')->where(['id'=>$ticket->ticket_id,'event_id'=>$get_event->event_id])->first(); 		     
            if(empty($check_ticket)){
            	$this->responseWithError("You have entered wrong or other event's ticket id");
            }else if($check_ticket->ticket_status == 0){
            	$this->responseWithError("Tickets has been closed for this event.");	
            }
		}

        $amount=$request->amount;
        $token = !empty($request->input('token')) ? $request->input('token') : '';
        $card_id = !empty($request->input('card_id')) ? $request->input('card_id') : '';
        $card_number = !empty($request->input('card_number')) ? $request->input('card_number') : '';
        $payment_type=$request->payment_type;

        foreach ($tickets as $key => $ticket) {
        	//echo $ticket->quantity; die;
        	$get_ticket_info=DB::table('tickets')->where('id','=',$ticket->ticket_id)->first();
        	//dd($get_ticket_info->event_id);die;
        	$bought_quantity=DB::table('bought_tickets')->where('id','=',$ticket->ticket_id)->sum('quantity');
			$total_tickets=$bought_quantity+$ticket->quantity;
			//if($total_tickets > $ticket->quantity)
			$get_tickets=DB::table('tickets')->where(['event_id'=>$get_ticket_info->event_id])->first();
			//dd($get_tickets->ticket_quantity);die;
        	if($get_ticket_info->ticket_quantity > $total_tickets && $get_tickets->ticket_quantity < $ticket->quantity){
        		return	$this->responseWithError("'$get_ticket_info->ticket_title'".' '.'ticket quantity over/No more tickets available.');		
        	}
	        $save_data['user_id'] =$id;
	        $save_data['sub_event_id'] = $request->event_id; 
	        $save_data['event_id'] = $get_event->event_id;	
			$save_data['ticket_id'] = $ticket->ticket_id;
			$save_data['quantity'] = $ticket->quantity;
			$save_data['amount'] = $ticket->amount;
			$save_data['created_at'] =Date('Y-m-d H:i:s');
			$last_id=DB::table('bought_tickets')->insertGetId($save_data);
			$image_name=time();
	        //$qr_data = $this->encrypt_fucntion($last_id,'e');
			$qr_image= QrCode::format('png')->size(400)->encoding('UTF-8')->generate($last_id, './qr_image/'.$image_name.".".'png');	
			$get_path=url('qr_image/'.$image_name.'.png');
	      	DB::table('bought_tickets')->where(['id'=>$last_id])->update(['qr_image'=>$get_path]);
		}

        
      	/*$create_customer = new StripeCustomClass();
		if($request->is_save ==1){
        	$result=$this->save_card($card_number,$token); //if user save new card then payment type will 2 and send card id
            $card_id=$result['card_id'];
        	$payment_type=2; 
        }
      	$result = $create_customer->createCreditCardPayment($user_data->stripe_customer_id,$token,$amount,$payment_type,$user_data->email,'event_id',$card_id,$country='INR');
		if($result['status'] == 2){
			return $this->responseWithError('Oops Something went wrong during the payment. Please try again later!'); exit;
		}*/

		    $save_transaction['user_id'] =$id;
	        $save_transaction['sub_event_id'] =$request->event_id;
	        $save_transaction['event_id'] =$get_event->event_id;
			$save_transaction['transaction_id'] ='gdgdgdsgsdg';
			$save_transaction['created_at'] =Date('Y-m-d H:i:s');
			DB::table('ticket_transactions')->insertGetId($save_transaction);
		
		return $this->responseOk('Ticket Bought successfully','');

	}
 	/********************************************my_tickets************************************************/
	public function my_tickets(Request $request)
	{
		$user_data = $this->checkUserExist();
		$id=$user_data->id;
		
        $bought_tickets=  DB::table('bought_tickets')
        		->where(['user_id'=>$id])
        		->distinct('sub_event_id')
        		->pluck('sub_event_id');
    			
		$get_events= EventList::
				leftJoin('event_schedule', 'event_schedule.event_id', '=', 'event_list.id')
				->where(['status' =>2])
				->whereIn('event_schedule.id',$bought_tickets)
				->orderBy('event_schedule.event_start_date_time','ASC')
			    ->get(); 
		if(count($get_events)>0){	 

				foreach ($get_events as $key => $get_event) {
			    	
			        $get_event->my_tickets=DB::table('tickets')
					        	->leftJoin('bought_tickets','bought_tickets.ticket_id','=','tickets.id')
					        	->where(['bought_tickets.sub_event_id'=>$get_event->id,
					        			'bought_tickets.user_id'=>$id
					        			])
					        	->get();	

			         
			    }	    
		}	    
		return $this->responseOk('Event list',$get_events);
	}
	/********************************************scan_ticket************************************************/
	public function scan_ticket(Request $request){

		$user_data = $this->checkUserExist();
		$id=$user_data->id;
		$validator = Validator::make($request->all(), [
			'event_id' =>'required|exists:event_schedule,id',
			'ticket_code' =>'required',
		]);  
		if($validator->fails()){
			return $this->responseWithError($validator->errors()->first());
		}
	   $code=$request->ticket_code;	
	  
		$get_event=DB::table('event_schedule')->where('id','=',$request->event_id)->first();
	       $check_ticket_id=DB::table('bought_tickets')->where('id',$code)->first(); 
	      // echo "<pre>";print_r($check_ticket_id);die;
	    if(empty($check_ticket_id)){
         	return $this->responseWithError('ticket id not found');
        }
	    $check_ticket=DB::table('check_in_tickets')->where('qr_id','=',$code)->first(); 
       // dd($check_ticket);
        if(!empty($check_ticket)){
         	return $this->responseWithError('This ticket has been already scanned');
        }else{
         	$save_ticket['user_id']=$id;
         	$save_ticket['ticket_id']=$check_ticket_id->ticket_id;
         	$save_ticket['qr_id']=$code;
         	$save_ticket['event_id']=$get_event->event_id;
         	$save_ticket['sub_event_id']=$request->event_id;
         	$save_ticket['created_at']=date('Y-m-d H:i');
         	DB::table('check_in_tickets')->insertGetId($save_ticket);
         	return $this->responseOk('Ticket scanned successfully.','');
        }

	}


	/********************************************delete_guest************************************************/
	public function delete_guest(Request $request)
	{
		$user_data = $this->checkUserExist();
		$id=$user_data->id;

		$validator = Validator::make($request->all(), [
			'guest_id' =>'required|exists:guests,id',
		]);  
		if($validator->fails()){
			return $this->responseWithError($validator->errors()->first());
		}

		DB::table('guests')->where(['id'=>$request->guest_id])->delete();
		return $this->responseOk('Guest has been deleted successfully.','');
	}
	/********************************************check_ins************************************************/

	public function close_sale(Request $request)
	{
		$user_data = $this->checkUserExist();
		$id=$user_data->id;

		$validator = Validator::make($request->all(), [
			'event_id' =>'required|exists:event_schedule,id',
		]);  
		if($validator->fails()){
			return $this->responseWithError($validator->errors()->first());
		}

		$get_event=DB::table('event_schedule')->where(['id'=>$request->event_id])->first();
		DB::table('tickets')->where(['event_id'=>$get_event->event_id])->update(['ticket_status'=>0]);
		return $this->responseOk('Tikcets has been closed successfully.','');
	}

	/********************************************check_ins************************************************/
	public function check_ins(Request $request)
	{
		
		$user_data = $this->checkUserExist();
		$id=$user_data->id;
		$validator = Validator::make($request->all(), [
			'event_id' =>'required|exists:event_schedule,id',
			'type' =>'required|in:1,2',                          //1=>check in,2=>guest
		]);  
		if($validator->fails()){
			return $this->responseWithError($validator->errors()->first());
		}
		if($request->type == 1){
            $get_check_ins=User::leftJoin('bought_tickets','bought_tickets.user_id','users.id')
       		->leftJoin('tickets','tickets.id','bought_tickets.ticket_id')
            ->where(['tickets.event_id'=>$request->event_id])
            ->select('user_id','user_name','ticket_id','ticket_title','ticket_description')
            ->get();
             
            $get_check_ins_list= array('total_check_ins'=>0,'total_revenue'=>0,'check_in_users'=>$get_check_ins);

		}else{
			 $guest_list_name=DB::table('guest_list_name')->where(['sub_event_id'=>$request->event_id])->get();
			 //dd( $guest_list_name);
			 if(count($guest_list_name)>0){
			 	foreach ($guest_list_name as $each_guest_list) {
			 		//dd($guest_list_name->id);
			 		$each_guest_list->guest_list=DB::table('guests')->where(['guest_list_name_id'=>$each_guest_list->id])->get();
			 	
			 	}
			 }
       		
            $get_check_ins_list= array('total_check_ins'=>0,'total_revenue'=>0,'guest_list'=>$guest_list_name);
		}
		return $this->responseOk('Check In list',$get_check_ins_list);
	}


	public function check_in_events(Request $request)
	{
		$user_data = $this->checkUserExist();
		$id=$user_data->id;
	
	    $bought_tickets=DB::table('bought_tickets')
            ->distinct('sub_event_id')
            ->pluck('sub_event_id');
	    
	    $get_event= EventList::
			    leftJoin('event_schedule','event_schedule.event_id', '=', 'event_list.id')
		        ->whereIn('event_schedule.id',$bought_tickets)
		        ->where(['event_schedule.user_id'=>$id])
			    ->paginate(10);   
	  
		foreach($get_event as $eachEvent){
			$eachEvent->postList->take(10);
			$eachEvent->post_list_count = $eachEvent->postList->count();
			
			$eachEvent->guest_count=DB::table('invitations')->where(['event_id'=>$eachEvent->event_id,'sub_event_id'=>$eachEvent->id,'request_status'=>1])->count();
			$event_music_interest_list=DB::table('event_music_interest_list')->where(['event_id'=>$eachEvent->event_id])->first();
			  
			if(!empty($eachEvent->music_int_id)){
				$eachEvent->music_interest_id=DB::table('music_interest')->whereIn('id',explode(',', $eachEvent->music_int_id))->get();
			}else{
				$eachEvent->music_interest_id=array();	
			}

			if(!empty($eachEvent->public_int_id)){
				$eachEvent->public_interest_id=DB::table('public_interest')->whereIn('id',explode(',', $eachEvent->public_int_id))->get();
			}else{
				$eachEvent->public_interest_id=array();	
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
			$eachEvent->event_views=$this->event_views($id,$eachEvent->id);
			$eachEvent->favourite_status=$this->favourite_status($id,$eachEvent->id);
			$eachEvent->ticket_sold_count=$this->get_sold_tickets($eachEvent->id);
	
		} 
		return $this->responseOk('Check In events',$get_event);
	}


   public function dashboard(Request $request)
	{
		$user_data = $this->checkUserExist();
		$id=$user_data->id;
	    $validator = Validator::make($request->all(), [
			'event_id' =>'required|exists:event_schedule,id',
			'type' =>'required|in:1,2,3,4',       // 1=>hourly,2=>daily,3=>weekly,4=>monthly
			'chart_type' =>'required|in:1,2',     // 1=>sales_chart,2=>check_in_summary
		]);  
		if($validator->fails()){
			return $this->responseWithError($validator->errors()->first());
		}
       
        if($request->chart_type == 2){
       	 	return	$this->check_in_summary($request);
        }


       // $request->event_id=285;
        $get_event=DB::table('event_schedule')->where(['id'=>$request->event_id])->first();
        $total_sales=DB::table('bought_tickets')->where(['sub_event_id'=>$request->event_id])->sum('amount');
        if(empty($total_sales)){
        	return $this->responseWithError('No ticket found');
        }      
        $total_tickets=DB::table('tickets')->where(['event_id'=>$get_event->event_id])->sum('ticket_quantity');
        $total_bought_tickets=DB::table('bought_tickets')->where(['sub_event_id'=>$request->event_id])->sum('quantity');
        $purchased=$this->get_sold_tickets($request->event_id);
        $last_date=DB::table('bought_tickets')->where(['sub_event_id'=>$request->event_id])->orderBy('id','Desc')->first();
		$first_date=DB::table('bought_tickets')->where(['sub_event_id'=>$request->event_id])->first();
		
		$star_date=$last_date->created_at;
		$current_date = date('Y-m-d H:00',strtotime($first_date->created_at));
	
		if($request->type == 1){
			$diff = round((strtotime($star_date) - strtotime($current_date))/3600, 1);    
    		$increment = '+1 hour';
    		$decrement = '-1 hour';
    		$type = 'hour';
	    }else if ($request->type == 2) {
	    	$diff = ceil(abs(strtotime($star_date) - strtotime($current_date)) / 86400);
			$increment = '+1 day';
			$decrement = '-1 day';
			$type = 'day';	
		}else if ($request->type == 3) {
			$diff = ceil(abs(strtotime($star_date) - strtotime($current_date)) / 86400);
            $diff= floor($diff/7);
			$increment = '+1 week';
			$decrement = '-1 week';
			$type = 'week';			
		}else{
			$date1 = new DateTime($current_date);
			$date2 = $date1->diff(new DateTime($star_date));
			$diff = $date2->m;
			$increment = '+1 month';			
			$decrement = '-1 month';	
			$type = 'month';	
		}
		
	   
	   	for ($i=0; $i <= $diff; $i++){
	    	$end_date=date('Y-m-d H:i',strtotime($increment,strtotime($current_date)));
        	$get_tickets=DB::table('bought_tickets')
			->where('sub_event_id','=',$request->event_id)
			->where('created_at','>=',$current_date)
			//->where('created_at','<=',$end_date)
			->addSelect([DB::raw('Date(created_at) as date'),DB::raw('SUM(quantity) as bought_tickets')])
			->groupBy('date')
			->first();
			if(!empty($get_tickets->bought_tickets)){
				$bought	= $get_tickets->bought_tickets;
				$tickets[]=$bought;
    		}else{
				$bought = 0;
				$tickets[]=$bought;
			}
			$current_date=date('Y-m-d H:i',strtotime($increment,strtotime($current_date)));
        	$date_time=date('Y-m-d H:i',strtotime($decrement,strtotime($current_date)));
        	$customers[]=array(
				"date_time"=>$date_time,
				 $type=>$i,
  				"tickets"=>$total_tickets- array_sum($tickets),
  				"bought"=>$bought,

			);

        }
        $tickets_lists=DB::table('tickets')
			->where(['event_id'=>$get_event->event_id])
			->select('id','ticket_title','ticket_quantity')
			->get();
		foreach ($tickets_lists as $key => $tickets_list) {
			$tickets_list	=DB::table('bought_tickets')
				->where('ticket_id','=',$tickets_list->id)
				->groupBy('ticket_id')
				->sum('quantity');
			}
        $data=[
            'gross_sales'=>$total_sales,  
            'total_tickets'=>$total_tickets,  
            'total_bought_tickets'=>$total_bought_tickets, 
            'tickets_list'=>$tickets_lists,
			'record'=>$customers
       	];
	    return $this->responseOk('Ticket summary',$data);
  	}

    	
  	 public function Check_in_summary($request)
	{
		$user_data = $this->checkUserExist();
		$id=$user_data->id;
	    $validator = Validator::make($request->all(), [
			'event_id' =>'required|exists:event_schedule,id',
			'type' =>'required|in:1,2,3,4',       // 1=>hourly,2=>daily,3=>weekly,4=>monthly
			'chart_type' =>'required|in:1,2',     // 1=>sales_chart,2=>check_in_summary
		]);  
		if($validator->fails()){
			return $this->responseWithError($validator->errors()->first());
		}
        $get_event=DB::table('event_schedule')->where(['id'=>$request->event_id])->first();
        $total_sales=DB::table('bought_tickets')->where(['sub_event_id'=>$request->event_id])->sum('amount');
        if(empty($total_sales)){
        	return $this->responseWithError('No ticket found');
        }      
        $total_tickets=DB::table('tickets')->where(['event_id'=>$get_event->event_id])->sum('ticket_quantity');
        //$total_bought_tickets=DB::table('bought_tickets')->where(['sub_event_id'=>$request->event_id])->sum('quantity');
//        $purchased=$this->get_sold_tickets($request->event_id);

        $total_bought_tickets=DB::table('check_in_tickets')->where(['sub_event_id'=>$request->event_id])->count();
		 if($total_bought_tickets == 0){
        	return $this->responseWithError('No CheckIn found');
        }   

        $last_date=DB::table('check_in_tickets')->where(['sub_event_id'=>$request->event_id])->orderBy('id','Desc')->first();
		$first_date=DB::table('check_in_tickets')->where(['sub_event_id'=>$request->event_id])->first();
		
		$star_date=$last_date->created_at;
		$current_date = date('Y-m-d H:00',strtotime($first_date->created_at));
	
		if($request->type == 1){
			$diff = round((strtotime($star_date) - strtotime($current_date))/3600, 1);    
    		$increment = '+1 hour';
    		$decrement = '-1 hour';
    		$type = 'hour';
	    }else if ($request->type == 2) {
	    	$diff = ceil(abs(strtotime($star_date) - strtotime($current_date)) / 86400);
			$increment = '+1 day';
			$decrement = '-1 day';
			$type = 'day';	
		}else if ($request->type == 3) {
			$diff = ceil(abs(strtotime($star_date) - strtotime($current_date)) / 86400);
            $diff= floor($diff/7);
			$increment = '+1 week';
			$decrement = '-1 week';
			$type = 'week';			
		}else{
			$date1 = new DateTime($current_date);
			$date2 = $date1->diff(new DateTime($star_date));
			$diff = $date2->m;
			$increment = '+1 month';			
			$decrement = '-1 month';	
			$type = 'month';	
		}
		
	    	//die('kkk');
	   	for ($i=0; $i <= $diff; $i++){
	    	$end_date=date('Y-m-d H:i',strtotime($increment,strtotime($current_date)));
        	$get_tickets=DB::table('check_in_tickets')
			->where('sub_event_id','=',$request->event_id)
			->where('created_at','>=',$current_date)
			->where('created_at','<=',$end_date)
			->addSelect([DB::raw('Date(created_at) as date'),DB::raw('Count(ticket_id) as bought_tickets')])
			->groupBy('date')
			->first();
			if(!empty($get_tickets->bought_tickets)){
				$bought	= $get_tickets->bought_tickets;
				$tickets[]=$bought;
    		}else{
				$bought = 0;
				$tickets[]=$bought;
			}
			$current_date=date('Y-m-d H:i',strtotime($increment,strtotime($current_date)));
        	$date_time=date('Y-m-d H:i',strtotime($decrement,strtotime($current_date)));
        	$customers[]=array(
				"date_time"=>$date_time,
				 $type=>$i,
  				"tickets"=>$total_tickets- array_sum($tickets),
  				"bought"=>$bought,

			);

        }
        $tickets_lists=DB::table('tickets')
			->where(['event_id'=>$get_event->event_id])
			->select('id','ticket_title','ticket_quantity')
			->get();
		foreach ($tickets_lists as $key => $tickets_list) {
			$tickets_list->bought_tickets=DB::table('check_in_tickets')
				->where('ticket_id','=',$tickets_list->id)
				->groupBy('ticket_id')
				->count();
			}
        $data=[
            'gross_sales'=>$total_sales,  
            'total_tickets'=>$total_tickets,  
            'total_bought_tickets'=>$total_bought_tickets,  
            'tickets_list'=>$tickets_lists,
			'record'=>$customers
       	];
	    return $this->responseOk('Check in summary',$data);
  	}
  	/* public function Check_in_summary($request)
	{
		$user_data = $this->checkUserExist();
		$id=$user_data->id;
	    $validator = Validator::make($request->all(), [
			'event_id' =>'required|exists:event_schedule,id',
			'type' =>'required|in:1,2,3,4',       // 1=>hourly,2=>daily,3=>weekly,4=>monthly
			'chart_type' =>'required|in:1,2',     // 1=>sales_chart,2=>check_in_summary
		]);  
		if($validator->fails()){
			return $this->responseWithError($validator->errors()->first());
		}
        $get_event=DB::table('event_schedule')->where(['id'=>$request->event_id])->first();
        $total_sales=DB::table('bought_tickets')->where(['sub_event_id'=>$request->event_id])->sum('amount');
        if(empty($total_sales)){
        	return $this->responseWithError('No ticket found');
        }      
        $total_tickets=DB::table('tickets')->where(['event_id'=>$get_event->event_id])->sum('ticket_quantity');
        $total_bought_tickets=DB::table('bought_tickets')->where(['sub_event_id'=>$request->event_id])->sum('quantity');
        $purchased=$this->get_sold_tickets($request->event_id);

        $last_date=DB::table('bought_tickets')->where(['sub_event_id'=>$request->event_id])->orderBy('id','Desc')->first();
		$first_date=DB::table('bought_tickets')->where(['sub_event_id'=>$request->event_id])->first();
		
		$star_date=$last_date->created_at;
		$current_date = date('Y-m-d H:00',strtotime($first_date->created_at));
	
		if($request->type == 1){
			$diff = round((strtotime($star_date) - strtotime($current_date))/3600, 1);    
    		$increment = '+1 hour';
    		$decrement = '-1 hour';
    		$type = 'hour';
	    }else if ($request->type == 2) {
	    	$diff = ceil(abs(strtotime($star_date) - strtotime($current_date)) / 86400);
			$increment = '+1 day';
			$decrement = '-1 day';
			$type = 'day';	
		}else if ($request->type == 3) {
			$diff = ceil(abs(strtotime($star_date) - strtotime($current_date)) / 86400);
            $diff= floor($diff/7);
			$increment = '+1 week';
			$decrement = '-1 week';
			$type = 'week';			
		}else{
			$date1 = new DateTime($current_date);
			$date2 = $date1->diff(new DateTime($star_date));
			$diff = $date2->m;
			$increment = '+1 month';			
			$decrement = '-1 month';	
			$type = 'month';	
		}
		
	    	
	   	for ($i=0; $i <= $diff; $i++){
	    	$end_date=date('Y-m-d H:i',strtotime($increment,strtotime($current_date)));
        	$get_tickets=DB::table('bought_tickets')
			->where('sub_event_id','=',$request->event_id)
			->where('created_at','>=',$current_date)
			->where('created_at','<=',$end_date)
			->addSelect([DB::raw('Date(created_at) as date'),DB::raw('SUM(quantity) as bought_tickets')])
			->groupBy('date')
			->first();
			if(!empty($get_tickets->bought_tickets)){
				$bought	= $get_tickets->bought_tickets;
				$tickets[]=$bought;
    		}else{
				$bought = 0;
				$tickets[]=$bought;
			}
			$current_date=date('Y-m-d H:i',strtotime($increment,strtotime($current_date)));
        	$date_time=date('Y-m-d H:i',strtotime($decrement,strtotime($current_date)));
        	$customers[]=array(
				"date_time"=>$date_time,
				 $type=>$i,
  				"tickets"=>$total_tickets- array_sum($tickets),
  				"bought"=>$bought,

			);

        }
        $tickets_lists=DB::table('tickets')
			->where(['event_id'=>$get_event->event_id])
			->select('id','ticket_title','ticket_quantity')
			->get();
		foreach ($tickets_lists as $key => $tickets_list) {
			$tickets_list->bought_tickets=DB::table('bought_tickets')
				->where('ticket_id','=',$tickets_list->id)
				->groupBy('ticket_id')
				->sum('quantity');
			}
        $data=[
            'gross_sales'=>$total_sales,  
            'total_tickets'=>$total_tickets,  
            'total_bought_tickets'=>$$total_bought_tickets,  
            'tickets_list'=>$tickets_lists,
			'record'=>$customers
       	];
	    return $this->responseOk('Check in summary',$data);
  	}*/




	
}
