<?php

namespace App\Http\Controllers\Website;

header('Cache-Control: no-store, private, no-cache, must-revalidate');
header('Cache-Control: pre-check=0, post-check=0, max-age=0, max-stale = 0', false);

require app_path('facebook-sdk-v5/autoload.php');
use Facebook;
use Redirect;
use DB;
use Auth;
use Hash;
use Session;
use \Crypt;
use App\User;
use Carbon\Carbon;
Use App\Models\Like;
Use App\Models\ContactUs;
Use App\Models\Friend;
Use App\Models\FriendList;
Use App\Models\MusicInterest;
Use App\Models\PublicInterest; 
Use App\Models\UserPublicInterest;
Use App\Models\UserMusicInterest;
use GuzzleHttp\Client;
use GuzzleHttp;
use App\Mail\WebsiteForgotPassword;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Exception\GuzzleException;
use App\Http\Controllers\ResponseController;
use Illuminate\Support\Facades\Input;
use DateTime;

class ProfileController extends ResponseController
{
    public function register(Request $request) {
        if($request->isMethod('get')) {
           $music_interest = MusicInterest::all();
           $public_interest = PublicInterest::all();
    	   return view('website.register',compact('music_interest','public_interest'));      
        }

        if($request->isMethod('post')) {
            $errors = [
                'firstname.required'=>  'Please enter first name.',
                'lastname.required' =>  'Please enter last name.',
                'user_name.required'=>  'Please enter username.',
                'user_name.unique'  =>  'Username has already been taken.',
                'email.required'    =>  'Please enter email address.',
                'password.required' =>  'Please enter password.',
                'confirm_password.required' =>  'Please enter confirm password.',
                'email.email'       =>  'Please enter valid email address.',
                'email.exists'      =>  'Please enter registered email address.',
                'question.required' =>  'Please select question.',
                'dob.required'      =>  'Please enter date of birth.',
                'user_type.required'=>  'Please select user type.',
                'image.required'    =>  'Please upload the profile picture.',
                'phone_number.digits_between' => 'Phone number must be number and between 8 and 15 digits.',
                'confirm_password.same'  => 'Password and confirm password must be same.',
                'about.max'             => 'About you cannot be greater than 120 characters.',
                'about.required'        => 'The about you field is required.',
                'firstname.regex'    => 'Firstname should be character.',
                'lastname.regex'    => 'Lastname should be character.',
                'about.regex'       => 'The About you should be character.',
                'privacy.required'       => 'Please select privacy policy.',
                'user_name.regex'       => 'Please enter a valid username.',
                'phone_number.regex' => 'Please enter a valid phone number.',
                'phone_number.max' => 'Please enter a valid phone number.',
                'phone_number.min' => 'Please enter a valid phone number.',

             ];     

           $this->validate($request,[
                'firstname' => 'required|regex:/^[a-zA-Z ]*$/u|max:50',
                'lastname'  => 'required|regex:/^[a-zA-Z ]*$/u|max:50',
                'user_name'  => 'required|unique:users|regex:/^\S*$/u|max:50',
                'email'     => 'required|unique:users|email',
                'password'  => 'required|min:6',
                'confirm_password'  => 'required|required_with:password|same:password',
                'phone_number' => 'nullable|regex:/^[+0-9]+$/|max:19|min:10',
                'dob'      => 'required',
                'privacy' => 'required',
                // 'answer'   => 'required',
                // 'about'    => 'required|regex:/^[\pL\s\-]+$/u|max:120',
                // 'image'    => 'required' 
    
            ],$errors);
            // $request->all();
            $user = new User();
            $user->name = $request->firstname.' '.$request->lastname;
            // $user->lastname  = $request->lastname;
            $user->user_name  = $request->user_name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->visibsle_pwd = $request->password;
            $user->email_verification_token = Crypt::encrypt(str_random(5));

            $dob = DateTime::createFromFormat('Y/m/d', $request->dob);
            $age = Carbon::parse($dob)->age;
            $user->role = 2;
            $user->age = $age;
            $user->phone_number = $request->phone_number;
            $user->about_me = $request->about;
            // $user->refferal_code=$this->refferal_num($insert_user);
            // $user->question = $request->question;
            // $user->answer = $request->answer;
            // return $request->file('image');
            


            if(!empty($request->file('image'))){
                $image_name = str_random(20).'.png';
                $path = Storage::putFileAs('public/user_images', $request->file('image'), $image_name);
                $baseUrl = url('/');
                $baseUrl = str_replace('/public','/',$baseUrl);
                $img_url = $baseUrl.'/storage/app/'.$path;
                $user->image_url = $img_url;
            }

            $user->save();

            $size=3;
            $alpha_key = $user->id;
            $keys = range('A', 'Z');
            for ($i = 0; $i < 2; $i++) {
                $alpha_key .= $keys[array_rand($keys)];
            }
            $length = $size - 2;
            $key = '';
            $keys = range(0, 9);
            for ($i = 0; $i < $length; $i++) {
                $key .= $keys[array_rand($keys)];
            }


            $user = User::find($user->id);
            $user->refferal_code= $alpha_key . $key;

            $public_interest = $request->public;
           
           
            if($public_interest && !empty($public_interest)) {
                $public = count($public_interest);
                if($public > 0 && !empty(@$public_interest[0])){
                    for($j=0;$j< $public;$j++){
                        if(is_numeric($public_interest[$j])){
                            UserPublicInterest::insertGetId(['public_interest_id' => $public_interest[$j],'user_id' => $user->id,'created_at' => Date('Y-m-d H:i:s')]);
                        }
                    }
                }
            }


            $music_interest = $request->music;

            if($music_interest && !empty($music_interest)) {
                $music = count($music_interest);
                if($music > 0){
                    for($k=0;$k<$music;$k++){
                        if(is_numeric($music_interest[$k])){
                            UserMusicInterest::insertGetId(['music_interest_id' => $music_interest[$k],'user_id' => $user->id,'created_at' => Date('Y-m-d H:i:s')]);
                        }
                    }
                }
            }

            $email = $request->email;
            $password = $request->password;
            $http = new GuzzleHttp\Client;
            $url = url('oauth/token');
            try{
                $response = $http->post($url, [
                    'form_params'   =>  [
                        'grant_type'    =>  'password',
                        'client_id'     =>  2,
                        'client_secret' =>  'uu6k1kweRMo201y8HGjQbSV5xBYFUnA3MBYlK4OD',
                        'username'      =>  $request->email,
                        'password'      =>  $request->password,
                        'scope'         =>  '*'
                    ],
                ]);
            } catch(\Exception $ex) {
                return $ex->getMessage();
              $this ->responseWithError('Something went wrong...Please Try Again..');
            }

                $return_data = json_decode($response->getBody(), true);
                
                $user->refresh_token = $return_data['refresh_token'];
                if($user->update()) {
                    $url = url('website/user-web/verify/'.$user->email_verification_token);
                    try{
                        Mail::send('website_email_verify',['url' =>$url,'user_data' => $user], function ($m) use ($user) {
                            $m->from(env('MAIL_FROM'), 'MoTiv');
                            $m->to($user->email,'App User');
                            $m->subject('Email verification link');
                        });
                    }catch(\Exception $ex){
                        return $ex->getMessage();
                        return $this ->responseWithError('Something went wrong...Please Try Again..');
                         // $this->responseOk('Your account registration process has been completed Successfully','');
                    }
                    Session::flash('message', 'All Done, Please verify your email address to create motiv.');
                    return redirect(url('website/login'))->withInput();
                }
                
                
        }   
    }


