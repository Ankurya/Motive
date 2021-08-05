<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use DB;
use Redirect;
use Session;
use Validator;
Use App\User;
use GuzzleHttp;
use Hash;
use Auth;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function login(Request $request){
        if(Auth::check()){
            return redirect('admin/dashboard');
        }

        if($request->isMethod('get')) {
            return view('admin.login');
        }

        if($request->isMethod('post')){
            $validator = Validator::make($request->all(), [
                'email' => 'required',
                'password' => 'required',
            ]);
            if($validator->fails()){
                return back()->withErrors($validator)->withInput();
            }
            $email = $request->input('email');
            $password = $request->input('password');
            $device_token = !empty($request->input('device_token')) ? $request->input('device_token'):'';
            $device_type = !empty($request->input('device_type')) ? $request->input('device_type'):'';
            $check_user_exist = User::where(['email' => $email,'role' => 1])->first();
            if(!empty($check_user_exist)){
                if(Auth::attempt(['email' => $email, 'password' => $password, 'role' => 1])){
                    return redirect('admin/dashboard');
                }else{
                    $message = array('email or password does not match');
                    return back()->withErrors($message)->withInput();
                }
            }else{
                $message = array('Email does not registred with us');
                return back()->withErrors($message)->withInput();
            }
        }
    }
    
    public function signup(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required',
        ]);
        
        if($validator->fails()){
            $this->responseWithError($validator->errors()->first());
            exit;
        }
        $device_token = !empty($request->input('device_token')) ? $request->input('device_token'):'';
        $device_type = !empty($request->input('device_type')) ? $request->input('device_type'):'';
        $password = $request->input('password');
        $email = $request->input('email');
        $insert_user = User::insertGetId(['name' => $request->input('name'),'email' => $email,'password' => Hash::make($password),'visibsle_pwd' =>$request->input('password'),'created_at' => Date('Y-m-d H:i:s'),'updated_at' => Date('Y-m-d H:i:s'),'refresh_token' => '','device_token' => $device_token, 'device_type' => $device_type]);
        if($insert_user){
            $http = new GuzzleHttp\Client;
            $url = Url('oauth/token');
            $response = $http->post($url, [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => 2,
                    'client_secret' => 'OJZMQ9Ef4gYdxxsdfvUubdbbX0BzsWolWg5wKwAC',
                    'username' => $email,
                    'password' => $password,
                    'scope' => '*',
                ],
            ]);
            $return_data = json_decode($response->getBody(), true);
            if(!empty($return_data)){
                $update_refresh_token = User::where(['id' => $insert_user])->update(['refresh_token' => $return_data['refresh_token']]);
                $this->responseOk('Registration has been Successfully done','');
            }
        }else{
            $this->responseWithError('oops Something Wrong');
        }
    }
    
    public function forgotPassword(Request $request){
        if($request->isMethod('post')){
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
            ]);
            
            if($validator->fails()){
                return back()->withErrors($validator)->withInput();
            }
            $check_user_exist = User::where('email',$request->input('email'))->first();
            if(!empty($check_user_exist)){
                $reset_password_token = str_random(40);
                $url = url('admin/reset-password/'.$reset_password_token);
                $update_data = User::where('id',$check_user_exist->id)->update(['reset_password_token' => $reset_password_token, 'updated_at' => Date('Y-m-d H:i:s')]);
                try{
                    $user_data = User::where('id',$check_user_exist->id)->first();
                    Mail::send('email_forget',['url' => $url,'user_data' => $user_data], function ($m) use ($user_data) {
                        $m->from(env('MAIL_FROM'), 'Mo-Tiv');
                        $m->to($user_data->email,'App User');
                        $m->cc('deftsofttesting786@gmail.com','App User');
                        $m->subject('Forgot Password link');
                    });
                    $message = array('Mail has been sent to your registred email id');
                    return back()->withErrors($message)->withInput();
                }catch(\Exception $e){
                    $message = array('Oops Something wrong! '.$e->getMessage());
                    return back()->withErrors($message)->withInput();
                }
            }else{
                $message = array('Old password does not match with your account');
                return back()->withErrors($message)->withInput();
            }
        }else{
            return view('admin/forgot-password');
        }
    }
    
    public function resetPassword(Request $request,$token){
        // print_r($token);exit;
        $check_token = DB::table('users')->where(['reset_password_token' => $token])->first();
        if(!empty($check_token)){
            if($request->isMethod('post')){
                $validator = Validator::make($request->all(), [
                    'password' => 'required|min:6',
                    'confirm_password' => 'required|same:password',
                ]);
                if($validator->fails()){
                    return redirect('admin/reset-password/'.$token)->withErrors($validator)->withInput();
                }
                $update_data = User::where('id',$check_token->id)->update(['password' => Hash::make($request->input('password')),'visibsle_pwd' => $request->input('password'), 'updated_at' => Date('Y-m-d H:i:s'),'reset_password_token' => '']);
                $message = 'Your Password has been changed Successfully.';
                Session::flash('success', 'Your Password has been changed Successfully.');
                return view('success',compact('message'));
            }else{
                return view('admin/reset-password',compact('message'));
            }
        }else{
                $message = 'Your reset password link has been expired or invalid.';
                Session::flash('success', 'Your reset password link has been expired or invalid.');
                return view('success',compact('message'));
            // return view('pageNotFound404');
        }
    }

    public function verify(Request $request,$link){
        $check_link = User::where(['email_verification_token' => $link])->first();
        if(!empty($check_link)){
            User::where(['id' => $check_link->id])->update(['email_verification_token' => '','status' => 1]);
            // Session::flash('message','Your account email verification process has been completed');
            $message = "Your email has been verified successfully. You may now login.";
            return view('success',compact('message'));
        }else{
            $message = "Your account email verification link has been expired or invalid.";
            // Session::flash('message','Your account email verification link has been expired or invalid');
            return view('success',compact('message'));
        }
    }

}