    public function organiserRegister(Request $request) {
        if($request->isMethod('get')) {
           return view('website.organiser-register');      
        }
        if($request->isMethod('post')) {
            // return $request->all();
            $errors = [
                'firstname.required'=>  'Please enter full name.',
                'lastname.required' =>  'Please enter lastname.',
                'user_name.required' =>  'Please enter username.',
                'email.required'    =>  'Please enter email address.',
                'user_name.unique'  =>  'Username has already been taken.',
                'password.required' =>  'Please enter password.',
                'confirm_password.required' =>  'Please enter confirm password.',
                'email.email'       =>  'Please enter valid email address.',
                'email.exists'      =>  'Please enter registered email address.',
                'question.required' =>  'Please select question.',
                'dob.required'      =>  'Please enter date of birth.',
                'user_type.required'=>  'Please select user type.',
                'image.required'    =>  'Please upload the profile picture.',
                'phone_number.digits_between' => 'Phone number must be number and between 8 and 15 digits.',
                'confirm_password.same'  => 'Password and confirm password must be same.',
                'about.max'             => 'About you cannot be greater than 120 characters.',
                'about.required'        => 'The about you field is required.',
                'firstname.regex'    => 'Firstname should be character.',
                'about.regex'       => 'The About you should be character.',
                'privacy.required'       => 'Please select privacy policy.',
                'user_name.regex'       => 'Please enter a valid username.',
                'phone_number.required'       => 'Please enter a phone number.',
                'phone_number.regex' => 'Please enter a valid phone number.',
                'phone_number.max' => 'Please enter a valid phone number.',
                'phone_number.min' => 'Please enter a valid phone number.',

             ];     

           $this->validate($request,[
                'firstname' => 'required|regex:/^[a-zA-Z ]*$/u|max:50',
                'user_name'  => 'required|regex:/^\S*$/u|max:50',
                'email'     => 'required|unique:users|email',
                'password'  => 'required|min:6',
                'confirm_password'  => 'required|required_with:password|same:password',
                'phone_number' => 'required|regex:/^[+0-9]+$/|max:19|min:10',
                'privacy' => 'required',
    
            ],$errors);
            // $request->all();
            $user = new User();
            $user->name = $request->firstname;
            // $user->lastname  = $request->lastname;
            $user->user_name  = $request->user_name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->visibsle_pwd = $request->password;
            $user->email_verification_token = Crypt::encrypt(str_random(5));
            $user->role = 3;
            $user->phone_number = $request->phone_number;
            $user->about_me = $request->about;
               


            if(!empty($request->file('image'))){
                $image_name = str_random(20).'.png';
                $path = Storage::putFileAs('public/user_images', $request->file('image'), $image_name);
                $baseUrl = url('/');
                $baseUrl = str_replace('/public','/',$baseUrl);
                $img_url = $baseUrl.'/storage/app/'.$path;
                $user->image_url = $img_url;
            }

            $user->save();

            $size=3;
            $alpha_key = $user->id;
            $keys = range('A', 'Z');
            for ($i = 0; $i < 2; $i++) {
                $alpha_key .= $keys[array_rand($keys)];
            }
            $length = $size - 2;
            $key = '';
            $keys = range(0, 9);
            for ($i = 0; $i < $length; $i++) {
                $key .= $keys[array_rand($keys)];
            }


            $user = User::find($user->id);
            $user->refferal_code= $alpha_key . $key;;

            $email = $request->email;
            $password = $request->password;
            $http = new GuzzleHttp\Client;
            $url = url('oauth/token');
            try{
                $response = $http->post($url, [
                    'form_params'   =>  [
                        'grant_type'    =>  'password',
                        'client_id'     =>  2,
                        'client_secret' =>  'uu6k1kweRMo201y8HGjQbSV5xBYFUnA3MBYlK4OD',
                        'username'      =>  $request->email,
                        'password'      =>  $request->password,
                        'scope'         =>  '*'
                    ],
                ]);
            } catch(\Exception $ex) {
                return $ex->getMessage();
              $this ->responseWithError('Something went wrong...Please Try Again..');
            }

                $return_data = json_decode($response->getBody(), true);
                
                $user->refresh_token = $return_data['refresh_token'];
                if($user->update()) {
                    $url = url('website/user-web/verify/'.$user->email_verification_token);
                    try{
                        Mail::send('website_email_verify',['url' =>$url,'user_data' => $user], function ($m) use ($user) {
                            $m->from(env('MAIL_FROM'), 'MoTiv');
                            $m->to($user->email,'App User');
                            $m->subject('Email verification link');
                        });
                    }catch(\Exception $ex){
                        return $ex->getMessage();
                        return $this ->responseWithError('Something went wrong...Please Try Again..');
                    }
                    Session::flash('message', 'All Done, Please verify your email address to create motiv.');
                    return redirect(url('website/login'))->withInput();
                }
                
                
        }   
    }


    public function verify(Request $request)
    {
        $token = $request->token;
        $user = User::where('email_verification_token',$token)->first();

        if($user) {
            $user->email_verification_token = null;
            $user->status = 1;
            $user->update();
            $title = "Email verified";
            $message = "Your email has been verified successfully. You may now login.";
            $type = "success";
            return view('success', compact('title', 'message', 'type'));
        }else {
            
            $title = "Invalid link";
            $message = "The email verification link is either expired or invalid.";
            $type = "danger";
            return view('success', compact('title', 'message', 'type'));
        }
    }


    public function login(Request $request) {
        if($request->isMethod('get')) {
            // if(!empty(auth()->guard('web')->user())) {
            //     return redirect('website/current-events');
            // }
            return view('website.login');
        }
        if($request->isMethod('post')) {
            $errors = [
                'email.email'       =>  'Please enter valid email address.',
                'email.exists'      =>  'Please enter registered email address.',
                'email.required'    =>  'Please enter email address.',
                'password.required' =>  'Please enter password.'
             ];     

           $this->validate($request,[
                'email'     => 'required|email',
                'password'  => 'required|min:6',
            ],$errors);

            $email = $request->email;
            $password = $request->password;

            $user = User::where('email',$email)->first();
            if($user) {
                if($user->status == 2) {
                    Session::flash('danger', 'Please verify your account.');
                    return back()->withInput();
                }

                if($user->role == 1) {
                    Session::flash('danger', 'Please enter registered email address.');
                    return back()->withInput();
                }

                if($user->blockStatus == 1) {
                Session::flash('danger', 'You are blocked by admin');
                return back()->withInput();
                }

                if(auth()->guard('web')->attempt(['email' => $email, 'password' => $password])) {
                    return redirect('website/current-events');
                } else {
                Session::flash('danger', 'Email address and password does not match.');
                return back()->withInput();
                }    
            } else {
                Session::flash('danger', 'Please enter registered email address.');
                return back()->withInput();
            }

        }
        
    }


    public function forgotPassword(Request $request) {
        if($request->isMethod('get')) {
            return view('website.forgot-password');
        }

        if($request->isMethod('post')) {
            $messages = [
                'email.email'       => 'Please enter valid email address.',
                'email.exist'       => 'Please enter registered email address.',
                'email.required'    => 'Please enter email address.',
            ];
        $data = $this->validate($request,['email' => 'required|email'], $messages);
        $reset_token  = str_random(64);
        $url = url("website/reset-password/$reset_token");
        $user = User::where(['email' => $request->email])->first();
        if (!$user) {
                Session::flash('danger', 'Email Address does not exist.');
                return back()->withInput();
            }
        try{
                Mail::to($user->email)->send(new WebsiteForgotPassword($user, $url));
                DB::table('password_resets')->insert([
                    'email'     => $request->email,
                    'token'     => $reset_token,
                    'created_at'=> Carbon::now()    
                ]);
            }catch(\Exception $ex) {
                return $ex->getMessage();
            }
            // return "1";
            Session::flash('message', 'Forgot password email has been sent to your registered email address.');
            return back()->withInput();
        }
    }



    public function resetPassword(Request $request) {
        if($request->isMethod('get')) {
            $token = $request->reset_token;
            $token_data = DB::table('password_resets')->whereToken($token)->first();
            if(!$token_data) {
                return redirect(url("website/reset-password-invalid"));
            }
            if(Carbon::now() > Carbon::parse($token_data->created_at)->addMinutes(10)) {
                return redirect(url("website/reset-password-invalid"));
            }
            return view('website.reset-password',compact('token'));
        }
        if($request->isMethod('post')) {
            $token = $request->reset_token;
            $message = [
                'password.required' => 'Please enter password.',
                'password_confirmation.required' => "Please enter confirm password.",
                'password_confirmation.same' => "New password and confirm password does not match."
            ];
            $this->validate($request,[
                'password'              => 'required|min:6',
                'password_confirmation' => 'required|same:password|min:6',
            ], $message);

            $data = DB::table('password_resets')->whereToken($token)->first();
            // print_r($data); die;
            // $tokenData = $data->first();
            $user = User::where(['email' => $data->email])->first();
            $user->password = Hash::make($request->password);
            $user->visibsle_pwd = $request->password;
            
            if($user->update()) {
                  $data = DB::table('password_resets')->whereToken($token)->delete();
                  return redirect(url("website/password-reset-success"));
            } else {
                return redirect(url("website/reset-password-invalid"));
            }
            
        }
    }


    public function changePassword(Request $request) {
        if($request->isMethod('get')) {
            $user = Auth::guard('web')->user(); 
            
            if($user) {
                if($user->blockStatus == 1){
                    Auth::logout();
                    Session::flash('danger', 'You are blocked by admin.');
                    return redirect('website/login')->withInput();
                }
            }     
            return view('website/change-password');
        }
        if($request->isMethod('post')) {
            $message = [
                'old_password.required'     => 'Please enter old password.',
                'new_password.required'     => 'Please enter new password.',
                'confirm_password.required' => "Please enter confirm password.",
                'new_password.min'          => 'New password must be at least 6 characters.',
                'confirm_password.min'      => "Confirm password must be at least 6 characters.",
                'confirm_password.same'     => "Password and confirm password does not match."
            ];
            $this->validate($request, [
                'old_password'      => 'required',
                'new_password'      => 'required|min:6,max:20',
                'confirm_password'  => 'required|required_with:new_password|same:new_password||min:6,max:20'
            ],$message);

            $old_password = $request->old_password;
            $new_password = $request->new_password;

            $user = Auth::guard('web')->user();
            if(Hash::check($old_password, $user->password)) {
                $update_data = User::where('id',$user->id)->update(['password' => Hash::make($request->new_password), 'visibsle_pwd' => $request->new_password ]);
                Auth()->guard()->logout();

                $request->session()->flush();
                $request->session()->regenerate();

                Session::flash('message', 'Password has been changed successfully.');
                return redirect(url('website/login'))->withInput();
            }else {
                Session::flash('danger', "Please enter valid old password.");
                return redirect(url('website/change-password'))->withInput();
            }

        }
    }


    public function logout(Request $request) {
        
        $user = Auth::guard('web')->user();
        Auth::logout();
        $request->session()->flush();
        $request->session()->regenerate();

        Session::flash('success', 'Logout successfully.');
        return redirect(url('website/login'))->withInput();
    }


    public function blockedUsersList(Request $request) {
        if($request->isMethod('get')) {
            $user = Auth::guard('web')->user();
            if($user) {
                if($user->blockStatus == 1){
                    Auth::logout();
                    Session::flash('danger', 'You are blocked by admin.');
                    return redirect('website/login')->withInput();
                }
            }    

            $blocked_users = DB::table('block_users')->where('user_id',$user->id)->get();
            foreach ($blocked_users as $blocked_user) {
                $user = DB::table('users')->where('id',$blocked_user->friend_id)->first();
                $blocked_user->user_image = !empty($user->image_url) ? $user->image_url : '';
                $blocked_user->user_name =!empty($user->name) ? $user->name : '';    
            }
            return view('website/block-users',compact('blocked_users'));
        }


    }

    public function blockUser(Request $request) {
        $user = Auth::guard('web')->user();
        if($user) {
            if($user->blockStatus == 1){
                Auth::logout();
                Session::flash('danger', 'You are blocked by admin.');
                return redirect('website/login')->withInput();
            }
        }  
         
        $id = $user->id;
        $friend_id = $request->friend_id;


        $check_block=DB::table('block_users')->where(['user_id'=>$id,'friend_id'=>$friend_id])->first(); 
        $check_friend   = Friend::where(function ($query) use ($id,$friend_id){
                                $query->where(['sender_id'=>$id])
                                ->where(['receiver_id'=>$friend_id]);
                            })->orWhere(function($query) use ($id,$friend_id){
                                $query->where(['sender_id'=>$friend_id])
                                ->where(['receiver_id'=>$id]);                                                  
                            })->first();
        $check_friend->request_status = 4;
        $check_friend->update();

        $save['user_id']=$id;
        $save['friend_id']=$friend_id;
        $save['created_at']=Date('Y-m-d H:i:s');
        $check_block=DB::table('block_users')->where(['user_id'=>$id,'friend_id'=>$friend_id])->insert($save);
        Session::flash('message', 'User blocked successfully.');
        return redirect(url('website/friend-list'))->withInput();
    }

    public function unblockedUser(Request $request) {
        $user = Auth::guard('web')->user();
        $id = $user->id;
        $friend_id = $request->user_id;
        $unblocked_users = DB::table('block_users')->where(['user_id'=> $user->id,'friend_id'=>$friend_id])->delete();
        $check_friend   = Friend::where(function ($query) use ($id,$friend_id){
                                $query->where(['sender_id'=>$id])
                                ->where(['receiver_id'=>$friend_id]);
                            })->orWhere(function($query) use ($id,$friend_id){
                                $query->where(['sender_id'=>$friend_id])
                                ->where(['receiver_id'=>$id]);                                                  
                            })->first();
        $check_friend->request_status = 1;
        $check_friend->update();
        Session::flash('message', 'Unblocked successfully.');
        return redirect(url('website/block-users'))->withInput();

    }


    public function enableNotification(Request $request) {
        $user = Auth::guard('web')->user();
        $notification_status = $request->notification_status;
        $user_id = $user->id;
        $notificationOff = User::where(['id'=>$user_id])->first();
        $notificationOff->notification_status = $notification_status;
        $notificationOff->update();
        if($notification_status == 1) {
            return 1;
        } else {
            return 2;
        }
    }

    public function myProfile(Request $request) {
        $user = auth()->guard('web')->user();
        if($user) {
            if($user->blockStatus == 1){
                Auth::logout();
                Session::flash('danger', 'You are blocked by admin.');
                return redirect('website/login')->withInput();
            }
        }   
        $friend_count = FriendList::where(['request_status'=>2,'receiver_id'=>$user->id])
                    ->select('id as request_id','sender_id','receiver_id')
                    ->count();
        return view('website.my-profile',compact('user','friend_count'));
    }

    public function editProfile(Request $request,$id) {
        if($request->isMethod('get')) {
            $user = User::findOrfail($id);
            if($user) {
                if($user->blockStatus == 1){
                    Auth::logout();
                    Session::flash('danger', 'You are blocked by admin.');
                    return redirect('website/login')->withInput();
                }
            } 
            return view('website.edit-profile',compact('user')); 
        }

        if($request->isMethod('post')) {
            $errors = [
                'name.required'=>  'Please enter name.',
                'lastname.required' =>  'Please enter lastname.',
                'user_name.required' =>  'Please enter username.',
                'email.required'    =>  'Please enter email address.',
                'password.required' =>  'Please enter password.',
                'confirm_password.required' =>  'Please enter confirm password.',
                'email.email'       =>  'Please enter valid email address.',
                'email.exists'      =>  'Please enter registered email address.',
                'question.required' =>  'Please select question.',
                'dob.required'      =>  'Please enter date of birth.',
                'image.required'    =>  'Please upload the profile picture.',
                // 'phone_number.max' => 'Phone number must be number and between  and 15 digits.',
                'confirm_password.same'  => 'Password and confirm password must be same.',
                'about.max'             => 'About you cannot be greater than 120 characters.',
                'about.required'        => 'The about you field is required.',
                'firstname.regex'    => 'Firstname should be character.',
                'lastname.regex'    => 'Lastname should be character.',
                'about.max'       => 'The About you may not be greater than 120 character.',
                'name.regex'       => 'Name should be character.',
                'phone_number.regex' => 'Please enter a valid phone number.',
                'phone_number.max' => 'Please enter a valid phone number.',
                'phone_number.min' => 'Please enter a valid phone number.',
                'image.image'     =>  'Profile picture must be an image.',
             ];     

           $this->validate($request,[
                'name' => 'required|regex:/^[a-zA-Z ]*$/u|max:50',
                'email'     => 'required|email',
                'phone_number' => 'nullable|regex:/^[+0-9]+$/|max:19|min:10',
                'image' => 'nullable|image|mimes:jpeg,png,jpg', 
    
            ],$errors);

            $user = User::findOrfail($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone_number = $request->phone_number;
            $user->about_me = $request->about;
           
            if(!empty($request->file('image'))){
                $image_name = str_random(20).'.png';
                $path = Storage::putFileAs('public/user_images', $request->file('image'), $image_name);
                $baseUrl = url('/');
                $baseUrl = str_replace('/public','/',$baseUrl);
                $img_url = $baseUrl.'/storage/app/'.$path;
                $user->image_url = $img_url;
            }
            $user->update();
            Session::flash('message', 'Profile updated successfully.');
            return redirect(url('website/my-profile'))->withinput();

        }
    }


    public function friendList(Request $request) {
        $user = auth()->guard('web')->user();
        if($user) {
            if($user->blockStatus == 1){
                Auth::logout();
                Session::flash('danger', 'You are blocked by admin.');
                return redirect('website/login')->withInput();
            }
        } 

        $id = $user->id;
        $friendList = Friend::where(function ($query) use ($id){
                                    $query->where(['sender_id'=>$id])
                                    ->where(['request_status'=>1]);
                                })->orWhere(function($query) use ($id){
                                    $query->where(['receiver_id'=>$id])
                                    ->where(['request_status'=>1])  
                                    ->where('id','!=',$id);                                            
                                })->select('id as request_id','sender_id','receiver_id')
                                  ->get();  
            foreach($friendList as $eachGet_friend){
                if($eachGet_friend->sender_id == $id){
                    $eachGet_friend->senderDetail=User::where('id','=',$eachGet_friend->receiver_id)->select('id','name','image_url','email')->first();
                }else{
                    $eachGet_friend->senderDetail=User::where('id','=',$eachGet_friend->sender_id)->select('id','name','image_url','email')->first();
                }
            }

        return view('website.friend-list',compact('friendList'));
    }


    public function addFriend(Request $request) {
        
        $user = Auth::guard('web')->user();
        if($user) {
            if($user->blockStatus == 1){
                Auth::logout();
                Session::flash('danger', 'You are blocked by admin.');
                return redirect('website/login')->withInput();
            }
        }  

        $friends = Friend::where('sender_id',$user->id)->pluck('receiver_id');
        $receive_request = Friend::where('receiver_id',$user->id)->pluck('sender_id');
        $blocked_users = DB::table('block_users')->where('user_id',$user->id)->pluck('friend_id');
        $blocked_by_user = DB::table('block_users')->where('friend_id',$user->id)->pluck('user_id');

        $users = User::where(['status'=>1,'role'=>2])
                ->where('id','!=',$user->id)
                ->whereNotIn('id',$blocked_users)
                ->whereNotIn('id',$friends)
                ->whereNotIn('id',$receive_request)
                ->whereNotIn('id',$blocked_by_user)
                ->select('id','name','image_url')
                ->get();
        return view('website.add-friends',compact('users'));
    }

    public function viewProfile(Request $request,$id) {
        $user = User::findOrfail($id);
        $users = auth()->guard('web')->user();

        if($users) {
            if($users->blockStatus == 1){
                Auth::logout();
                Session::flash('danger', 'You are blocked by admin.');
                return redirect('website/login')->withInput();
            }
        }   

        $event_music_interest_list=DB::table('user_music_interest')->where(['user_id'=>$user->id])->pluck('music_interest_id');
        $event_public_interest_list=DB::table('user_public_interest')->where(['user_id'=>$user->id])->pluck('public_interest_id');
        
        if(!empty($event_music_interest_list)){
            $music_interest_id=DB::table('music_interest')->whereIn('id',$event_music_interest_list)->get();
        }else{
            $music_interest_id=array(); 
        }

        if(!empty($event_public_interest_list)){
            $public_interest_id=DB::table('public_interest')->whereIn('id',$event_public_interest_list)->get();
        }else{
            $public_interest_id=array();    
        }
        // return $public_interest_id;
        return view('website/view-profile',compact('user','music_interest_id','public_interest_id'));
    }

    public function sentRequest(Request $request) {

        $user = Auth::guard('web')->user();
        $id=$user->id;

        $receiver_id = $request->friend_id;
        $get_friend_name = User::where(['id'=>$request->friend_id])->first();
        $check_friend   =Friend::where(function ($query) use ($id,$receiver_id){
                                        $query->where(['sender_id'=>$id])
                                        ->where(['receiver_id'=>$receiver_id]);
                                    })->orWhere(function($query) use ($id,$receiver_id){
                                        $query->where(['sender_id'=>$receiver_id])
                                        ->where(['receiver_id'=>$id]);                                                  
                                    })->first();
    
        if(empty($check_friend)){
        $request_id = FriendList::insertGetId(['sender_id'=>$id,'receiver_id'=>$receiver_id,'timestamp'=>round(microtime(true) * 1000)]);
        }else{
            Session::flash('message', 'Friend request already sent to this user.'); 
            return redirect(url('website/add-friends'))->withinput();
        }

        Session::flash('message', 'Friend request sent to this user.'); 
        return redirect(url('website/add-friends'))->withinput();
    }

    public function friendRequest(Request $request) {
        $user = auth()->guard('web')->user();
        
        if($user) {
            if($user->blockStatus == 1){
                Auth::logout();
                Session::flash('danger', 'You are blocked by admin.');
                return redirect('website/login')->withInput();
            }
        }    

        $id = $user->id;
        $get_friend = FriendList::where(['request_status'=>2,'receiver_id'=>$id])
                    ->select('id as request_id','sender_id','receiver_id')
                    ->get();

        foreach($get_friend as $eachGet_friend){
            $eachGet_friend->sender = User::where('id',$eachGet_friend->sender_id)->select('id','name','image_url')->first();
        }
        
        return view('website.friend-request',compact('get_friend'));
    }





    public function acceptRequest(Request $request) {
        $user = auth()->guard('web')->user();
        $id = $user->id;
        $check_id=DB::table('friends')->where(['id'=>$request->request_id])->first();

        $update_data = FriendList::where('id',$request->request_id)
                    ->update(['request_status'=>1]);           
        
        Session::flash('message', 'Request accepted successfully.'); 
        return redirect(url('website/friend-request'))->withinput();
        
    }




    public function rejectRequest(Request $request) { 
        $update_data = FriendList::where('id',$request->request_id)->delete();
        Session::flash('message', 'Request declined successfully.'); 
        return redirect(url('website/friend-request'))->withinput();
    }



    public function referallCode(Request $request) {
        $user = auth()->guard('web')->user();
        if($user) {
            $this->checkBlock($user->id);
        }   
        return view('website.referall-code',compact('user'));
    }

    public function publicInterest(Request $request) {
        $user = auth()->guard('web')->user();

        if($user) {
            if($user->blockStatus == 1){
                Auth::logout();
                Session::flash('danger', 'You are blocked by admin.');
                return redirect('website/login')->withInput();
            }
        }    

        $user_public_interest = DB::table('user_public_interest')->where('user_id',$user->id)->get();
        $user_public_count = DB::table('user_public_interest')->where('user_id',$user->id)->count();
        $public_interest = PublicInterest::all();
        $public_count = PublicInterest::count();
        return view('website.public-interest',compact('public_interest','user_public_interest','public_count','user_public_count'));
    }

    public function musicInterest(Request $request) {
        $user = auth()->guard('web')->user();
        
        if($user) {
            if($user->blockStatus == 1){
                Auth::logout();
                Session::flash('danger', 'You are blocked by admin.');
                return redirect('website/login')->withInput();
            }
        }  
        $music_interest = MusicInterest::all();
        $user_music_interest = DB::table('user_music_interest')->where('user_id',$user->id)->get();
        $music_count = MusicInterest::count();
        $user_music_count = DB::table('user_music_interest')->where('user_id',$user->id)->count();
        return view('website.music-interest',compact('music_interest','user_music_interest','music_count','user_music_count'));
    }


    public function editPublicInterest(Request $request) {
        $user = auth()->guard('web')->user();
        
        if($user) {
            if($user->blockStatus == 1){
                Auth::logout();
                Session::flash('danger', 'You are blocked by admin.');
                return redirect('website/login')->withInput();
            }
        }    
        $user_public_interest = DB::table('user_public_interest')->where('user_id',$user->id)->delete();
        $public_interest = $request->public;
        if($public_interest) {
            $public = count($public_interest);
            if($public > 0){
                for($i=0;$i< $public;$i++){
                    if(is_numeric($public_interest[$i])){
                        UserPublicInterest::insertGetId(['public_interest_id' => $public_interest[$i],'user_id' => $user->id,'created_at' => Date('Y-m-d H:i:s')]);
                    }
                }
            }
        }
        Session::flash('message', 'Public interest updated successfully.'); 
        return redirect(url('website/my-profile'))->withinput();
    }

    public function editMusicInterest(Request $request) {
        $user = auth()->guard('web')->user();

        $user_music_interest = DB::table('user_music_interest')->where('user_id',$user->id)->delete();
        $music_interest = $request->music;
        if($music_interest) {
            $music = count($music_interest);
            if($music > 0){
                for($i=0;$i<$music;$i++){
                    if(is_numeric($music_interest[$i])){
                        UserMusicInterest::insertGetId(['music_interest_id' => $music_interest[$i],'user_id' => $user->id,'created_at' => Date('Y-m-d H:i:s')]);
                    }
                }
            }
        }
        Session::flash('message', 'Music interest updated successfully.'); 
        return redirect(url('website/my-profile'))->withinput();


    }

    



    public function viewMessageResetPassword()
    {
        $title = "Password Reset Success";
        $message = "Password has been reset successfully.";
        $type = "success";
        return view('success', compact('title', 'message', 'type','link'));
    }

    public function resetPasswordInvalid()
    {
        $title = "Invalid link";
        $message = "The forgot password link you clicked is invalid or has expired.";
        $type = "danger";
        return view('success', compact('title', 'message', 'type'));
    }

    public function notification_count($user_id){
        $notification_count=DB::table('notification_list')->where(['user_id'=>$user_id,'status'=>2])->count();
        return $notification_count;
    }


    public function socialLogin(Request $request)
    {
            session_start();
//            $fb = new Facebook\Facebook([
//              'app_id' => '2050763491639795',
//              'app_secret' => '3cca8831006d55e33ba5a46323d59c30',
//              'default_graph_version' => 'v2.7',
//            ]);

            //CLIENT
        $fb = new Facebook\Facebook([
        'app_id' => '401548690469040',
        'app_secret' => '1bffc27bc271482d7b215659de66d18a',
        'default_graph_version' => 'v2.7',
        ]);
            ///////
             Session::put('fb', $fb);
            $helper = $fb->getRedirectLoginHelper();
            $permissions = ['email'];
            $url = url('website/facebook-callback');
            //dd($url);
            //print_r($url);exit;
            $loginUrl = $helper->getLoginUrl($url, $permissions);
            // dd($loginUrl);
            return Redirect::to($loginUrl);
    }

    public function loginFacebookCallback(Request $request)
    {
        session_start();


            $fb = new Facebook\Facebook([
              'app_id' => '401548690469040',
              'app_secret' => '1bffc27bc271482d7b215659de66d18a',
              'default_graph_version' => 'v2.7',
            ]);
        
        $helper = $fb->getRedirectLoginHelper();
       // dd($helper);
        $error_message = Input::get('error_message');
        if(!empty($error_message)){
            Session::flash('error',$error_message.'');
            return redirect(url('website/login'));
        }

        try {
          $accessToken = $helper->getAccessToken();
          
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
          echo 'Graph returned an error: ' . $e->getMessage();
          exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
          echo 'Facebook SDK returned an error: ' . $e->getMessage();
          exit;
        }

        try {
          $response = $fb->get('/me?fields=id,name,about,age_range,birthday,cover,email,first_name,last_name,gender', $accessToken->getValue());
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
          echo 'Graph returned an error: ' . $e->getMessage();
          exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
          echo 'Facebook SDK returned an error: ' . $e->getMessage();
          exit;
        }

        $user = $response->getGraphUser();

        dd($user);

        $id = $user["id"];
        $check_user = User::whereSocialId($id)->first();

        if(isset($check_user)){
            if(auth()->guard('web')->attempt(['email' => $user['id'], 'password' => $user['id']])) {
                return redirect(url('website/events'));
            }
        }else{
            $fb_user_image_name = uniqid().'_'.$user['id'];
                  
            $signup = new User();
            $signup->name                 = $user['first_name'];
            $signup->email                = $user['id'];
            $signup->phone_number         = $request->phone_number ? $request->phone_number : "";
            $signup->social_id            = $user['id'];
            $signup->social_email         = $user['id'];
            $signup->visible_password     = $user['id'];
            $signup->password             = Hash::make($user['id']);
            //$signup->device_type          = '';
            $signup->device_token         = $request->input('device_token') ? $request->input('device_token') : null;
            $signup->social_signup_type                 = 'Facebook';

            $signup->save();
            return redirect(url('website/events'));

        }
    }

    public function viewFriend(Request $request) {
        $id = $request->friend_id;
        $user = User::findOrfail($id);

        $event_music_interest_list=DB::table('user_music_interest')->where(['user_id'=>$id])->pluck('music_interest_id');
        $event_public_interest_list=DB::table('user_public_interest')->where(['user_id'=>$id])->pluck('public_interest_id');
        
        if(!empty($event_music_interest_list)){
            $music_interest_id=DB::table('music_interest')->whereIn('id',$event_music_interest_list)->get();
        }else{
            $music_interest_id=array(); 
        }

        if(!empty($event_public_interest_list)){
            $public_interest_id=DB::table('public_interest')->whereIn('id',$event_public_interest_list)->get();
        }else{
            $public_interest_id=array();    
        }
        return view('website/view-friend',compact('user','music_interest_id','public_interest_id'));
    }

    public function viewRequest(Request $request) {
        $request_id = $request->request_id;
        $check_id=DB::table('friends')->where(['id'=>$request_id])->first();
        $user = User::where('id',$check_id->sender_id)->first();
        $id = $user->id;

        $event_music_interest_list=DB::table('user_music_interest')->where(['user_id'=>$id])->pluck('music_interest_id');
        $event_public_interest_list=DB::table('user_public_interest')->where(['user_id'=>$id])->pluck('public_interest_id');
        
        if(!empty($event_music_interest_list)){
            $music_interest_id=DB::table('music_interest')->whereIn('id',$event_music_interest_list)->get();
        }else{
            $music_interest_id=array(); 
        }

        if(!empty($event_public_interest_list)){
            $public_interest_id=DB::table('public_interest')->whereIn('id',$event_public_interest_list)->get();
        }else{
            $public_interest_id=array();    
        }
        return view('website/view-request',compact('user','music_interest_id','public_interest_id','request_id'));
    }


    public function contactUs(Request $request) {
        if($request->isMethod('get')) {
           return view('website.contact-us'); 
        }

        if($request->isMethod('post')) {

            $errors = [
                'name.required'     =>  'Please enter name.',
                'email.required'    =>  'Please enter email address.',
                'email.email'       =>  'Please enter valid email address.',
                'email.exists'      =>  'Please enter registered email address.',
                // 'phone_number.max' => 'Phone number must be number and between  and 15 digits.',
                'name.regex'       => 'Name should be character.',
                'phone_number.regex' => 'Please enter a valid phone number.',
                'phone_number.max' => 'Please enter a valid phone number.',
                'phone_number.min' => 'Please enter a valid phone number.',
                'phone_number.required' => 'Please enter phone number.',
                'comment.required' => 'Please enter comment.',
             ];     

           $this->validate($request,[
                'name' => 'required|regex:/^[a-zA-Z ]*$/u|max:50',
                'email'     => 'required|email',
                'phone_number' => 'required|regex:/^[+0-9]+$/|max:19|min:10',
                'comment'    => 'required|max:120',
    
            ],$errors);

            $user = auth()->guard('web')->user();
            $insert = ContactUs::insert(['user_id' => $user->id, 'text' =>$request->input('comment'),'created_at'=>Date('Y-m-d H:i:s'),'updated_at'=>Date('Y-m-d H:i:s')]);
            if($insert){
                $text_message = $request->input('comment');
                $insert_data['name'] = $request->name;
                $insert_data['email'] = $request->email;

                $insert_data['email_verification_token'] = str_random('40');
                $url = url('user/verify/'.$insert_data['email_verification_token']);
                try{
                    Mail::send('email-feedback',['url' =>$url,'text_message'=>$text_message,'user_data' => $insert_data], function ($m) use ($insert_data) {
                        $m->from(env('MAIL_FROM'), 'MoTiv');
                        $m->to('adminn@yopmail.com','App User');
                        $m->subject('Feedback Email');
                    });
                }catch(\Exception $ex){
                    return $ex->getMessage();
                    Session::flash('danger', 'Something went wrong...Please Try Again..');
                    return back()->withinput();
                }
                Session::flash('message', 'Contact us request submitted successfully.');
                return back()->withinput();
            }else{
                Session::flash('danger', 'Something went wrong...Please Try Again..');
                return back()->withinput();
            }
        }
        
    }

    public function checkBlock($id) {
        $user = User::find($id);
        if($user->blockStatus == 1){
            Auth::logout();
            Session::flash('danger', 'You are blocked by admin.');
            return redirect('website/login')->withInput();
        }
    }

    public function removeProfile($id) {
        $user = User::find($id);
        $user->image_url = "";
        $user->update();
        return back();

    }





}
