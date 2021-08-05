<?php

namespace App\Http\Controllers\Website;

header('Cache-Control: no-store, private, no-cache, must-revalidate');
header('Cache-Control: pre-check=0, post-check=0, max-age=0, max-stale = 0', false);

use DB;
use Session;
use Auth;
use QrCode;
use App\User;
use App\Models\Like;
use App\Models\Ticket;
use App\Models\Friend;
use App\Models\CommentList;
use App\Models\add_details;
use Illuminate\Http\Request;
use App\Models\MusicInterest;
use App\Models\PostList;
use App\Models\EventList;
use App\Models\PublicInterest;
use App\Models\FavouriteEvent;
use App\Models\UserPublicInterest;
use App\Models\UserMusicInterest;
use App\Models\EventMusicInterestList;
use App\Models\EventPublicInterestList;
Use App\Models\ShareUrl;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\ResponseController;
use Carbon\Carbon;
use Illuminate\Support\Arr;


use LVR\CreditCard\CardCvc;
use LVR\CreditCard\CardNumber;
use LVR\CreditCard\CardExpirationYear;
use LVR\CreditCard\CardExpirationMonth;

date_default_timezone_set('Asia/Kolkata');

class WebsiteController extends ResponseController
{
	
	public function homePage() {
        return view('website.home');
    }

	Public function currentEvent(Request $request,$date = null) {
		
		$user = auth()->guard('web')->user();
		if($user) {
	        
            if($user->blockStatus == 1){
                Auth::logout();
                Session::flash('danger', 'You are blocked by admin.');
                return redirect('website/login')->withInput();
            }
	       
			if($user->role == 1){
				Auth::logout();
				$request->session()->flush();
				$request->session()->regenerate();
				return redirect(url('website/login'))->withInput();

			}
			$user_id = $user->id; 
		} else{
			$user_id = 123456789; 
		}

		$current_date = date('Y-m-d');
		$data = array();
		$public_interest = PublicInterest::all();
		$music_interest = MusicInterest::all();
		$match_date=date('Y-m-d H:i:00'); 
		$end_dt=date('Y-m-d');
		$time=date('H:i:s');
		$date_range=date('Y-m-d', strtotime("+2 week", strtotime($end_dt)));	
		$match_date_range=date('Y-m-d H:i', strtotime("+2 week", strtotime($end_dt)));
		
		if($public_interest) {
			foreach ($public_interest as $public) {
				$data[$public->name] = DB::table("public_interest as P")
				->select("P.name as interest_name","E.event_name","event_video_url","E.event_image_url","event_video_url2","E.event_image_url2","main","E.event_media_type","event_schedule.event_id","event_schedule.id",DB::raw("TIME_FORMAT(event_schedule.event_time, '%h:%i %p') as event_time"),DB::raw('(SELECT (ticket_amount) FROM tickets where tickets.event_id = event_schedule.event_id order by id desc limit 1 ) as ticket_amount'),DB::raw('(SELECT (is_favorite) FROM favourite_events where favourite_events.event_id = E.id and favourite_events.user_id = '.$user_id.') as is_favorite'))

				->join("event_public_interest_list as I","P.id","=","I.public_interest_id")
				->join("event_list as E","E.id","=","I.event_id")
				// ->leftjoin("favourite_events as F","F.event_id","=","I.event_id")
				->where("P.id",$public->id)
				->join("event_schedule","event_schedule.event_id","=","E.id")
				->where(function($query) use($match_date){
					$query->where(['E.status'=>2]);
					$query->whereRaw('(unix_timestamp(event_schedule.event_start_date_time) <= unix_timestamp("'.$match_date.'") and unix_timestamp(event_schedule.event_end_date_time) >= unix_timestamp("'.$match_date.'"))');
				})

				->orderBy("P.id","DESC")
				->get();
			}
		}	
		 // return $data;

		$musics = array();
		if($music_interest) {
			foreach ($music_interest as $music) {
				$musics[$music->name] = DB::table("music_interest as M")
				->select("M.name as interest_name","E.event_name","event_video_url","E.event_image_url","event_video_url2","E.event_image_url2","main","E.event_media_type","event_schedule.event_id","event_schedule.id",DB::raw("TIME_FORMAT(event_schedule.event_time, '%h:%i %p') as event_time"),DB::raw('(SELECT (ticket_amount) FROM tickets where tickets.event_id = event_schedule.event_id order by id desc limit 1 ) as ticket_amount'),DB::raw('(SELECT (is_favorite) FROM favourite_events where favourite_events.event_id = E.id and favourite_events.user_id = '.$user_id.') as is_favorite'))
				->join("event_music_interest_list as I","M.id","=","I.music_interest_id")
				->join("event_list as E","E.id","=","I.event_id")
				// ->leftjoin("favourite_events as F","F.event_id","=","I.event_id")
				->where("M.id",$music->id)
				->join("event_schedule","event_schedule.event_id","=","E.id")
				->where(function($query) use($match_date){
					$query->where(['E.status'=>2]);
					$query->whereRaw('(unix_timestamp(event_schedule.event_end_date_time) >= unix_timestamp("'.$match_date.'") and unix_timestamp(event_schedule.event_start_date_time) <= unix_timestamp("'.$match_date.'"))');
				})
				->orderBy("M.id","DESC")
				->get();
			}
		}

		$event_interest = DB::table('event_public_interest_list')->pluck('event_id');
		$music_interest = DB::table('event_music_interest_list')->pluck('event_id');
		$other_category = DB::table("event_list as E")
		->select("E.event_name","event_video_url","E.event_image_url","event_video_url2","E.event_image_url2","main","E.event_media_type","event_schedule.event_id","event_schedule.id",DB::raw("TIME_FORMAT(event_schedule.event_time, '%h:%i %p') as event_time"),DB::raw('(SELECT (ticket_amount) FROM tickets where tickets.event_id = event_schedule.event_id order by id desc limit 1 ) as ticket_amount'),DB::raw('(SELECT (is_favorite) FROM favourite_events where favourite_events.event_id = E.id and favourite_events.user_id = '.$user_id.') as is_favorite'))
		->whereNotIn("E.id",$event_interest)
		->whereNotIn("E.id",$music_interest)
		->join("event_schedule","event_schedule.event_id","=","E.id")
		->where(function($query) use($match_date){
			$query->where(['E.status'=>2]);
			$query->whereRaw('(unix_timestamp(event_schedule.event_end_date_time) >= unix_timestamp("'.$match_date.'") and unix_timestamp(event_schedule.event_start_date_time) <= unix_timestamp("'.$match_date.'"))');
		})
		->orderBy("event_schedule.event_end_date_time","DESC")
		->get();
		
		return view('website.current_events',["data" => $data,"music_interest" => $musics,"now_category" => $other_category]);
		
	}


	public function upcomingEvents(Request $request,$date = null) {
		$user = auth()->guard('web')->user();
		if($user) {
			if($user->blockStatus == 1){
                Auth::logout();
                Session::flash('danger', 'You are blocked by admin.');
                return redirect('website/login')->withInput();
            }
			if($user->role == 1){
				Auth::logout();
				$request->session()->flush();
				$request->session()->regenerate();
				return redirect(url('website/login'))->withInput();

			}
			$user_id = $user->id;
		} else{
			$user_id = 0;
		}

		$current_date = date('Y-m-d');
		$data = array();
		// For Current Event
		$public_interest = PublicInterest::all();
		$music_interest = MusicInterest::all();
		$match_date=date('Y-m-d H:i:00');
		// $event_time = $this->is_require($request->input('event_time'),'event_time');
		$end_dt=date('Y-m-d');
		$time=date('H:i:s');
		$date_range=date('Y-m-d', strtotime("+2 week", strtotime($end_dt)));	
		$match_date_range=date('Y-m-d H:i:00', strtotime("+2 week", strtotime($end_dt)));

		if($public_interest) {
			foreach ($public_interest as $public) {
				$upcoming[$public->name] = DB::table("public_interest as P")
				->select("P.name as interest_name","E.event_name","event_video_url","E.event_image_url","event_video_url2","E.event_image_url2","main","E.event_media_type","event_schedule.event_id",DB::raw("DATE_FORMAT(event_schedule.event_date, '%d-%m-%Y') as event_date"),"event_schedule.id",DB::raw("TIME_FORMAT(event_schedule.event_time, '%h:%i %p') as event_time"),DB::raw('(SELECT (ticket_amount) FROM tickets where tickets.event_id = event_schedule.event_id order by id desc limit 1 ) as ticket_amount'),DB::raw('(SELECT (is_favorite) FROM favourite_events where favourite_events.event_id = E.id and favourite_events.user_id = '.$user_id.') as is_favorite'))
				->join("event_public_interest_list as I","P.id","=","I.public_interest_id")
				->join("event_list as E","E.id","=","I.event_id")
				->where("P.id",$public->id)
				->join("event_schedule","event_schedule.event_id","=","E.id")
				->where(function($query) use($match_date,$match_date_range){
					$query->where(['E.status'=>2]);
					$query->whereRaw('(unix_timestamp(event_schedule.event_start_date_time) > unix_timestamp("'.$match_date.'") and unix_timestamp(event_schedule.event_start_date_time) between  unix_timestamp("'.$match_date.'") and unix_timestamp("'.$match_date_range.'"))');
				})
				->orderBy('event_schedule.event_start_date_time','ASC')
				->get();
			}
			// return $upcoming;	
		}

		$musics = array();
		if($music_interest) {
			foreach ($music_interest as $music) {
				$musics[$music->name] = DB::table("music_interest as M")
				->select("M.name as interest_name","E.event_name","event_video_url","E.event_image_url","event_video_url2","E.event_image_url2","main","E.event_media_type",DB::raw("DATE_FORMAT(event_schedule.event_date, '%d-%m-%Y') as event_date"),"event_schedule.event_id","event_schedule.id",DB::raw("TIME_FORMAT(event_schedule.event_time, '%h:%i %p') as event_time"),DB::raw('(SELECT (ticket_amount) FROM tickets where tickets.event_id = event_schedule.event_id order by id desc limit 1 ) as ticket_amount'),DB::raw('(SELECT (is_favorite) FROM favourite_events where favourite_events.event_id = E.id and favourite_events.user_id = '.$user_id.') as is_favorite'))
				->join("event_music_interest_list as I","M.id","=","I.music_interest_id")
				->join("event_list as E","E.id","=","I.event_id")
				->where("M.id",$music->id)
				->join("event_schedule","event_schedule.event_id","=","E.id")
				->where(function($query) use($match_date,$match_date_range){
					$query->where(['E.status'=>2]);
					$query->whereRaw('(unix_timestamp(event_schedule.event_start_date_time) > unix_timestamp("'.$match_date.'") and unix_timestamp(event_schedule.event_start_date_time) between  unix_timestamp("'.$match_date.'") and unix_timestamp("'.$match_date_range.'"))');
				})
				->orderBy("M.id","DESC")
				->get();
			}
		}

		$event_interest = DB::table('event_public_interest_list')->pluck('event_id');
		$music_interest = DB::table('event_music_interest_list')->pluck('event_id');

		$upcoming_category = DB::table("event_list as E")
		->select("E.event_name","event_video_url","E.event_image_url","event_video_url2","E.event_image_url2","main",DB::raw("DATE_FORMAT(event_schedule.event_date, '%d-%m-%Y') as event_date"),"E.event_media_type","event_schedule.event_id","event_schedule.id",DB::raw("TIME_FORMAT(event_schedule.event_time, '%h:%i %p') as event_time"),DB::raw('(SELECT (ticket_amount) FROM tickets where tickets.event_id = event_schedule.event_id order by id desc limit 1 ) as ticket_amount'),DB::raw('(SELECT (is_favorite) FROM favourite_events where favourite_events.event_id = E.id and favourite_events.user_id = '.$user_id.') as is_favorite'))
		->whereNotIn("E.id",$event_interest)
		->whereNotIn("E.id",$music_interest)
		->join("event_schedule","event_schedule.event_id","=","E.id")
		->where(function($query) use($match_date,$match_date_range){
			$query->where(['E.status'=>2]);
			$query->whereRaw('(unix_timestamp(event_schedule.event_start_date_time) > unix_timestamp("'.$match_date.'") and unix_timestamp(event_schedule.event_start_date_time) between  unix_timestamp("'.$match_date.'") and unix_timestamp("'.$match_date_range.'"))');
		})
		->orderBy("event_schedule.event_end_date_time","DESC")
		->get();
		return view('website.upcoming_events',["upcoming" => $upcoming,"music_interest" => $musics,"upcoming_category" => $upcoming_category]);
	}

	public function favoriteEvents(Request $request,$date = null) {
		$user = auth()->guard('web')->user();
		$id = $user->id;
		if($user) {
			if($user) {
				if($user->blockStatus == 1){
	                Auth::logout();
	                Session::flash('danger', 'You are blocked by admin.');
	                return redirect('website/login')->withInput();
	            }
				if($user->role == 1){
					Auth::logout();
					$request->session()->flush();
					$request->session()->regenerate();
					return redirect(url('website/login'))->withInput();

				}
			}
		}
		$favorite_event_id = FavouriteEvent::where(['user_id'=> $user->id,'is_favorite' => 1])->pluck('event_id')->toarray();
		$current_date = date('Y-m-d');
		$data = array();
		// For Current Event
		$public_interest = PublicInterest::all();
		$music_interest = MusicInterest::all();
		$match_date=date('Y-m-d g:i a');
		// $event_time = $this->is_require($request->input('event_time'),'event_time');
		$end_dt=date('Y-m-d');
		$time=date('H:i:s');
		$date_range=date('Y-m-d', strtotime("+2 week", strtotime($end_dt)));	
		$match_date_range=date('Y-m-d H:i', strtotime("+2 week", strtotime($end_dt)));

		$favourites= FavouriteEvent::where(['user_id'=>$user->id, 'is_favorite' => 1])->select('sub_event_id')
		->distinct('sub_event_id')
		->pluck('sub_event_id');	
		if($public_interest) {
			foreach ($public_interest as $public) {
				$favorite[$public->name] = DB::table("public_interest as P")
				->select("P.name as interest_name","E.event_name","event_video_url","E.event_image_url","event_video_url2","E.event_image_url2","main","E.event_media_type","event_schedule.event_id","event_schedule.id",DB::raw("TIME_FORMAT(event_schedule.event_time, '%h:%i %p') as event_time"),DB::raw('(SELECT (ticket_amount) FROM tickets where tickets.event_id = event_schedule.event_id order by id desc limit 1 ) as ticket_amount'),DB::raw('(SELECT (is_favorite) FROM favourite_events where favourite_events.event_id = E.id and favourite_events.user_id = '.$id.') as is_favorite'))
				->join("event_public_interest_list as I","P.id","=","I.public_interest_id")
				->join("event_list as E","E.id","=","I.event_id")	
				->where("E.status", 2)
				->whereIn("E.id",$favorite_event_id)
				->where("P.id",$public->id)
				->join("event_schedule","event_schedule.event_id","=","E.id")
				->where(function($query) use($favourites,$id){
					$query->whereIn('event_schedule.id',$favourites);
					$query->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id));
					$query->whereNotIn('event_schedule.user_id',$this->get_user_block_by_admin($id));
				})
				->orderBy("P.id","DESC")
				->get();

			}	
		}

		$musics = array();
		if($music_interest) {
			foreach ($music_interest as $music) {
				$musics[$music->name] = DB::table("music_interest as M")
				->select("M.name as interest_name","E.event_name","event_video_url","E.event_image_url","event_video_url2","E.event_image_url2","main","E.event_media_type","event_schedule.event_id","event_schedule.id",DB::raw("TIME_FORMAT(event_schedule.event_time, '%h:%i %p') as event_time"),DB::raw('(SELECT (ticket_amount) FROM tickets where tickets.event_id = event_schedule.event_id order by id desc limit 1 ) as ticket_amount'),DB::raw('(SELECT (is_favorite) FROM favourite_events where favourite_events.event_id = E.id and favourite_events.user_id = '.$id.') as is_favorite'))
				->join("event_music_interest_list as I","M.id","=","I.music_interest_id")
				->join("event_list as E","E.id","=","I.event_id")
				->where("M.id",$music->id)
				->join("event_schedule","event_schedule.event_id","=","E.id")
				->where("E.status", 2)
				->whereIn("E.id",$favorite_event_id)
				->where("M.id",$music->id)
				->where(function($query) use($favourites,$id){
					$query->whereIn('event_schedule.id',$favourites);
					$query->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id));
					$query->whereNotIn('event_schedule.user_id',$this->get_user_block_by_admin($id));
				})
				->orderBy("M.id","DESC")
				->get();

			}
		}
		return view('website.favourite_events',["favorite" => $favorite,"music_interest" => $musics]);
	}

	public function pastEvent(Request $request,$date = null) {
		$user = auth()->guard('web')->user();
		
		if($user) {
			if($user) {
				if($user->blockStatus == 1){
	                Auth::logout();
	                Session::flash('danger', 'You are blocked by admin.');
	                return redirect('website/login')->withInput();
	            }
				if($user->role == 1){
					Auth::logout();
					$request->session()->flush();
					$request->session()->regenerate();
					return redirect(url('website/login'))->withInput();

				}
			}
		}
		$current_date = date('Y-m-d');
		$data = array();
		$public_interest = PublicInterest::all();
		$music_interest = MusicInterest::all();
		$match_date=date('Y-m-d H:i:s');
		// $event_time = $this->is_require($request->input('event_time'),'event_time');
		$end_dt=date('Y-m-d');
		$time=date('H:i:s');
		$date_range=date('Y-m-d', strtotime("+2 week", strtotime($end_dt)));	
		$match_date_range=date('Y-m-d', strtotime("+2 week", strtotime($end_dt)));
		if($user) {
			$id = $user->id;
			// $UserPublicInterest = UserPublicInterest::where('user_id',$user->id)->get();
			$favorite_event_id = FavouriteEvent::where(['user_id'=> $user->id,'is_favorite' => 1])->pluck('event_id')->toarray();
			if($public_interest) {
				foreach ($public_interest as $public) {
					$data[$public->name] = DB::table("public_interest as P")
					->select("P.name as interest_name","E.event_name","event_video_url","E.event_image_url","E.event_media_type","E.id","E.ticket_amount",DB::raw("TIME_FORMAT(E.event_time, '%h:%i %p') as event_time"))
					->join("event_public_interest_list as I","P.id","=","I.public_interest_id")
					->join("event_list as E","E.id","=","I.event_id")
											// ->where(["E.event_date" => $current_date])
					->where("P.id",$public->id)
					->join("event_schedule","event_schedule.event_id","=","E.id")
					->where(function($query) use($match_date){
						$query->where(['E.submit_by'=>2,'E.status'=>2]);
						$query->where('event_schedule.event_end_date_time','<',$match_date);
					})
					->orderBy('event_schedule.event_start_date_time','ASC')
					->get();
					
				}
				// return $data;	
			}
		}
		return view('website.Past-events',["data" => $data]);
	}


	public function pastEventView(Request $request) {
		$users = auth()->guard('web')->user();
		if($users) {
			if($users->blockStatus == 1){
                Auth::logout();
                Session::flash('danger', 'You are blocked by admin.');
                return redirect('website/login')->withInput();
            }
		}
		
		$get_event_id=DB::table('event_schedule')->where(['event_id'=>$request->event_id])->first();
		// print_r($get_event_id);die;

		$eachEvent= EventList::
		leftJoin('event_schedule', 'event_schedule.event_id', '=', 'event_list.id')
		->where(['event_schedule.event_id' =>$request->event_id])->first();

		$carbon = Carbon::parse($eachEvent->event_time);
		$eachEvent->event_time = $carbon->format('g:i A');

		$total_posts =  DB::table('post_list')->where(['event_id'=>$eachEvent->event_id,'status'=>1])->count();

		$event_id = $get_event_id->event_id;
		$event_music_interest_list=DB::table('event_music_interest_list')->where(['event_id'=>$event_id])->pluck('music_interest_id');
		$event_public_interest_list=DB::table('event_public_interest_list')->where(['event_id'=>$event_id])->pluck('public_interest_id');

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

		$FavouriteEvent = FavouriteEvent::where(['user_id'=> $users->id,'event_id'=>$event_id])->first();
		// return $eachEvent;
		return view('website.website-view_2',compact('eachEvent','total_posts','FavouriteEvent','public_interest_id','music_interest_id'));
	}

	public function createMotive(Request $request) {
		if($request->isMethod('get')) {
			$user = auth()->guard('web')->user();
			if($user) {
				if($user->blockStatus == 1){
	                Auth::logout();
	                Session::flash('danger', 'You are blocked by admin.');
	                return redirect('website/login')->withInput();
	            }
				$user_id = $user->id; 
				$music_interest = MusicInterest::all();
				$public_interest = PublicInterest::all();

				$sender_id = Friend::where(['sender_id'=> $user_id,'request_status'=>1])->pluck('receiver_id')->toArray();
				$receiver_id = Friend::where(['receiver_id'=> $user_id,'request_status'=>1])->pluck('sender_id')->toArray();
				$friends = array_merge($sender_id,$receiver_id);
				$users = User::whereIn('id',$friends)->distinct('id')->select('id','name','image_url')->get();
				return view('website.create-motiv',compact('music_interest','public_interest','users'));			
			} else {
				return redirect(url('website/login'));
			}
		}
		if($request->isMethod('post')) {

			$user = auth()->guard('web')->user();
			$id = $user->id; 
			$errors = [
				'event_name.required'	=>  'Please enter event name.',
				'location.required'   =>  'Please enter location.',
				'start_date.required' =>  'Please select a start date.',
				'end_date.required'   =>  'Please select a end date.',
				'renew.required'      =>  'Please enter renew list.',
				'website_url.regex' =>  'Please enter valid website url.',
				'descriptions.required' =>  'Please enter descriptions.',
				'repeat_interval.required' =>  'Please enter renew listing.',
				'descriptions.max' =>  'Descriptions may not be greater than 500 characters.',              
				'phone_number.regex' => 'Please enter a valid phone number.',
				'phone_number.max' => 'Phone number should be 10 to 19 digits.',
				'phone_number.min' => 'Phone number should be 10 to 19 digits.',

			];     

			$this->validate($request,[
				'event_name' => 'required|max:50',
				'location'  => 'required',
				'start_date'  => 'required',
				'end_date'  => 'required',
				'repeat_interval'  => 'required',
				'website_url'  => 'nullable|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
				'phone_number' => 'nullable|regex:/^[+0-9]+$/|max:19|min:10',
				'descriptions' => 'required|max:500',
			],$errors);	
		}

		$insert_array = [
			'event_name' => $request->input('event_name'),
			'event_location' => $request->input('location'),
			'event_lat' => !empty($request->input('lat')) ? $request->input('lat') : '0',
			'event_long' => !empty($request->input('long')) ? $request->input('long') : '0',
			'event_date' => date('Y-m-d',(strtotime($request->input('start_date')))),
			'event_date2' => date('D j M',strtotime($request->input('start_date'))),
			'event_time' => date('H:i:s',(strtotime($request->input('start_date')))),
			'end_time' => date('H:i:s',(strtotime($request->input('end_date')))),
			'website' => !empty($request->input('website_url')) ? $request->input('website_url') : '',
			'contact_number' => $request->input('phone_number'),
			'repeat_interval' => !empty($request->input('repeat_interval')) ? $request->input('repeat_interval') : 'one_day',
			'day_name'=>$nameOfDay = date('D', strtotime($request->input('start_date'))),
			'description' => $request->input('descriptions'),
			'post_type' => 2, // 1 => public, 2 => private
			'event_media_type' => !empty($request->input('main')) ? $request->input('main') : 1,
			'submit_by' => 2, //1=>admin, 2=> user, 3 => Organizer
			'status' =>2,
			'user_id' =>$id,
			'updated_at' => Date('Y-m-d H:i:s'),
			'created_at' => Date('Y-m-d H:i:s'),
		];

		$event_date2=date('D j M',strtotime($request->input('start_date'))); 

		if(!empty($request->file('event_video_url'))){
			$video = $request->file('event_video_url');
			$check_type  = $request->file('event_video_url')->getClientOriginalExtension();
			$image_name = str_random(20).'.'.$check_type;
			$path = Storage::putFileAs('public/event_media', $request->file('event_video_url'),$image_name);
			$baseUrl = url('/');
			$baseUrl = str_replace('/public','/',$baseUrl);
			$insert_array['event_video_url'] = $baseUrl.'/storage/app/'.$path;

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
			$insert_array['event_video_url2'] = $baseUrl.'/storage/app/'.$path;

		}else{
			$insert_array['event_video_url2'] = '';
		}

		if(!empty($request->file('event_image_url'))){
			$image_name = str_random(20).'.png';
			$path = Storage::putFileAs('public/event_media',$request->file('event_image_url'),$image_name);
			$baseUrl = url('/');
			$baseUrl = str_replace('/public','/',$baseUrl);
			$insert_array['event_image_url'] = $baseUrl.'/storage/app/'.$path;
		}else{
			$insert_array['event_image_url'] = '';
		}


		if(!empty($request->file('event_image_url2'))){
			$image_name = str_random(20).'.png';
			$path = Storage::putFileAs('public/event_media', $request->file('event_image_url2'),$image_name);
			$baseUrl = url('/');
			$baseUrl = str_replace('/public','/',$baseUrl);
			$insert_array['event_image_url2'] = $baseUrl.'/storage/app/'.$path;
		}else{
			$insert_array['event_image_url2'] = '';
		}	
		if(!empty($request->input('main') == 1)) {
			$insert_array['main'] = 1;
			if($request->input('event_media_type')== 1) {
				$insert_array['event_media_type'] = 1;
			} else {
				$insert_array['event_media_type'] = 2;
			}

		} else {
			$insert_array['main'] = 2;
			if($request->input('event_media_type1')== 3) {
				$insert_array['event_media_type'] = 1;
			} else {
				$insert_array['event_media_type'] = 2;
			}
		}

		$insert_array['ticket_amount'] = !empty($request->input('ticket_amount')) ? $request->input('ticket_amount') : '0';
		$insert_array['enable_ticket'] = !empty($request->input('enable_ticket')) ? $request->input('enable_ticket') : '1';
		$insert_array['enable_guest'] = !empty($request->input('enable_guest')) ? $request->input('enable_guest') : '2';
		$insert_array['dress_code'] = !empty($request->input('dress_code')) ? $request->input('dress_code') : '';
		$insert_array['age_restrictions'] = !empty($request->input('age_restrictions')) ? $request->input('age_restrictions') : '0';
		$insert_array['id_Required'] = !empty($request->input('id_Required')) ? $request->input('id_Required') : '';
		$insert_array['url'] = !empty($request->input('url')) ? $request->input('url') : '';
		$insert_array['music_int_id']=$request->input('music_interest');
		$insert_array['public_int_id']=$request->input('public_interest');
		$insert_array['post_type'] =2;


		$insert_id = EventList::insertGetId($insert_array);

		$invite_user = $request->input('users');

		if(!empty($invite_user)){
			foreach($invite_user as $invite){
				$check = DB::table('invitations')->where('id',$invite)->first();
				if(!empty($check)){
					$guests = DB::table('invitations')->insert(['event_id'=>$insert_id,'sender_id'=>$id,'receiver_id'=>$invite,'request_status'=>1,'created_at'=>date("Y-m-d h:i:s"),'timestamp'=>round(microtime(true) * 1000)]);
				}
			}
		}

		$guests = DB::table('invitations')->insert(['event_id'=>$insert_id,'sender_id'=>0,'receiver_id'=>$id,'request_status'=>1,'created_at'=>date("Y-m-d h:i:s"),'timestamp'=>round(microtime(true) * 1000)]);
		$public_interest = $request->input('public');
		$music_interest = $request->input('music');
		if(!empty($public_interest)){
			foreach($public_interest as $eachPublic){
				$check = PublicInterest::find($eachPublic);
				if(!empty($check)){
					EventPublicInterestList::insert(['event_id'=>$insert_id,'public_interest_id'=>$eachPublic, 'updated_at'=>Date('Y-m-d H:i:s'),'created_at' => Date('Y-m-d H:i:s')]);
				}
			}
		}
		if(!empty($music_interest) > 0){
			foreach($music_interest as $eachPublic){
				$check = MusicInterest::find($eachPublic);
				if(!empty($check)){
					EventMusicInterestList::insert(['event_id'=>$insert_id,'music_interest_id'=>$eachPublic, 'updated_at'=>Date('Y-m-d H:i:s'),'created_at' => Date('Y-m-d H:i:s')]);
				}
			}
		}

		if(!empty($request->add_ticket) && !empty($request->enable_ticket == 1)){
			$return_data=$this->add_tickets($request->add_ticket,$insert_id);
			if($return_data==2){
				$this->responseWithError('Please fill all add ticket fields');
			}
		}

		$this->create_event_schedule($request->input('start_date'),$request->input('end_date'),$request->input('repeat_interval'),$insert_id,$id);
				#send invitation to friends at event create time
		$get_lat_id=DB::table('event_schedule')
		->whereRaw("event_schedule.id IN (select MIN(event_schedule.id) FROM event_schedule WHERE event_id = '$insert_id')")
		->first();

		$get_event=DB::table('event_list')->where(['id'=>$insert_id])->first();
		$userss = $request->input('users');

		if(!empty($userss)){      
			foreach($userss as $friend_id ){		
				$get_friend_name=DB::table('users')->where(['id'=>$friend_id])->first();
				$get_friend = DB::table('invitations')->insert(['sender_id'=>$id,'receiver_id'=>$friend_id,'event_id'=>$insert_id,'sub_event_id'=>$get_lat_id->id,'created_at'=>date("Y-m-d h:i:s"),'timestamp'=>round(microtime(true) * 1000)]);
				 
			}   
		} 

		Session::flash('message', 'Event created successfully.'); 
		return redirect('website/current-events')->withInput();

	}

	public function currentFilter(Request $request) {
		$all = $request->session()->get('public');
		$public = json_decode(base64_decode($all));

		$music = $request->session()->get('music');
		$music_interest = json_decode(base64_decode($music));

		return view('website.current_event_filter',["data" => $public,"music_interest" => $music_interest]);
	}


	public function search_by_current(Request $request) {
		$user = auth()->guard('web')->user();

		if($user) {
			if($user->blockStatus == 1){
                Auth::logout();
                Session::flash('danger', 'You are blocked by admin.');
                return redirect('website/login')->withInput();
            }
			$user_id = $user->id;
		} else{
			$user_id = 0;
		}

		$current_date = date('Y-m-d');
		$data = array();
		$public_interest = PublicInterest::all();
		$music_interest = MusicInterest::all();
		$match_date=date('Y-m-d H:i:00'); 
		$end_dt=date('Y-m-d');
		$time=date('H:i:s');
		$date_range=date('Y-m-d', strtotime("+2 week", strtotime($end_dt)));	
		$match_date_range=date('Y-m-d H:i', strtotime("+2 week", strtotime($end_dt)));
		$search_by_name = $request->search_by_name;

		if($public_interest) {
			foreach ($public_interest as $public) {
				$data[$public->name] = DB::table("public_interest as P")
				->select("P.name as interest_name","E.event_name","event_video_url","E.event_image_url","event_video_url2","E.event_image_url2","main","E.event_media_type","event_schedule.event_id","event_schedule.id",DB::raw("TIME_FORMAT(event_schedule.event_time, '%h:%i %p') as event_time"),DB::raw('(SELECT (ticket_amount) FROM tickets where tickets.event_id = event_schedule.event_id order by id desc limit 1 ) as ticket_amount'),DB::raw('(SELECT (is_favorite) FROM favourite_events where favourite_events.event_id = E.id and favourite_events.user_id = '.$user_id.') as is_favorite'))
				->join("event_public_interest_list as I","P.id","=","I.public_interest_id")
				->join("event_list as E","E.id","=","I.event_id")
				->where("P.id",$public->id)
				->join("event_schedule","event_schedule.event_id","=","E.id")
				->where(function($qu1) use ($search_by_name){
					$qu1->where('event_name', 'like', '%' . $search_by_name . '%')
					->where(['status' =>2]);
				})
				->where(function($query) use($match_date){
					$query->where(['E.status'=>2]);
					$query->whereRaw('(unix_timestamp(event_schedule.event_start_date_time) <= unix_timestamp("'.$match_date.'") and unix_timestamp(event_schedule.event_end_date_time) >= unix_timestamp("'.$match_date.'"))');
				})
				->orderBy("P.id","DESC")
				->get();		
			// return $data;	
			}
		}	

		$musics = array();
		if($music_interest) {
			foreach ($music_interest as $music) {
				$musics[$music->name] = DB::table("music_interest as M")
				->select("M.name as interest_name","E.event_name","event_video_url","E.event_image_url","event_video_url2","E.event_image_url2","main","E.event_media_type","event_schedule.event_id","event_schedule.id",DB::raw("TIME_FORMAT(event_schedule.event_time, '%h:%i %p') as event_time"),DB::raw('(SELECT (ticket_amount) FROM tickets where tickets.event_id = event_schedule.event_id order by id desc limit 1 ) as ticket_amount'),DB::raw('(SELECT (is_favorite) FROM favourite_events where favourite_events.event_id = E.id and favourite_events.user_id = '.$user_id.') as is_favorite'))
				->join("event_music_interest_list as I","M.id","=","I.music_interest_id")
				->join("event_list as E","E.id","=","I.event_id")	
				->where("M.id",$music->id)
				->join("event_schedule","event_schedule.event_id","=","E.id")
				->where(function($qu1) use ($search_by_name){
					$qu1->where('event_name', 'like', '%' . $search_by_name . '%')
					->where(['status' =>2]);
				})
				->where(function($query) use($match_date){
					$query->where(['E.status'=>2]);
					$query->whereRaw('(unix_timestamp(event_schedule.event_end_date_time) >= unix_timestamp("'.$match_date.'") and unix_timestamp(event_schedule.event_start_date_time) <= unix_timestamp("'.$match_date.'"))');
				})
				->orderBy("M.id","DESC")
				->get();

			}
		}

		$event_interest = DB::table('event_public_interest_list')->pluck('event_id');
		$music_interest = DB::table('event_music_interest_list')->pluck('event_id');

		$other_category = DB::table("event_list as E")
		->select("E.event_name","event_video_url","E.event_image_url","event_video_url2","E.event_image_url2","main","E.event_media_type","event_schedule.event_id","event_schedule.id",DB::raw("TIME_FORMAT(event_schedule.event_time, '%h:%i %p') as event_time"),DB::raw('(SELECT (ticket_amount) FROM tickets where tickets.event_id = event_schedule.event_id order by id desc limit 1 ) as ticket_amount'),DB::raw('(SELECT (is_favorite) FROM favourite_events where favourite_events.event_id = E.id and favourite_events.user_id = '.$user_id.') as is_favorite'))
		->whereNotIn("E.id",$event_interest)
		->whereNotIn("E.id",$music_interest)	
		->join("event_schedule","event_schedule.event_id","=","E.id")
		->where(function($qu1) use ($search_by_name){
			$qu1->where('event_name', 'like', '%' . $search_by_name . '%')
			->where(['status' =>2]);
		})

		->where(function($query) use($match_date){
			$query->where(['E.status'=>2]);
			$query->whereRaw('(unix_timestamp(event_schedule.event_start_date_time) <= unix_timestamp("'.$match_date.'") and unix_timestamp(event_schedule.event_end_date_time) >= unix_timestamp("'.$match_date.'"))');
		})
		->orderBy("event_schedule.event_start_date_time","DESC")
		->get();
		return view('website.current_events',["data" => $data,"music_interest" => $musics,"now_category" => $other_category]);
	}

	public function search_by_upcoming(Request $request,$date = null) {
		$user = auth()->guard('web')->user();

		if($user) {
			if($user->blockStatus == 1){
                Auth::logout();
                Session::flash('danger', 'You are blocked by admin.');
                return redirect('website/login')->withInput();
            }
			$user_id = $user->id;
		} else{
			$user_id = 0;
		}

		$current_date = date('Y-m-d');
		$data = array();
		$public_interest = PublicInterest::all();
		$music_interest = MusicInterest::all();
		$match_date=date('Y-m-d H:i:00');
		$end_dt=date('Y-m-d');
		$time=date('H:i:s');
		$date_range=date('Y-m-d', strtotime("+2 week", strtotime($end_dt)));	
		$match_date_range=date('Y-m-d H:i', strtotime("+2 week", strtotime($end_dt)));
		$search_by_name = $request->search_by_name;


		if($public_interest) {
			foreach ($public_interest as $public) {
				$upcoming[$public->name] = DB::table("public_interest as P")
				->select("P.name as interest_name","E.event_name","E.event_date","event_video_url","E.event_image_url","event_video_url2","E.event_image_url2","main","E.event_media_type","event_schedule.event_id","event_schedule.id",DB::raw("TIME_FORMAT(event_schedule.event_time, '%h:%i %p') as event_time"),DB::raw('(SELECT (ticket_amount) FROM tickets where tickets.event_id = event_schedule.event_id order by id desc limit 1 ) as ticket_amount'),DB::raw('(SELECT (is_favorite) FROM favourite_events where favourite_events.event_id = E.id and favourite_events.user_id = '.$user_id.') as is_favorite'))
				->join("event_public_interest_list as I","P.id","=","I.public_interest_id")
				->join("event_list as E","E.id","=","I.event_id")
				->where("P.id",$public->id)	
				->join("event_schedule","event_schedule.event_id","=","E.id")
				->where(function($qu1) use ($search_by_name){
					$qu1->where('event_name', 'like', '%' . $search_by_name . '%')
					->where(['status' =>2]);
				})
				->where(function($query) use($match_date,$match_date_range){
					$query->where(['E.status'=>2]);
					$query->whereRaw('(unix_timestamp(event_schedule.event_start_date_time) > unix_timestamp("'.$match_date.'") and unix_timestamp(event_schedule.event_start_date_time) between  unix_timestamp("'.$match_date.'") and unix_timestamp("'.$match_date_range.'"))');
				})
				->orderBy('event_schedule.event_start_date_time','ASC')
				->get();
			}
				// return $upcoming;	
		}

		$musics = array();
		if($music_interest) {
			foreach ($music_interest as $music) {
				$musics[$music->name] = DB::table("music_interest as M")
				->select("M.name as interest_name","E.event_name","event_video_url","E.event_image_url","event_video_url2","E.event_image_url2","main","E.event_media_type","event_schedule.event_id","event_schedule.id",DB::raw("TIME_FORMAT(event_schedule.event_time, '%h:%i %p') as event_time"),DB::raw('(SELECT (ticket_amount) FROM tickets where tickets.event_id = event_schedule.event_id order by id desc limit 1 ) as ticket_amount'),DB::raw('(SELECT (is_favorite) FROM favourite_events where favourite_events.event_id = E.id and favourite_events.user_id = '.$user_id.') as is_favorite'))
				->join("event_music_interest_list as I","M.id","=","I.music_interest_id")
				->join("event_list as E","E.id","=","I.event_id")	
				->where("M.id",$music->id)
				->join("event_schedule","event_schedule.event_id","=","E.id")
				->where(function($qu1) use ($search_by_name){
					$qu1->where('event_name', 'like', '%' . $search_by_name . '%')
					->where(['status' =>2]);
				})
				->where(function($query) use($match_date,$match_date_range){
					$query->where(['E.status'=>2]);
					$query->whereRaw('(unix_timestamp(event_schedule.event_start_date_time) > unix_timestamp("'.$match_date.'") and unix_timestamp(event_schedule.event_start_date_time) between  unix_timestamp("'.$match_date.'") and unix_timestamp("'.$match_date_range.'"))');
				})
				->orderBy("M.id","DESC")
				->get();

			}
		}
		$event_interest = DB::table('event_public_interest_list')->pluck('event_id');
		$music_interest = DB::table('event_music_interest_list')->pluck('event_id');

		$upcoming_category = DB::table("event_list as E")
		->select("E.event_name","event_video_url","E.event_image_url","event_video_url2","E.event_image_url2","main","E.event_media_type","event_schedule.event_id","event_schedule.id","E.ticket_amount","event_schedule.event_start_date_time",DB::raw("TIME_FORMAT(event_schedule.event_time, '%h:%i %p') as event_time"),DB::raw('(SELECT (is_favorite) FROM favourite_events where favourite_events.event_id = E.id and favourite_events.user_id = '.$user_id.') as is_favorite'))
		->whereNotIn("E.id",$event_interest)
		->whereNotIn("E.id",$music_interest)
		->join("event_schedule","event_schedule.event_id","=","E.id")
		->where(function($qu1) use ($search_by_name){
			$qu1->where('event_name', 'like', '%' . $search_by_name . '%')
			->where(['status' =>2]);
		})
		->where(function($query) use($match_date,$match_date_range){
			$query->where(['E.status'=>2]);
			$query->whereRaw('(unix_timestamp(event_schedule.event_start_date_time) > unix_timestamp("'.$match_date.'") and unix_timestamp(event_schedule.event_start_date_time) between  unix_timestamp("'.$match_date.'") and unix_timestamp("'.$match_date_range.'"))');
		})
		->orderBy("event_schedule.event_end_date_time","DESC")
		->get();
		return view('website.upcoming_events',["upcoming" => $upcoming,"music_interest" => $musics,"upcoming_category" => $upcoming_category]);
	}

	public function search_by_favorite(Request $request,$date = null) {
		$user = auth()->guard('web')->user();
		$id = $user->id;
		if($user) {
			if($user->blockStatus == 1){
                Auth::logout();
                Session::flash('danger', 'You are blocked by admin.');
                return redirect('website/login')->withInput();
            }
		}
		$favorite_event_id = FavouriteEvent::where(['user_id'=> $user->id,'is_favorite' => 1])->pluck('event_id')->toarray();
		$current_date = date('Y-m-d');
		$data = array();
		// For Current Event
		$public_interest = PublicInterest::all();
		$music_interest = MusicInterest::all();
		$match_date=date('Y-m-d g:i a');
	
		$end_dt=date('Y-m-d');
		$time=date('H:i:s');
		$date_range=date('Y-m-d', strtotime("+2 week", strtotime($end_dt)));	
		$match_date_range=date('Y-m-d H:i', strtotime("+2 week", strtotime($end_dt)));
		$search_by_name = $request->search_by_name;

		$favourites= FavouriteEvent::where(['user_id'=>$user->id, 'is_favorite' => 1])->select('sub_event_id')
		->distinct('sub_event_id')
		->pluck('sub_event_id');	
		if($public_interest) {
			foreach ($public_interest as $public) {
				$favorite[$public->name] = DB::table("public_interest as P")
				->select("P.name as interest_name","E.event_name","event_video_url","E.event_image_url","event_video_url2","E.event_image_url2","main","E.event_media_type","is_favorite","event_schedule.event_id","event_schedule.id",DB::raw("TIME_FORMAT(event_schedule.event_time, '%h:%i %p') as event_time"),DB::raw('(SELECT (ticket_amount) FROM tickets where tickets.event_id = event_schedule.event_id order by id desc limit 1 ) as ticket_amount'),DB::raw('(SELECT (is_favorite) FROM favourite_events where favourite_events.event_id = E.id and favourite_events.user_id = '.$id.') as is_favorite'))
				->join("event_public_interest_list as I","P.id","=","I.public_interest_id")
				->join("event_list as E","E.id","=","I.event_id")
				->leftjoin("favourite_events as F","F.event_id","=","E.id")	
				->where("E.status", 2)
				->whereIn("E.id",$favorite_event_id)
				->where("P.id",$public->id)
				->join("event_schedule","event_schedule.event_id","=","E.id")
				->where(function($qu1) use ($search_by_name){
					$qu1->where('event_name', 'like', '%' . $search_by_name . '%')
					->where(['status' =>2]);
				})
				->where(function($query) use($favourites,$id){
					$query->whereIn('event_schedule.id',$favourites);
				})
				->orderBy("P.id","DESC")
				->get();
			}	
		}

		$musics = array();
		if($music_interest) {
			foreach ($music_interest as $music) {
				$musics[$music->name] = DB::table("music_interest as M")
				->select("M.name as interest_name","E.event_name","event_video_url","E.event_image_url","event_video_url2","E.event_image_url2","main","E.event_media_type","is_favorite","event_schedule.event_id","event_schedule.id",DB::raw("TIME_FORMAT(event_schedule.event_time, '%h:%i %p') as event_time"),DB::raw('(SELECT (ticket_amount) FROM tickets where tickets.event_id = event_schedule.event_id order by id desc limit 1 ) as ticket_amount'),DB::raw('(SELECT (is_favorite) FROM favourite_events where favourite_events.event_id = E.id and favourite_events.user_id = '.$id.') as is_favorite'))
				->join("event_music_interest_list as I","M.id","=","I.music_interest_id")
				->join("event_list as E","E.id","=","I.event_id")
				->leftjoin("favourite_events as F","F.event_id","=","E.id")	
				->where("M.id",$music->id)
				->join("event_schedule","event_schedule.event_id","=","E.id")
				->where("E.status", 2)
				->whereIn("E.id",$favorite_event_id)
				->where("M.id",$music->id)
				->where(function($qu1) use ($search_by_name){
					$qu1->where('event_name', 'like', '%' . $search_by_name . '%')
					->where(['status' =>2]);
				})
				->where(function($query) use($favourites,$id){
					$query->whereIn('event_schedule.id',$favourites);
				})
				->orderBy("M.id","DESC")
				->get();

			}
		}

		
		return view('website.favourite_events',["favorite" => $favorite,"music_interest" => $musics]);
	}




	public function filter(Request $request) {
		if($request->isMethod('get')) {
			$user = auth()->guard('web')->user();
			if($user) {
				if($user->blockStatus == 1){
	                Auth::logout();
	                Session::flash('danger', 'You are blocked by admin.');
	                return redirect('website/login')->withInput();
	            }
			}	
			$public_interest = PublicInterest::all();
			$music_interest = MusicInterest::all();
			return view('website.filter',compact('public_interest','music_interest'));
		}
		if($request->isMethod('post')) {
			$public_interest = $request->public;
			$music_interest = $request->music;
			$miles = !empty($request->input('miles')) ? $request->input('miles') : '10';
			$event_date = $request->date;
			$lat = $request->lat;
			$long = $request->lon;
			$now = Carbon::now();
			$subDay=$now->subDay('1');
			$date=$now->toDateTimeString();
			$end_dt=date('Y-m-d H:i:s');
			$match_date = date('Y-m-d'); 
			$date_range=date('Y-m-d', strtotime("+2 week", strtotime($end_dt)));	
			$match_date_range=date('Y-m-d H:i:00', strtotime("+2 week", strtotime($end_dt)));

			$date_range=date('Y-m-d H:i:s', strtotime("+30 day", strtotime($end_dt)));

			if($public_interest) {
				$public_interests = PublicInterest::whereIn('id',$public_interest)->get();
			} else {
				$public_interests = PublicInterest::all();
			}

			if($music_interest) {
				$music_interests = MusicInterest::whereIn('id',$music_interest)->get();
			} else {
				$music_interests = MusicInterest::all();
			}

			$user = auth()->guard('web')->user();
			if($user) {
				$this->checkBlock($user->id);
				$user_id = $user->id;
			} else{
				$user_id = 0;
			}

			$data = array();
			$query_distance = '(3959 * acos( cos( radians("'.$lat.'") ) * cos( radians( event_lat ) ) * cos( radians( event_long ) - radians("'.$long.'") ) + sin( radians("'.$lat.'") ) * sin( radians( event_lat ) ) ) )';

			if($public_interests && !empty($lat) && !empty($long) && !empty($event_date)) {
				foreach ($public_interests as $public) {
					$data[$public->name] = DB::table("public_interest as P")
					->select("P.name as interest_name","E.event_name","event_video_url","E.event_image_url","event_video_url2","E.event_image_url2","main","E.event_media_type","event_schedule.event_id","event_schedule.id",DB::raw("TIME_FORMAT(event_schedule.event_time, '%h:%i %p') as event_time"),DB::raw('(SELECT (ticket_amount) FROM tickets where tickets.event_id = event_schedule.event_id order by id desc limit 1 ) as ticket_amount'),DB::raw('(SELECT (is_favorite) FROM favourite_events where favourite_events.event_id = E.id and favourite_events.user_id = '.$user_id.') as is_favorite'))

					->join("event_public_interest_list as I","P.id","=","I.public_interest_id")
					->join("event_list as E","E.id","=","I.event_id")
					->where("P.id",$public->id)
					->join("event_schedule","event_schedule.event_id","=","E.id")
					->where(function($query) use($event_date){
						$query->where(['E.status'=>2]);
						$query->whereRaw('(unix_timestamp(event_schedule.event_end_date_time) >= unix_timestamp("'.$event_date.'") and unix_timestamp(event_schedule.event_start_date_time) <= unix_timestamp("'.$event_date.'"))');
					})
					->where(DB::raw($query_distance),'<=',$miles)
					->orderBy("P.id","DESC")
					->get();

				}
			} else {
				foreach ($public_interests as $public) {
					$data[$public->name] = DB::table("public_interest as P")
					->select("P.name as interest_name","E.event_name","event_video_url","E.event_image_url","event_video_url2","E.event_image_url2","main","E.event_media_type","event_schedule.event_id","event_schedule.id",DB::raw("TIME_FORMAT(event_schedule.event_time, '%h:%i %p') as event_time"),DB::raw('(SELECT (ticket_amount) FROM tickets where tickets.event_id = event_schedule.event_id order by id desc limit 1 ) as ticket_amount'),DB::raw('(SELECT (is_favorite) FROM favourite_events where favourite_events.event_id = E.id and favourite_events.user_id = '.$user_id.') as is_favorite'))

					->join("event_public_interest_list as I","P.id","=","I.public_interest_id")
					->join("event_list as E","E.id","=","I.event_id")
					->where("P.id",$public->id)
					->join("event_schedule","event_schedule.event_id","=","E.id")
					->where(function($query) use($match_date,$match_date_range){
						$query->where(['E.status'=>2]);
						$query->whereRaw('(unix_timestamp(event_schedule.event_start_date_time) > unix_timestamp("'.$match_date.'") and unix_timestamp(event_schedule.event_start_date_time) between  unix_timestamp("'.$match_date.'") and unix_timestamp("'.$match_date_range.'"))');
					})
					->orderBy("P.id","DESC")
					->get();

				}
			}
			$musics = array();
			if($music_interests && !empty($lat) && !empty($long)) {
				foreach ($music_interests as $music) {
					$musics[$music->name] = DB::table("music_interest as M")
					->select("M.name as interest_name","E.event_name","event_video_url","E.event_image_url","event_video_url2","E.event_image_url2","main","E.event_media_type","event_schedule.event_id","event_schedule.id",DB::raw("TIME_FORMAT(event_schedule.event_time, '%h:%i %p') as event_time"),DB::raw('(SELECT (ticket_amount) FROM tickets where tickets.event_id = event_schedule.event_id order by id desc limit 1 ) as ticket_amount'),DB::raw('(SELECT (is_favorite) FROM favourite_events where favourite_events.event_id = E.id and favourite_events.user_id = '.$user_id.') as is_favorite'))
					->join("event_music_interest_list as I","M.id","=","I.music_interest_id")
					->join("event_list as E","E.id","=","I.event_id")	
					->where("M.id",$music->id)
					->join("event_schedule","event_schedule.event_id","=","E.id")
					->where(function($query) use($event_date){
						$query->where(['E.status'=>2]);
						$query->whereRaw('(unix_timestamp(event_schedule.event_end_date_time) >= unix_timestamp("'.$event_date.'") and unix_timestamp(event_schedule.event_start_date_time) <= unix_timestamp("'.$event_date.'"))');
					})
					->where(DB::raw($query_distance),'<=',$miles)
					->orderBy("M.id","DESC")
					->get();

				}
			} else {
				foreach ($music_interest as $music) {
					$musics[$music->name] = DB::table("music_interest as M")
					->select("M.name as interest_name","E.event_name","event_video_url","E.event_image_url","event_video_url2","E.event_image_url2","main","E.event_media_type","event_schedule.event_id","event_schedule.id",DB::raw("TIME_FORMAT(event_schedule.event_time, '%h:%i %p') as event_time"),DB::raw('(SELECT (ticket_amount) FROM tickets where tickets.event_id = event_schedule.event_id order by id desc limit 1 ) as ticket_amount'),DB::raw('(SELECT (is_favorite) FROM favourite_events where favourite_events.event_id = E.id and favourite_events.user_id = '.$user_id.') as is_favorite'))
					->join("event_music_interest_list as I","M.id","=","I.music_interest_id")
					->join("event_list as E","E.id","=","I.event_id")
					->where("M.id",$music->id)
					->join("event_schedule","event_schedule.event_id","=","E.id")
					->where(function($query) use($match_date,$match_date_range){
						$query->where(['E.status'=>2]);
						$query->whereRaw('(unix_timestamp(event_schedule.event_start_date_time) > unix_timestamp("'.$match_date.'") and unix_timestamp(event_schedule.event_start_date_time) between  unix_timestamp("'.$match_date.'") and unix_timestamp("'.$match_date_range.'"))');
					})
					->orderBy("M.id","DESC")
					->get();

				}
			}
			session()->forget('public');
			session()->forget('music');
			$public = base64_encode(json_encode($data));
			$request->session()->put('public', $public);
			$music = base64_encode(json_encode($musics));
			$request->session()->put('music', $music);
			return redirect(url('website/current-filter'));

		}

	}

	public function notification() {
		return view('website.notification');
	}


	public function add_details(Request $request){

		$user = auth()->guard('web')->user();
		
		if(isset($user->id)==1){
			Auth::logout();
		}

		$user_id=$request->user_id;
        return view('website.add_details',compact('user_id'));	
	
	}
	public function add_details_insert(Request $request){
		$errors = [
			'card_name.required' 		  =>  'Please enter name.',
			'id'            			  => 'Please enter id',
			'card_number.digits_between'  =>  'Card number should not be greater than 16 digits.',
            'cvv.digits_between'          =>  'Cvv should not be greater than 4 digits.',
            'expire_date.required'        =>  'Please enter expiration date.',
            'expire_date.regex'           =>  'Expiration date does not match the format MM/YY.',
            'cvv.required'                =>  'Please enter cvv number.',
            'card_number.required'        =>  'Please enter card number.',
            'card_number.numeric'         => ' Card number must be a number.'
		 ];   

		  $message = [
                
            ];  

	   
		$this->validate($request,[
			'card_name' 	=> 'required|regex:/^[a-zA-Z ]*$/u|max:50',
			'expiry_date' 	=> ['required', 'regex:/^0[1-9]|1[0-2]\/[1-9][0-9]$/'],
			'card_number'	=> 'required|numeric|digits_between:14,16',
			'image'     	=> 'required'
		],$errors);

		// return $request->all();
		$expire_date = $request->expiry_date;
        $month = explode('/', $expire_date)[0];
        $year = explode('/', $expire_date)[1];
            
        if ($year < date('y')) {
        	Session::flash('danger', 'Please enter a valid expiration date');
            return redirect("website/add-details/$request->user_id")->withInput();  
        }




		$add_details = new add_details();

		$add_details->user_id = $request->user_id;
		$add_details->card_name = $request->card_name;
		$add_details->expire_date = $expire_date;
		$add_details->card_number = $request->card_number;

		if(!empty($request->file('image'))){
            $image_name = str_random(20).'.png';
            $path = Storage::putFileAs('public/user_images', $request->file('image'), $image_name);
            $baseUrl = url('/');
            $baseUrl = str_replace('/public','/',$baseUrl);
            $img_url = $baseUrl.'/storage/app/'.$path;
            $add_details->image = $img_url;
        }

		if($add_details->save()){
			Session::flash('message', 'User Card Details Saved Succesfully');
			return redirect("website/add-details/$request->user_id")->withInput();
		}
		else{
			Session::flash('danger', 'Oops Somwthing went Wrong');
			return redirect("website/add-details/$request->user_id")->withInput();
		}

	}

	public function websiteView(Request $request) {
		$users = auth()->guard('web')->user();
		if($users) {
			if($users->blockStatus == 1){
                Auth::logout();
                Session::flash('danger', 'You are blocked by admin.');
                return redirect('website/login')->withInput();
            }
		}
		$get_event_id=DB::table('event_schedule')->where(['id'=>$request->event_id])->first();

		$eachEvent= EventList::
		leftJoin('event_schedule', 'event_schedule.event_id', '=', 'event_list.id')
		->where(['event_schedule.id' =>$request->event_id])->first();

		$carbon = Carbon::parse($eachEvent->event_time);
		$eachEvent->event_time = $carbon->format('g:i A');

		$total_posts =  DB::table('post_list')->where(['event_id'=>$eachEvent->event_id,'status'=>1])->count();

		$event_id = $get_event_id->event_id;
		$event_music_interest_list=DB::table('event_music_interest_list')->where(['event_id'=>$event_id])->pluck('music_interest_id');
		$event_public_interest_list=DB::table('event_public_interest_list')->where(['event_id'=>$event_id])->pluck('public_interest_id');

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

		$FavouriteEvent = FavouriteEvent::where(['user_id'=> $users->id,'event_id'=>$event_id])->first();

		$tickets = DB::table('tickets')->where('event_id',$event_id)->count();
		$ticket_closed = DB::table('tickets')->where(['event_id'=>$event_id,'ticket_status' => 0 ])->first();
		return view('website.website-view',compact('eachEvent','total_posts','FavouriteEvent','public_interest_id','music_interest_id','tickets','ticket_closed'));
	}





	public function addFriend() {
		return view('website.add-friends');
	}

	public function attending() {
		return view('website.attending');
	}

	public function blockUsers() {
		return view('website.block-users');
	}

	public function bookNow(Request $request) {
		if($request->isMethod('get')) {
			$event_id = $request->event_id;
			$tickets = DB::table('tickets')->where('event_id',$event_id)->get();

			return view('website.book-now',compact('tickets'));	
		} 

		if($request->isMethod('post')) {
			$user = Auth::user();
			$current_date = Date('Y-m-d H:i:s');

			$current_time = time();
			/*return $request->quant;*/
			$new_data = array();
			$data = $request->get("quant");
			if($data && !empty($data)){
				if(is_array($data)){
					$new_data = array_filter($data,function($element){
						return $element && isset($element[0]) && (int)@$element[0] > 0?true:false;
					});
				}
			}

			$tickets = base64_encode(json_encode($request->quant));
			$request->session()->put('tickets', $tickets);


			// foreach($request->get("quant") as $key => $value) {
			// 	// $ticket_id = $key;
			// 	if($value[0]) {
			// 		$quantity = $value[0];
			// 		$tickets = DB::table('tickets')->where('id',$key)->first();
			// 		// print_r($tickets);die;
			// 		$save_data['user_id'] =$user->id;
			//         $save_data['sub_event_id'] = $request->event_id; 
			//         $save_data['event_id'] = $request->event_id;	
			// 		$save_data['ticket_id'] = $key;
			// 		$save_data['quantity'] = $quantity;
			// 		$save_data['amount'] = $tickets->ticket_amount;
			// 		$save_data['created_at'] = $current_date;
			// 		$last_id=DB::table('bought_tickets')->insertGetId($save_data);
			// 		$image_name=$current_time;
			//         //$qr_data = $this->encrypt_fucntion($last_id,'e');
			// 		$qr_image= QrCode::format('png')->size(400)->encoding('UTF-8')->generate($last_id, './public/qr_image/'.$image_name.".".'png');	
			// 		$get_path=url('public/qr_image/'.$image_name.'.png');
			// 		DB::table('bought_tickets')->where(['id'=>$last_id])->update(['qr_image'=>$get_path]);	

			// 	}
			// }
			return redirect(url('website/buy-now',$request->event_id));
		}
	}

	public function buyNow(Request $request) {

		$all = $request->session()->get('tickets');

		$public = json_decode(base64_decode($all));
			// dd($public);
		$array = [];
		$quan = [];

		foreach ($public as $key => $value) {
			$array[] = $key;
			$quan[] = implode(',', $value);
			$tickets = DB::table('tickets')->where(['id'=>$value])->first();
		}
		$ticket_id = $array;
		$quantity = $quan;
		$array_com = array_combine($ticket_id, $quantity);

		foreach ($array_com as $key => $set_array) {
			$array_key = $key;
			$set_array = $set_array;
			$ticket_with_quantity[] = ['ticket_id_id' => $key, 'ticket_quantity' =>$set_array];
		}

		$final_data = [];
		if(isset($ticket_with_quantity)){
			foreach ($ticket_with_quantity as $data) {
				$tickets = DB::table('tickets')->whereId($data['ticket_id_id'])->first();
				//dd($tickets);
				$final_data[] = collect($tickets)->merge($data);

				if(!empty($data['ticket_quantity'])){
						// $data['ticket_quantity'];
					$bill[] = $tickets->ticket_amount * $data['ticket_quantity'];
				} else {
					$bill[] = 0;
				}
			}

		}else{
			return "error";
		}

		$total_amount = array_sum($bill);
		$request->session()->put('final_ticket',$final_data);
		$tickets = DB::table('tickets')->whereIn('id',$ticket_id)->get();
		return view('website.buy-now',compact('final_data','total_amount'));
	}

	public function deleteBuyTickets(Request $request) {
		$ticket_id = $request->ticket_id;
		$ticket = DB::table('tickets')->whereId($ticket_id)->first();
		$all = $request->session()->get('tickets');
		$public = json_decode(base64_decode($all),true);

		$new_public = array();
		if(!empty($public) && is_array($public)) {
			if(array_key_exists($ticket_id, $public)) {
				unset($public[$ticket_id]);
			}
			foreach ($public as $key => $value) {
				if(!empty($value[0])){
					$new_public[$key] = $value;
				}
			}
		}

		if(count($new_public) > 0 && !empty(key($new_public))) {
			$tickets = base64_encode(json_encode($new_public));
			$request->session()->put('tickets',$tickets);
			return back()->withInput();
		} else {
			return redirect(url("website/book-now/$ticket->event_id"));
		}

	}

	public function payment() {
		return view('website.save-card');
	}

	public function changePassword() {
		return view('website.change-password');
	}


	public function likePost(Request $request) {
		$user = auth()->guard('web')->user(); 
		$post_id = $request->post_id;
		$likes = Like::where(['user_id'=> $user->id,'post_id'=>$post_id])->first();
	 
		if(empty($likes)) {
			$like_post = new Like();
			$like_post->user_id = $user->id;
			$like_post->post_id = $post_id;
			$like_post->like_status = 1;
			$like_post->timestamp = round(microtime(true) * 1000);
			$like_post->save();
			return 'success';
		} 
		if($likes->like_status == 1) {
			$like_post = Like::where(['user_id'=> $user->id,'post_id'=>$post_id])->delete();
			return 'false';
		} 
	}


	public function addToFavourite(Request $request) {
		$user = auth()->guard('web')->user(); 
		$event_id = $request->event_id;
		$sub_event_id = $request->sub_event_id;
		$FavouriteEvent = FavouriteEvent::where(['user_id'=> $user->id,'event_id'=>$event_id])->first();
	 	// print_r($FavouriteEvent);die;
		if(empty($FavouriteEvent)) {
			$fav_event = new FavouriteEvent();
			$fav_event->user_id = $user->id;
			$fav_event->event_id = $event_id;
			$fav_event->sub_event_id = $sub_event_id;
			$fav_event->is_favorite = 1;
			$fav_event->save();
			return 'success';
		} else {
			$FavouriteEvent = FavouriteEvent::where(['user_id'=> $user->id,'event_id'=>$event_id])->delete();
			return 'false';
		}

	}

	public function comments(Request $request) {
		if($request->isMethod('get')) {
			$user = auth()->guard('web')->user();
			if($user) {
				if($user->blockStatus == 1){
	                Auth::logout();
	                Session::flash('danger', 'You are blocked by admin.');
	                return redirect('website/login')->withInput();
	            }
			}

			$post = DB::table('post_list')->where('id',$request->post_id)->first();
			$comments = DB::table('comments')->where('post_id',$post->id)->get();
			foreach ($comments as $comment) {
				$comment->users = User::where('id',$comment->user_id)->select('id','name','image_url')->first();

			}

			$likes = DB::table('likes')->where(['post_id'=>$post->id,'like_status'=>1])->count();
			$like = DB::table('likes')->where(['post_id'=>$post->id,'user_id'=>$user->id])->first();
			$comment = DB::table('comments')->where('post_id',$post->id)->count();
		
			return view('website.comment',compact('post','comments','likes','comment','like'));	
		}

		if($request->isMethod('post')) {

			$user = auth()->guard('web')->user();
			$id = $user->id;
			$errors = [
				'comment.required'	=>  'Please enter comment.',
			];     

			$this->validate($request,[
				'comment' => 'required',    
			],$errors);
			$comment = new CommentList();
			$comment->user_id = $user->id;
			$comment->post_id = $request->post_id;
			$comment->comment = $request->comment;
			$comment->timestamp = round(microtime(true) * 1000);
			if($comment->save()) {
				Session::flash('message', 'Comment added successfully.'); 
				return back()->withInput();
			} else {
				Session::flash('danger', 'Something went wrong. Please try again.'); 
				return back()->withInput();
			}

		}


	}

	public function settings(Request $request) {
		$user = auth()->guard('web')->user();
		if($user) {
			if($user->blockStatus == 1){
                Auth::logout();
                Session::flash('danger', 'You are blocked by admin.');
                return redirect('website/login')->withInput();
            }
		}
		return view('website.settings',compact('user'));
	}


	public function ticketView(Request $request) {
		$user = auth()->guard()->user();
		if($user) {
			$this->checkBlock($user->id);
		}
		$ids=$user->id;
		$event_id = $request->event_id;


		$get_events= EventList::
		leftJoin('event_schedule', 'event_schedule.event_id', '=', 'event_list.id')
		->where(['status' =>2])
		->where('event_schedule.id',$event_id)
		->orderBy('event_schedule.event_start_date_time','ASC')
		->first();



		// return $get_events;
		
		if(!empty($get_events)) {	 	
			$get_events->total_tickets = DB::table('bought_tickets')
			->where(['event_id'=>$get_events->event_id,'sub_event_id'=>$event_id,'user_id'=>$user->id])
			->count('quantity');
		}	
		
		$tickets=DB::table('tickets')
		->leftJoin('bought_tickets','bought_tickets.ticket_id','=','tickets.id')
		->where('bought_tickets.event_id',$get_events->event_id)
		->where('bought_tickets.user_id',$ids)
		->get(); 

		return view('website.ticket-view',compact('tickets','get_events'));
	}

	public function tickets(Request $request) {
		$user = auth()->guard()->user();
		if($user) {
			if($user->blockStatus == 1){
                Auth::logout();
                Session::flash('danger', 'You are blocked by admin.');
                return redirect('website/login')->withInput();
            }
            
			$id= $user->id;
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
			if(count($get_events)>0) {	 
				foreach ($get_events as $key => $get_event) {	
					$get_event->ticket_quantity=DB::table('tickets')
					->leftJoin('bought_tickets','bought_tickets.ticket_id','=','tickets.id')
					->where('bought_tickets.event_id',$get_event->event_id)
					->where('bought_tickets.user_id',$id)
					->count();

				}	    
			}
			return view('website.tickets',compact('get_events'));	
		} else {
			return redirect(url('website/login'));
		}
	}

	public function posts(Request $request) {
		$event_id = $request->event_id;
		$user = auth()->guard('web')->user();
		if($user) {
			if($user->blockStatus == 1){
                Auth::logout();
                Session::flash('danger', 'You are blocked by admin.');
                return redirect('website/login')->withInput();
            }
		}

		$posts = DB::table('post_list')->where(['event_id'=>$event_id,'status'=>1])->orderBy('id','DESC')->get();
		foreach ($posts as $post) {
			$post->comments = DB::table('comments')->where('post_id',$post->id)->count();
			$post->likes = DB::table('likes')->where('post_id',$post->id)->count();
			$post->like_status = Like::where(['user_id'=> $user->id,'post_id'=>$post->id])->first();
		}
		return view('website.posts',compact('posts','event_id'));
	}

	public function addPost(Request $request) {
		if($request->isMethod('get')) {
			$user = auth()->guard('web')->user();
			if($user) {
				if($user->blockStatus == 1){
	                Auth::logout();
	                Session::flash('danger', 'You are blocked by admin.');
	                return redirect('website/login')->withInput();
	            }
			}
			return view('website.add-post');
		}


		if($request->isMethod('post')) {
			$user = auth()->guard('web')->user();
			$id = $user->id;
			$event_id = $request->event_id;
			$errors = [
				'upload_type.required'	=>  'Please select a file type.',
				'description.required'	=>  'Please enter description.',
				'description.max'	    =>  'Descriptions may not be greater than 500 characters.',
				'post_image_url1.image'	    =>  'Post image must be an image.',
			];     

			$this->validate($request,[
				'upload_type' => 'required',
				'post_image_url1' => 'nullable|image|mimes:jpeg,png,jpg', 
				'description' => 'required|max:500',   
			],$errors);
           	// return $request->file('post_image_url1');
			if(!empty($request->file('post_image_url1')) || !empty($request->file('post_video_url'))) {
				$post = new PostList();
				$post->event_id = $event_id;
				$post->user_id = $id;
				$post->text = $request->description;
				$post->status = 1;
				$post->submit_by = 2;
				$post->post_media_type = $request->upload_type;

				if((!empty($request->file('post_image_url1')) && $request->upload_type == 1)){
					$image_name = str_random(20).'.png';
					$path = Storage::putFileAs('public/post_media', $request->file('post_image_url1'),$image_name);

					$baseUrl = url('/');
					$baseUrl = str_replace('/public','/',$baseUrl);
					$post->post_image_url = $baseUrl.'/storage/app/'.$path;
				}else{
					$insert_array['post_image_url'] = '';
				}


				if(!empty($request->file('post_video_url')) && $request->upload_type == 2){
					$check_type  = $request->file('post_video_url')->getClientOriginalExtension();
					$image_name = str_random(20).'.'.$check_type;
					$path = Storage::putFileAs('public/post_media', $request->file('post_video_url'), $image_name);
					$baseUrl = url('/');
					$baseUrl = str_replace('/public','/',$baseUrl);
					$post->post_video_url = $baseUrl.'/storage/app/'.$path;
				}else{
					$insert_array['post_video_url'] = '';
				}

				if($post->save()) {
					Session::flash('message', 'Post created successfully.'); 
					return redirect(url('website/posts',$event_id))->withInput();
				}
			} else {
				Session::flash('danger', 'Please upload image or video.'); 
				return back()->withInput();
			}
		}
	}


	public function invitation(Request $request) {
		$user = auth()->guard('web')->user();
		if($user) {
			if($user->blockStatus == 1){
                Auth::logout();
                Session::flash('danger', 'You are blocked by admin.');
                return redirect('website/login')->withInput();
            }
		}

		$id = $user->id;
		$current_date=date('Y-m-d H:i:s');
		$invitations = DB::table('invitations')
		->where('request_status',2)
		->where('sub_event_id','!=',0)
		->where('receiver_id',$id)->get();

		foreach($invitations as $invitation) {
			$invitation->event	= DB::table('event_list')
			->leftJoin('event_schedule', 'event_schedule.event_id', '=', 'event_list.id')
			->where(['event_schedule.id'=>$invitation->sub_event_id])
			->first(); 
			$invitation->user_detail=DB::table('users')->where(['id'=>$invitation->sender_id])->select('name','email','image_url')->first(); 
		}

		return view('website.invitation',compact('invitations'));
	}

	public function invitationView(Request $request) {
		$user = auth()->guard('web')->user();
		if($user) {
			if($user->blockStatus == 1){
                Auth::logout();
                Session::flash('danger', 'You are blocked by admin.');
                return redirect('website/login')->withInput();
            }
		}

		$id = $user->id;
		$invitation_id = $request->invitation_id;
		$current_date=date('Y-m-d H:i:s');
		$invitations = DB::table('invitations')
		->where(['request_status'=>2,'id' => $invitation_id])
		->where('sub_event_id','!=',0)
		->where('receiver_id',$id)->first();
	
		$event	= DB::table('event_list')
		->leftJoin('event_schedule', 'event_schedule.event_id', '=', 'event_list.id')
		->where('event_schedule.id',$invitations->sub_event_id)
		->first(); 
		
		$check_event=DB::table('invitations')->where(['event_id'=>$event->event_id,'sub_event_id'=>$event->id,'receiver_id'=>$id,/* 'request_status'=>1 */])->first();
		if(!empty($check_event)){
			$event->attend_status=1;
		}else{      
			if($id == $event->user_id){
				$event->attend_status=1;
			}else{
				$event->attend_status=2;
			}
		}
		$invitations->event = $event;

		$event_id = $event->event_id;

		$event_music_interest_list=DB::table('event_music_interest_list')->where(['event_id'=>$event->event_id])->pluck('music_interest_id');
		$event_public_interest_list=DB::table('event_public_interest_list')->where(['event_id'=>$event->event_id])->pluck('public_interest_id');

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

		$FavouriteEvent = FavouriteEvent::where(['user_id'=> $id,'event_id'=>$event_id])->first();
		return view('website.website-first',compact('invitations','FavouriteEvent','music_interest_id','public_interest_id'));
	}

public function acceptInvitation(Request $request) {
	$invitation_id = $request->invitation_id;
	$check_id=DB::table('invitations')->where(['id'=>$invitation_id])->first();
	$accepted_status = 1;
	$update_data = DB::table('invitations')->where('id',$invitation_id)->update(['request_status'=>$accepted_status]);

	$get_friend_name=DB::table('users')->where(['id'=>$check_id->sender_id])->first();
	$get_reciever_name=DB::table('users')->where(['id'=>$check_id->receiver_id])->first();
	$get_event=DB::table('event_schedule')->where(['id'=>$check_id->sub_event_id])->first();		

	DB::table('notification_list')->insert(['user_id'=>$check_id->sender_id,'other_user_id'=>$check_id->receiver_id,'message'=>$get_reciever_name->name.' '.'accepted your event invitation','notification_type'=>1,'timestamp'=>round(microtime(true) * 1000),'created_at' =>Date('Y-m-d H:i:s'),'updated_at' =>Date('Y-m-d H:i:s')]);

	$notify_count=$this->notification_count($check_id->sender_id);

	if($get_friend_name->device_type == 'I' && $get_friend_name->notification_status == 1){
		$message = array('sound' =>1,'message'=>$get_reciever_name->name.' '.'accepted your event invitation',
			'notifykey'=>'accept_invitation','data'=>'Mo-Tiv','title'=>$get_reciever_name->name,'event_id'=>$check_id->sub_event_id,
			'event_date'=>$get_event->event_date,'event_time'=>$get_event->event_time,'notify_count'=>$notify_count);
		$device_token=$get_friend_name->device_token;
		$dd=$this->send_iphone_notification2($device_token,$get_reciever_name->name.' '.'accepted your event invitation','accept_invitation',$message);
	}   
	if($get_friend_name->device_type == 'A' && $get_friend_name->notification_status == 1){
		$message = array('sound' =>1,'message'=>$get_reciever_name->name.' '.'accepted your event invitation',
			'notifykey'=>'accept_invitation','data'=>'Mo-Tiv','title'=>$get_reciever_name->name,'event_id'=>$check_id->sub_event_id,
			'event_date'=>$get_event->event_date,'event_time'=>$get_event->event_time,'notify_count'=>$notify_count);
		$device_token=$get_friend_name->device_token;
		$this->send_android_notification($device_token,$get_reciever_name->name.' '.'accepted your event invitation','accept_invitation',$message);
	}
	Session::flash('message', 'Invitation accepted successfully.'); 
	return redirect(url('website/invitation'))->withInput();		

}


public function rejectInvitation(Request $request) {
	$invitation_id = $request->invitation_id;
	$check_id=DB::table('invitations')->where(['id'=>$invitation_id])->first();

	$get_friend_name=DB::table('users')->where(['id'=>$check_id->sender_id])->first();
	$get_reciever_name=DB::table('users')->where(['id'=>$check_id->receiver_id])->first();
	$get_event=DB::table('event_schedule')->where(['id'=>$check_id->sub_event_id])->first();

	$check_id=DB::table('invitations')->where(['id'=>$invitation_id])->delete();
	Session::flash('message', 'Invitation declined successfully.'); 
	return redirect(url('website/invitation'))->withInput();	
}

public function notificationOff(Request $request) {
	$notification_status = $request->notification_status;
	$user = auth()->guard('web')->user();
	$user->notification_status = $notification_status;
	$user->update();

	return 'success';


}

public function showAge(Request $request) {
	$age_status = $request->age_status;
	$user = auth()->guard('web')->user();
	$user->age_status = $age_status;
	$user->update();

	return 'success';  		

}


Public function termCondition() {
	return view('website.terms&conditions');
}

Public function privacy() {
	return view('website.privacy');
}

Public function faq() {
	return view('website.faq');
}

Public function getInTouch() {
	return view('website.get-in-touch');
}

Public function support() {
	return view('website.support');
}

public function myEvent(Request $request) {
	$user = auth()->guard('web')->user();
	$ids=$user->id;
	if($user) {
		if($user->blockStatus == 1){
                Auth::logout();
                Session::flash('danger', 'You are blocked by admin.');
                return redirect('website/login')->withInput();
            }
	}   

	$now = Carbon::now();
	$date=$now->toDateString();
	$match_date=date('Y-m-d H:i:s');
	$public_interest = PublicInterest::all();
	$music_interest = MusicInterest::all();

	$end_dt=date('Y-m-d');
	$time=date('H:i:s');
	
	$date_range=date('Y-m-d', strtotime("+2 week", strtotime($end_dt)));	
	$match_date_range=date('Y-m-d', strtotime("+2 week", strtotime($end_dt)));


	if($public_interest) {
		foreach ($public_interest as $public) {
			$data[$public->name] = DB::table("public_interest as P")
			->select("P.name as interest_name","E.event_name","event_video_url","E.event_image_url","E.user_id","event_video_url2","E.event_image_url2","main","E.event_media_type","event_schedule.event_id","event_schedule.id",DB::raw("TIME_FORMAT(event_schedule.event_time, '%h:%i %p') as event_time"),DB::raw('(SELECT (ticket_amount) FROM tickets where tickets.event_id = event_schedule.event_id order by id desc limit 1 ) as ticket_amount'))

			->leftjoin("event_public_interest_list as I","P.id","=","I.public_interest_id")
			->where(['E.status' =>2])
			->leftjoin("event_list as E","E.id","=","I.event_id")
			->where("P.id",$public->id)
			->join("event_schedule","event_schedule.event_id","=","E.id")
			->where(['E.user_id'=>$ids])
			->whereDate('event_schedule.event_date','>=',$date)
			->orderBy('event_schedule.event_start_date_time','ASC')
			->get();

		}
	}
		// return $data;
	$musics = array();
	if($music_interest) {
		foreach ($music_interest as $music) {
			$musics[$music->name] = DB::table("music_interest as M")
			->select("M.name as interest_name","E.event_name","event_video_url","E.event_image_url","event_video_url2","E.event_image_url2","main","E.event_media_type","E.user_id","event_schedule.event_id","event_schedule.id",DB::raw("TIME_FORMAT(event_schedule.event_time, '%h:%i %p') as event_time"),DB::raw('(SELECT (ticket_amount) FROM tickets where tickets.event_id = event_schedule.event_id order by id desc limit 1 ) as ticket_amount'))
			->join("event_music_interest_list as I","M.id","=","I.music_interest_id")
			->join("event_list as E","E.id","=","I.event_id")
			->where("M.id",$music->id)
			->join("event_schedule","event_schedule.event_id","=","E.id")
			->where(['E.user_id'=>$ids])
			->whereDate('event_schedule.event_date','=',$date)
			->orderBy('event_schedule.event_start_date_time','ASC')
			->get();

		}

	}

	$other_category = DB::table("event_list as E")
	->select("E.event_name","event_video_url","E.event_image_url","event_video_url2","E.event_image_url2","main","E.event_media_type","event_schedule.event_id","event_schedule.id",DB::raw("TIME_FORMAT(event_schedule.event_time, '%h:%i %p') as event_time"),DB::raw('(SELECT (ticket_amount) FROM tickets where tickets.event_id = event_schedule.event_id order by id desc limit 1 ) as ticket_amount'))
	->join("event_schedule","event_schedule.event_id","=","E.id")
	->where(['E.user_id'=>$ids])
	->whereDate('event_schedule.event_date','>=',$date)
	->orderBy("event_schedule.event_start_date_time","DESC")
	->get();
	return view('website.my-events',["data" => $data,"music_interest" => $musics,"now_category" => $other_category]);

}

public function createEvent(Request $request) {

	if($request->isMethod('get')) {
		$user = auth()->guard('web')->user();
		// return $user;
		if($user) {
			if($user->blockStatus == 1){
                Auth::logout();
                Session::flash('danger', 'You are blocked by admin.');
                return redirect('website/login')->withInput();
            }
			$user_id = $user->id; 
			$music_interest = MusicInterest::all();
			$public_interest = PublicInterest::all();
			$sender_id = Friend::where(['sender_id'=> $user_id,'request_status'=>1])->pluck('receiver_id')->toArray();
			$receiver_id = Friend::where(['receiver_id'=> $user_id,'request_status'=>1])->pluck('sender_id')->toArray();
			$friends = array_merge($sender_id,$receiver_id);
			$users = User::whereIn('id',$friends)->distinct('id')->select('id','name','image_url')->get();
				// $value = $request->session()->flush();
			$value = $request->session()->get('ticket');

			$data = unserialize($value);
			$tickets = [];
			if(!empty($data)) {
				foreach ($data as $value) {
					$tickets[] = unserialize($value);
				}	
			}
			return view('website.create-motiv2',compact('music_interest','public_interest','users','tickets'));			
		} else {
			return redirect(url('website/login'));
		}
	}

	if($request->isMethod('post')) {
		$user = auth()->guard('web')->user();
		$id = $user->id; 

		$errors = [
			'event_name.required'	=>  'Please enter event name.',
			'location.required'   =>  'Please enter location.',
			'start_date.required' =>  'Please select a start date.',
			'end_date.required'   =>  'Please select a end date.',
			'renew.required'      =>  'Please enter renew list.',
			'website_url.regex' =>  'Please enter valid website url.',
			'descriptions.required' =>  'Please enter descriptions.',
			'repeat_interval.required' =>  'Please enter renew listing.',
			'descriptions.max' =>  'Descriptions may not be greater than 500 characters.',              
			'phone_number.regex' => 'Please enter a valid phone number.',
			'phone_number.max' => 'Please enter a valid phone number.',
			'phone_number.min' => 'Please enter a valid phone number.',

		];     

		$this->validate($request,[
			'event_name' => 'required|max:50',
			'location'  => 'required',
			'start_date'  => 'required',
			'end_date'  => 'required',
			'repeat_interval'  => 'required',
			'website_url'  => 'nullable|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
			'phone_number' => 'nullable|regex:/^[+0-9]+$/|max:19|min:10',
			'descriptions' => 'required|max:500',
		],$errors);	
	}

	 $insert_array = [
		'event_name' => $request->input('event_name'),
		'event_location' => $request->input('location'),
		'event_lat' => !empty($request->input('lat')) ? $request->input('lat') : '0',
		'event_long' => !empty($request->input('long')) ? $request->input('long') : '0',
		'event_date' => date('Y-m-d',(strtotime($request->input('start_date')))),
		'event_date2' => date('D j M',strtotime($request->input('start_date'))),
		'event_time' => date('H:i:s',(strtotime($request->input('start_date')))),
		'end_time' => date('H:i:s',(strtotime($request->input('end_date')))),
		'website' => !empty($request->input('website_url')) ? $request->input('website_url') : '',
		'contact_number' => $request->input('phone_number'),
		'repeat_interval' => !empty($request->input('repeat_interval')) ? $request->input('repeat_interval') : 'one_day',
		'day_name'=>$nameOfDay = date('D', strtotime($request->input('start_date'))),
		'description' => $request->input('descriptions'),
			'post_type' => 1, // 1 => public, 2 => private
			'event_media_type' => !empty($request->input('main')) ? $request->input('main') : 1,
			'submit_by' => 3, //1=>admin, 2=> user, 3 => Organizer
			'status' =>1,
			'user_id' =>$id,
			'updated_at' => Date('Y-m-d H:i:s'),
			'created_at' => Date('Y-m-d H:i:s'),
		];

		$event_date2=date('D j M',strtotime($request->input('start_date'))); 

		if(!empty($request->file('event_video_url'))){
			$video = $request->file('event_video_url');
			$check_type  = $request->file('event_video_url')->getClientOriginalExtension();
			$image_name = str_random(20).'.'.$check_type;
			$path = Storage::putFileAs('public/event_media', $request->file('event_video_url'),$image_name);
			$baseUrl = url('/');
			$baseUrl = str_replace('/public','/',$baseUrl);
			$insert_array['event_video_url'] = $baseUrl.'/storage/app/'.$path;

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
			$insert_array['event_video_url2'] = $baseUrl.'/storage/app/'.$path;

		}else{
			$insert_array['event_video_url2'] = '';
		}

		if(!empty($request->file('event_image_url'))){
			$image_name = str_random(20).'.png';
			$path = Storage::putFileAs('public/event_media',$request->file('event_image_url'),$image_name);
			$baseUrl = url('/');
			$baseUrl = str_replace('/public','/',$baseUrl);
			$insert_array['event_image_url'] = $baseUrl.'/storage/app/'.$path;
		}else{
			$insert_array['event_image_url'] = '';
		}


		if(!empty($request->file('event_image_url2'))){
			$image_name = str_random(20).'.png';
			$path = Storage::putFileAs('public/event_media', $request->file('event_image_url2'),$image_name);
			$baseUrl = url('/');
			$baseUrl = str_replace('/public','/',$baseUrl);
			$insert_array['event_image_url2'] = $baseUrl.'/storage/app/'.$path;
		}else{
			$insert_array['event_image_url2'] = '';
		}

		if(!empty($request->file('primary_image'))){
			$image_name = str_random(20).'.png';
			$path = Storage::putFileAs('public/event_media', $request->file('primary_image'),$image_name);
			$baseUrl = url('/');
			$baseUrl = str_replace('/public','/',$baseUrl);
			$insert_array['primary_image'] = $baseUrl.'/storage/app/'.$path;
				// $insert_array['event_video_url2'] = '';
		}else{
			$insert_array['primary_image'] = '';
		} 


		if(!empty($request->input('main') == 1)) {
					// return "1";
			$insert_array['main'] = 1;
			if($request->input('event_media_type')== 1) {
				$insert_array['event_media_type'] = 1;
			} else {
				$insert_array['event_media_type'] = 2;
			}

		} else {
			$insert_array['main'] = 2;
			if($request->input('event_media_type1')== 3) {
				$insert_array['event_media_type'] = 1;
			} else {
				$insert_array['event_media_type'] = 2;
			}
		}

		$insert_array['ticket_amount'] = !empty($request->input('ticket_amount')) ? $request->input('ticket_amount') : '0';
		$insert_array['enable_ticket'] = !empty($request->input('enable_ticket')) ? $request->input('enable_ticket') : '1';
		$insert_array['enable_guest'] = !empty($request->input('enable_guest')) ? $request->input('enable_guest') : '2';
		$insert_array['dress_code'] = !empty($request->input('dress_code')) ? $request->input('dress_code') : '';
		$insert_array['age_restrictions'] = !empty($request->input('age')) ? $request->input('age') : '0';
		$insert_array['id_Required'] = !empty($request->input('id')) ? $request->input('id') : '';
		$insert_array['url'] = !empty($request->input('url')) ? $request->input('url') : '';
		$insert_array['music_int_id']=$request->input('music_interest');
		$insert_array['public_int_id']=$request->input('public_interest');
			// $insert_array['post_type'] =2;

		// return $insert_array;
		 $insert_id = EventList::insertGetId($insert_array);


		$invite_user = $request->input('users');

		if(!empty($invite_user)){
						// return "1";
			foreach($invite_user as $invite){
				$check = DB::table('invitations')->where('id',$invite)->first();
				if(!empty($check)){
					$guests = DB::table('invitations')->insert(['event_id'=>$insert_id,'sender_id'=>$id,'receiver_id'=>$invite,'request_status'=>1,'created_at'=>date("Y-m-d h:i:s"),'timestamp'=>round(microtime(true) * 1000)]);
				}
			}
		}

		$guests = DB::table('invitations')->insert(['event_id'=>$insert_id,'sender_id'=>0,'receiver_id'=>$id,'request_status'=> 1,'created_at'=>date("Y-m-d h:i:s"),'timestamp'=>round(microtime(true) * 1000)]);

				// if($user_type == 3 || $user_type == 2){
		$public_interest = $request->input('public');
		$music_interest = $request->input('music');
		if(!empty($public_interest)){
						// return "1";
			foreach($public_interest as $eachPublic){
				$check = PublicInterest::find($eachPublic);
				if(!empty($check)){
					EventPublicInterestList::insert(['event_id'=>$insert_id,'public_interest_id'=>$eachPublic, 'updated_at'=>Date('Y-m-d H:i:s'),'created_at' => Date('Y-m-d H:i:s')]);
				}
			}
		}
		if(!empty($music_interest) > 0) {
			foreach($music_interest as $eachPublic){
				$check = MusicInterest::find($eachPublic);
				if(!empty($check)){
					EventMusicInterestList::insert(['event_id'=>$insert_id,'music_interest_id'=>$eachPublic, 'updated_at'=>Date('Y-m-d H:i:s'),'created_at' => Date('Y-m-d H:i:s')]);
				}
			}
		}
		$value = $request->session()->get('ticket');
		$data = unserialize($value);
		$tickets = [];
		if(!empty($data)) {
			foreach ($data as $value) {
				$tickets[] = unserialize($value);
			}	
		}
		if(!empty($tickets)){

			foreach($tickets as $ticket){
				$r = Ticket::insert(['event_id'=>$insert_id,'ticket_title'=>$ticket['ticket_title'], 'ticket_description'=>$ticket['ticket_description'], 'ticket_amount'=>$ticket['ticket_amount'], 'ticket_quantity'=>$ticket['ticket_quantity'], 'updated_at'=>Date('Y-m-d H:i:s'),'created_at' => Date('Y-m-d H:i:s')]);

			}
		}
		$value = $request->session()->forget('ticket');

		$this->create_event_schedule($request->input('start_date'),$request->input('end_date'),$request->input('repeat_interval'),$insert_id,$id);
				#send invitation to friends at event create time
		$get_lat_id=DB::table('event_schedule')
		->whereRaw("event_schedule.id IN (select MIN(event_schedule.id) FROM event_schedule WHERE event_id = '$insert_id')")
		->first();

		$get_event=DB::table('event_list')->where(['id'=>$insert_id])->first();
		$userss = $request->input('users');
				// return $friend_ids = explode(',',$request->input('users'));
		if(!empty($userss)){      
			foreach($userss as $friend_id){		
				$get_friend_name=DB::table('users')->where(['id'=>$friend_id])->first();
				$get_friend = DB::table('invitations')->insert(['sender_id'=>$id,'receiver_id'=>$friend_id,'event_id'=>$insert_id,'sub_event_id'=>$get_lat_id->id,'created_at'=>date("Y-m-d h:i:s"),'timestamp'=>round(microtime(true) * 1000)]); 
			}   
		} 

		// $insert_id = EventList::insertGetId($insert_array);
		$event_share_url = url('/').'/website/event_share_url/'.$insert_id;
		$share_url['event_id']	=$insert_id;
		$share_url['event_share_url'] = $event_share_url;
		
		$share_url['user_id']		= $id;
		$share_url['updated_at'] = Date('Y-m-d H:i:s');
		$share_url['created_at'] = Date('Y-m-d H:i:s');
		ShareUrl::insert($share_url);
		$share_url['user_name'] = $user->name;
		$users_email=User::all()->except([1,$id]);
		$emails = array();
		if(count($users_email)<0){
			$this->responseWithError('Users List Is Empty');	
		}
   		foreach($users_email as $email_user){
	   	$emails[]= $email_user->email;
	   }
	   
   	try{
	  	 Mail::send('share_event_details', array('event_share' => $share_url), 
	   	function ($m) use ($emails) {
		  $m->from(env('MAIL_FROM'), 'MoTiv');
		  $m->to($emails)->subject('New Event Posted');
		Session::flash('message', 'Event created successfully.'); 

  	});
   	}
   	catch(\Exception $ex){
			return $ex->getMessage();
			$this->responseWithError('Oops Something wrong');
		 	//$this->responseOk('Contact us request submitted Successfully','');
			}
		Session::flash('message', 'Event created successfully.'); 
		return redirect("website/my-event")->withInput();

		//return redirect("website/event-ticket/$insert_id")->withInput();
	}


	public function addTicket(Request $request) {
		$user = auth()->guard('web')->user();
		$id = $user->id; 
		$errors = [
			'ticket_title.required'          => 'Please enter ticket title.',
			'ticket_description.required'    => 'Please enter ticket description.',
			'ticket_quantity.required'       => 'Please enter ticket quantity.',
			'ticket_amount.required'         => 'Please enter ticket amount.',
			'ticket_quantity.regex'          => 'Ticket quantity must be number.',
			'ticket_amount.regex'            => 'Ticket amount must be number.',
			'ticket_quantity.not_in'          => 'Ticket quantity should be greater than 0.',
			'ticket_amount.not_in'            => 'Ticket amount should be greater than 0.',
		];

		$this->validate($request,[
			'ticket_title'     => 'required|max:30',
			'ticket_description'      => 'required|max:500',
			'ticket_amount'         => 'required|regex:/^[0-9 .]+$/|not_in:0',
			'ticket_quantity'  => 'required|regex:/^[0-9]+$/|not_in:0',
		],$errors);

		$insert_array = [
			'ticket_title' => $request->input('ticket_title'),
			'ticket_description' => $request->input('ticket_description'),
			'ticket_amount' => $request->input('ticket_amount'),
			'ticket_quantity' => $request->input('ticket_quantity'),
		];
	
		$ticket = array(serialize($insert_array));
		if($request->session()->has('ticket')) {
			
			$all = $request->session()->get('ticket');
			$unserialize = unserialize($all);

			if(is_array($unserialize) && !empty($unserialize)) {
				$add_ticket = array_merge($unserialize,$ticket);
			}else{
				$add_ticket = $ticket;
			}
			$request->session()->put('ticket', serialize($add_ticket));
		} else {
			$request->session()->put('ticket', serialize($ticket));
		}

		Session::flash('message', 'Ticket added successfullyl.'); 
		return response()->json([
			'message'=>'Ticket added successfully.'
		]);

	}

	public function eventTicket(Request $request) {
		$user = auth()->guard('web')->user();
		if($user) {
			if($user->blockStatus == 1){
                Auth::logout();
                Session::flash('danger', 'You are blocked by admin.');
                return redirect('website/login')->withInput();
            }
		}

		$event_id = $request->event_id;
		$tickets = DB::table('tickets')->where('event_id',$event_id)->get();
		return view('website.event-ticket',compact('tickets'));
	}


	public function deleteTicket(Request $request) {
		$ticket_id = $request->ticket_id;
		$delete_ticket = DB::table('tickets')->where('id',$ticket_id)->delete(); 
		Session::flash('message', 'Ticket deleted successfully.');
		return back()->withInput();

	}


	public function ticketAdd(Request $request) {
		$add_ticket = new Ticket();
		$add_ticket->ticket_title = $request->ticket_title;
		$add_ticket->ticket_description = $request->ticket_description;
		$add_ticket->ticket_amount = $request->ticket_amount;
		$add_ticket->ticket_quantity = $request->ticket_quantity;
		$add_ticket->created_at = Date('Y-m-d H:i:s');
		$add_ticket->updated_at = Date('Y-m-d H:i:s');
		$add_ticket->save();
		Session::flash('message', 'Ticket created successfully.');
		return back()->withInput();

	}
	public function checkInEvent(Request $request) {
		$user = auth()->guard('web')->user();
		if($user) {
			if($user->blockStatus == 1){
                Auth::logout();
                Session::flash('danger', 'You are blocked by admin.');
                return redirect('website/login')->withInput();
            }
		}

		$user = auth()->guard('web')->user();
		$bought_tickets=DB::table('bought_tickets')
		->distinct('event_id')
		->pluck('event_id');

		$get_event = EventList::whereIn('id',$bought_tickets)
		->where(['user_id'=>$user->id])
		->get();
		foreach ($get_event as  $event) {
			$event->bought_tickets = DB::table('bought_tickets')
			->where('event_id',$event->id)->count();
		}
		// return $get_event;
		return view('website.check-in-event',compact('get_event'));
	}

	public function checkinDetail(Request $request) {
		$user = auth()->guard('web')->user();
		if($user) {
			if($user->blockStatus == 1){
                Auth::logout();
                Session::flash('danger', 'You are blocked by admin.');
                return redirect('website/login')->withInput();
            }
		}

		$event_id = $request->event_id;
		$eachEvent = EventList::where('id',$event_id)->first();
		$ticket = DB::table('tickets')->where(['event_id'=>$event_id])->first();
		return view('website.check-in-detail',compact('eachEvent','ticket'));
	}

	// public function closeSales(Request $request) {
	// 	$event_id = $request->event_id;
	// 	DB::table('tickets')->where(['event_id'=>$event_id])->update(['ticket_status'=>0]);
		

	// 	Session::flash('message', 'Ticket closed successfully.');
	// 	return back()->withInput();


	// }


	public function closeSales(Request $request){
		$user = auth()->guard('web')->user();
		if($user) {
			if($user->blockStatus == 1){
                Auth::logout();
                Session::flash('danger', 'You are blocked by admin.');
                return redirect('website/login')->withInput();
            }
		}

		$email = $user->email;

		$event_id = $request->event_id;
		DB::table('tickets')->where(['event_id'=>$event_id])->update(['ticket_status'=>0]);


		$get_event=DB::table('event_list')->where(['id' =>$request->event_id])->first();
		$get_event->ticket_amount= DB::table('bought_tickets')
					->where('bought_tickets.event_id', '=', $request->event_id)
					->select(DB::raw('sum(amount) AS Amount'))
					->first();				
		$get_event->ticket_quantity= DB::table('bought_tickets')
					->where('bought_tickets.event_id', '=', $request->event_id)
					->select(DB::raw('sum(quantity) AS Quantity'))
					->first();	


					
		
		try{
		   Mail::send('close-ticket-website',['user_data' => $get_event], function ($m) use ($get_event,$email) {
			   $m->from(env('MAIL_FROM'), 'MoTiv');
			   $m->to($email,'App User');
			  // $m->cc('sourabhwa@yopmail.com','App User');
			   $m->subject('Summary of closed sales and revenue');
		   });
		   }catch(\Exception $ex){
			   return $ex->getMessage();
			   $this->responseWithError('Oops Something wrong');
		   }

		Session::flash('message', 'Ticket closed successfully.');
		return back()->withInput();

	}

	public function boughtTickets(Request $request) {
		$user = auth()->guard('web')->user();
		if($user) {
			if($user->blockStatus == 1){
                Auth::logout();
                Session::flash('danger', 'You are blocked by admin.');
                return redirect('website/login')->withInput();
            }
		}

		$event_id = $request->event_id;
		$bought_tickets = DB::table('bought_tickets')
		->Join('tickets','tickets.id','bought_tickets.ticket_id')
		->Join('users','users.id','bought_tickets.user_id')
		->Join('event_list','event_list.id','bought_tickets.event_id')
		->where('bought_tickets.event_id',$event_id)->get();

		return view('website.bought-tickets',compact('bought_tickets','event_id'));
	}

	public function guestListName(Request $request) {
		$user = auth()->guard('web')->user();
		if($user) {
			if($user->blockStatus == 1){
                Auth::logout();
                Session::flash('danger', 'You are blocked by admin.');
                return redirect('website/login')->withInput();
            }
		}

		$event_id = $request->event_id;
		$guest_list_name=DB::table('guest_list_name')->where(['event_id'=>$event_id])->get();
		return view('website.guest-list',compact('guest_list_name','event_id'));
	}

	public function viewGuestList() {
		return view('website.view-guest-list');
	}


	public function addGuest(Request $request) {
		if($request->isMethod('get')) {
			$user = auth()->guard('web')->user();
			if($user) {
				if($user->blockStatus == 1){
                Auth::logout();
                Session::flash('danger', 'You are blocked by admin.');
                return redirect('website/login')->withInput();
            }
			}

			return view('website.add-guest-list');
		}

		if($request->isMethod('post')) {

			$errors = [
				'name.required'	=>  'Please enter name.',
				'email.required'	=>  'Please enter email address.',
				'email.email'	=>  'Please enter valid a email address.',

			];     

			$this->validate($request,[
				'name' => 'required|max:50',
				'email' => 'required|email|max:50',
			],$errors);	


			$guest_list_name_id = $request->guest_name_id;

	

			$save_data['guest_list_name_id'] = $guest_list_name_id;
			$save_data['full_name'] = $request->name;
			$save_data['email'] = $request->email;
			$save_data['organisation'] = $request->organisation;
			$save_data['created_at']=Date('Y-m-d H:i:s');

			$check_guest = DB::table('guests')->where(['guest_list_name_id'=>$guest_list_name_id,'email'=> $request->email])->first();

			if(!empty($check_guest)){
				Session::flash('message', 'Email has already added to the guestlist.Please enter new one.');
				return back()->withInput();	
			}	

			DB::table('guests')->insertGetId($save_data);

			$guest = DB::table('guest_list_name')->where('id',$guest_list_name_id)->first();
			$ticket_id=mt_rand(100000, 999999);
			$last_id = DB::table('bought_tickets')->insertGetId(array(
				'user_id'	=> $guest->user_id,
				'sub_event_id' => $guest->sub_event_id,
				'event_id'	=> $guest->event_id,
				'ticket_id'	=> $ticket_id,
				'amount'	=> 0,
				'quantity'	=> 1,
				'created_at'=> Date('Y-m-d H:i:s'),

			));

			$email = $request->email;
			$image_name=time();
			
			$qr_image= QrCode::format('png')->size(400)->encoding('UTF-8')->generate($ticket_id, './storage/app/public/qr_image/'.$image_name.".".'png');	
			$get_path=url('storage/app/public/qr_image/'.$image_name.'.png');
			// $qr_image= QrCode::format('png')->size(400)->encoding('UTF-8')->generate($last_id, './public/qr_image/'.$image_name.".".'png');	

			// $get_path=url('public/qr_image/'.$image_name.'.png');

			DB::table('bought_tickets')->where(['id'=>$last_id])->update(['qr_image'=>$get_path]);

			$bought_tickets = DB::table('bought_tickets')->where(['id'=>$last_id])->first();


			$event = DB::table('event_list')->where(['id'=> $request->event_id])->first();




			$insert_data['user_name'] = $guest->guest_list_name;
			$insert_data['name'] = $event->event_name;
			$insert_data['address'] = $event->event_location;
			$insert_data['event_date'] = date('j M Y',strtotime($event->event_date));
			$insert_data['event_time'] = $event->end_time;
			$insert_data['end_time'] = $event->end_time;
			$insert_data['day_name'] = $event->day_name;
			$insert_data['dress_code'] = $event->dress_code;
			$insert_data['ticket_id'] = $bought_tickets->ticket_id;
			$insert_data['ticket'] = $bought_tickets->qr_image;



			try{
				Mail::send('ticket-email-web',['user_data' => $insert_data], function ($m) use ($insert_data,$email) {
					$m->from(env('MAIL_FROM'), 'MoTiv');
					$m->to($email,'App User');
					$m->subject('Guestlist Confirmation');
				});
			}catch(\Exception $ex){
				return $ex->getMessage();
				Session::flash('message', 'Oops Something wrong.');
				return back()->withInput();
			}

			Session::flash('message', 'Guest added successfully.');
			return redirect(url("website/guest-list/$guest_list_name_id/$request->event_id"))->withInput();

		}

	}

	public function addGuestName(Request $request) {
		if($request->isMethod('get')) {
			$user = auth()->guard('web')->user();
			if($user) {
				if($user->blockStatus == 1){
	                Auth::logout();
	                Session::flash('danger', 'You are blocked by admin.');
	                return redirect('website/login')->withInput();
	            }
			}
			return view('website.add-guest-name-list');
		}

		if($request->isMethod('post')) {	
			$errors = [
				'name.required'	=>  'Please enter name.',

			];     

			$this->validate($request,[
				'name' => 'required|max:50',
			],$errors);	

			$event_id = $request->event_id;	
			$user = auth()->guard('web')->user();
			$get_event=DB::table('event_schedule')->where(['event_id'=>$event_id])->first();
			// print_r($get_event); die;
			$save_data['user_id']=$user->id;
			$save_data['event_id']=$event_id;
			$save_data['sub_event_id']=$get_event->id;
			$save_data['guest_list_name']=$request->name;
			$save_data['created_at']=Date('Y-m-d H:i:s');

			$check_guest=DB::table('guest_list_name')->insertGetId($save_data);
			Session::flash('message', 'Guest list added successfully.');
			return redirect(url("website/add-guest/$check_guest/$event_id"))->withInput();
		}
	}

	public function guestList(Request $request) {
		$user = auth()->guard('web')->user();
		if($user) {
			if($user->blockStatus == 1){
                Auth::logout();
                Session::flash('danger', 'You are blocked by admin.');
                return redirect('website/login')->withInput();
            }
		}

		$guest_list_name_id = $request->guest_id;
		$guest_list = DB::table('guests')->where('guest_list_name_id',$guest_list_name_id)->get();
		return view('website.view-guest-list',compact('guest_list','guest_list_name_id'));
	}

	public function deleteGuest(Request $request) {
		$guest_id = $request->guest_id;
		$guest_list = DB::table('guests')->where('id',$guest_id)->delete();
		Session::flash('message', 'Guest deleted successfully.');
		return back()->withInput();
	}

	public function checkInGuest(Request $request) {
		$user = auth()->guard('web')->user();
		if($user) {
			if($user->blockStatus == 1){
                Auth::logout();
                Session::flash('danger', 'You are blocked by admin.');
                return redirect('website/login')->withInput();
            }
		}

		

		$event_id = $request->event_id;
		$guest_list_name=DB::table('guest_list_name')->where(['event_id'=>$event_id])->get();
		return view('website.check-in-guest-list',compact('guest_list_name','event_id'));
	}


	public function editEvent(Request $request) {
		if($request->isMethod('get')) {
			$user = auth()->guard('web')->user();
			$event_id = $request->event_id;
			$current_date = Date('Y-m-d');	
			if($user) {
				if($user->blockStatus == 1){
	                Auth::logout();
	                Session::flash('danger', 'You are blocked by admin.');
	                return redirect('website/login')->withInput();
	            }

				$user_id = $user->id; 
				$music_interest = MusicInterest::all();
				$public_interest = PublicInterest::all();
				$sender_id = Friend::where(['sender_id'=> $user_id,'request_status'=>1])->pluck('receiver_id')->toArray();
				$receiver_id = Friend::where(['receiver_id'=> $user_id,'request_status'=>1])->pluck('sender_id')->toArray();
				$friends = array_merge($sender_id,$receiver_id);

				$check_invitation = DB::table('invitations')->where(['sender_id'=>$user_id,'event_id'=>$event_id])->pluck('receiver_id');

				$users = User::whereIn('id',$friends)
				->whereNotIn('id',$check_invitation)
				->distinct('id')->select('id','name','image_url')->get();

				$now = Carbon::now();
				$date=$now->toDateString();
				$event = DB::table('event_list')->where('id',$event_id)->first();
				$sub_event = DB::table('event_schedule')->whereDate('event_schedule.event_date','>=',$date)->where('event_id',$event_id)->first();
				// print_r($sub_event);die;
				$event_public_interest = EventPublicInterestList::where('event_id',$event->id)->get();

				$event_music_interest = EventMusicInterestList::where('event_id',$event->id)->get();

				return view('website.edit-event',compact('music_interest','public_interest','users','event','event_public_interest','event_music_interest','sub_event'));
			}
		}

		if($request->isMethod('post')) {
			$user = auth()->guard('web')->user();
			$id = $user->id;
			// $request->input('end_date');
			// return $request->id;
			$event_id = $request->event_id;
			// return $public_interest = $request->input('public');
			$insert_array = [
				'event_name' => $request->input('event_name'),
				'event_location' => $request->input('location'),
				'event_lat' => $request->input('lat'),
				'event_long' => $request->input('lon'),
				'event_date' => date('Y-m-d',(strtotime($request->input('start_date')))),
				'event_date2' => date('D j M',strtotime($request->input('start_date'))),
				'event_time' => date('H:i:s',(strtotime($request->input('start_date')))),
				'end_time' => date('H:i:s',(strtotime($request->input('end_date')))),
				'website' => !empty($request->input('website_url')) ? $request->input('website_url') : '',
				'contact_number' => $request->input('phone_number'),
				'repeat_interval' => !empty($request->input('repeat_interval')) ? $request->input('repeat_interval') : 'one_day',
				'day_name'=>$nameOfDay = date('D', strtotime($request->input('start_date'))),
				'description' => $request->input('descriptions'),
				'post_type' => 1, // 1 => public, 2 => private
				'event_media_type' => !empty($request->input('main')) ? $request->input('main') : 1,
				'submit_by' => 3, //1=>admin, 2=> user, 3 => Organizer
				'user_id' => $user->id,
				'updated_at' => Date('Y-m-d H:i:s'),
				'created_at' => Date('Y-m-d H:i:s'),
			];

			$event_date2 = date('D j M',strtotime($request->input('start_date'))); 

			if(!empty($request->file('event_video_url'))){
				$video = $request->file('event_video_url');
				$check_type  = $request->file('event_video_url')->getClientOriginalExtension();
				$image_name = str_random(20).'.'.$check_type;
				$path = Storage::putFileAs('public/event_media', $request->file('event_video_url'),$image_name);
				$baseUrl = url('/');
				$baseUrl = str_replace('/public','/',$baseUrl);
				$insert_array['event_video_url'] = $baseUrl.'/storage/app/'.$path;
				$insert_array['event_image_url'] = '';

			}

			if(!empty($request->file('event_video_url2'))){
				$video = $request->file('event_video_url2');
				$check_type  = $request->file('event_video_url2')->getClientOriginalExtension();
				$image_name = str_random(20).'.'.$check_type;
				$path = Storage::putFileAs('public/event_media', $request->file('event_video_url2'),$image_name);
				$baseUrl = url('/');
				$baseUrl = str_replace('/public','/',$baseUrl);
				$insert_array['event_video_url2'] = $baseUrl.'/storage/app/'.$path;
				$insert_array['event_image_url2'] = '';

			}
			

				// $request->file('event_image_url');
			if(!empty($request->file('event_image_url'))){
				$image_name = str_random(20).'.png';
				$path = Storage::putFileAs('public/event_media',$request->file('event_image_url'),$image_name);
				$baseUrl = url('/');
				$baseUrl = str_replace('/public','/',$baseUrl);
				$insert_array['event_image_url'] = $baseUrl.'/storage/app/'.$path;
				$insert_array['event_video_url'] = '';
			} 


			if(!empty($request->file('event_image_url2'))){
				$image_name = str_random(20).'.png';
				$path = Storage::putFileAs('public/event_media', $request->file('event_image_url2'),$image_name);
				$baseUrl = url('/');
				$baseUrl = str_replace('/public','/',$baseUrl);
				$insert_array['event_image_url2'] = $baseUrl.'/storage/app/'.$path;
				$insert_array['event_video_url2'] = '';
			}

			if(!empty($request->file('primary_image'))){
				$image_name = str_random(20).'.png';
				$path = Storage::putFileAs('public/event_media', $request->file('primary_image'),$image_name);
				$baseUrl = url('/');
				$baseUrl = str_replace('/public','/',$baseUrl);
				$insert_array['primary_image'] = $baseUrl.'/storage/app/'.$path;
				// $insert_array['event_video_url2'] = '';
			} 

			if(!empty($request->input('main') == 1)) {
					// return "1";
				$insert_array['main'] = 1;
				if($request->input('event_media_type')== 1) {
					$insert_array['event_media_type'] = 1;
				} else {
					$insert_array['event_media_type'] = 2;
				}

			} else {
				$insert_array['main'] = 2;
				if($request->input('event_media_type1')== 3) {
					$insert_array['event_media_type'] = 1;
				} else {
					$insert_array['event_media_type'] = 2;
				}
			}

			$insert_array['ticket_amount'] = !empty($request->input('ticket_amount')) ? $request->input('ticket_amount') : '0';
			$insert_array['enable_ticket'] = !empty($request->input('enable_ticket')) ? $request->input('enable_ticket') : '1';
			$insert_array['enable_guest'] = !empty($request->input('enable_guest')) ? $request->input('enable_guest') : '1';
			$insert_array['dress_code'] = !empty($request->input('dress_code')) ? $request->input('dress_code') : '';
			$insert_array['age_restrictions'] = !empty($request->input('age')) ? $request->input('age') : '0';
			$insert_array['id_Required'] = !empty($request->input('id')) ? $request->input('id') : '2';
			$insert_array['url'] = !empty($request->input('url')) ? $request->input('url') : '';
			$insert_array['music_int_id']=$request->input('music_interest');
			$insert_array['public_int_id']=$request->input('public_interest');
			
			$event_start_date_time = date('Y-m-d H:i:s', strtotime($request->input('start_date')));
			$event_end_date_time = date('Y-m-d H:i:s', strtotime($request->input('end_date')));
			// $insert_id = EventList::insertGetId($insert_array);
			DB::table('event_list')->where(['id'=>$event_id])->update($insert_array);
			DB::table('event_schedule')->where(['id'=>$event_id])->delete();   

			$invite_user = $request->input('users');



			if(!empty($invite_user)) {
				foreach($invite_user as $invite){
					$check = DB::table('invitations')->where('id',$invite)->first();
					if(!empty($check)){
						$guests = DB::table('invitations')->insert(['event_id'=>$event_id,'sender_id'=>$id,'receiver_id'=>$invite,'request_status'=>1,'created_at'=>date("Y-m-d h:i:s"),'timestamp'=>round(microtime(true) * 1000)]);
					}
				}
			}


			$public_interest = $request->input('public');
			EventPublicInterestList::where('event_id',$event_id)->delete();
			EventMusicInterestList::where('event_id',$event_id)->delete();

			if(!empty($public_interest)){
				foreach($public_interest as $eachPublic){
					$check = PublicInterest::find($eachPublic);
					if(!empty($check)){
				// return "1";
						EventPublicInterestList::insert(['event_id'=>$event_id,'public_interest_id'=>$eachPublic, 'updated_at'=>Date('Y-m-d H:i:s'),'created_at' => Date('Y-m-d H:i:s')]);
					}
				}
			}

			$music_interest = $request->input('music');
			if(!empty($music_interest) > 0){
				foreach($music_interest as $eachPublic){
					$check = MusicInterest::find($eachPublic);
					if(!empty($check)){
						EventMusicInterestList::insert(['event_id'=>$event_id,'music_interest_id'=>$eachPublic, 'updated_at'=>Date('Y-m-d H:i:s'),'created_at' => Date('Y-m-d H:i:s')]);
					}
				}
			}

			DB::table('event_schedule')->where('event_id',$event_id)->delete();
			$this->create_event_schedule($request->input('start_date'),$request->input('end_date'),$request->input('repeat_interval'),$event_id,$id);
				#send invitation to friends at event create time
			$get_lat_id=DB::table('event_schedule')
			->whereRaw("event_schedule.id IN (select MIN(event_schedule.id) FROM event_schedule WHERE event_id = '$event_id')")
			->first();

			$get_event=DB::table('event_list')->where(['id'=>$event_id])->first();
			$userss = $request->input('users');
				// return $friend_ids = explode(',',$request->input('users'));
			if(!empty($userss)){      
				foreach($userss as $friend_id ){		
					$get_friend_name=DB::table('users')->where(['id'=>$friend_id])->first();
					$get_friend = DB::table('invitations')->insert(['sender_id'=>$id,'receiver_id'=>$friend_id,'event_id'=>$event_id,'sub_event_id'=>$get_lat_id->id,'created_at'=>date("Y-m-d h:i:s"),'timestamp'=>round(microtime(true) * 1000)]);
				}   
			} 
	
			Session::flash('message', 'Event updated successfully.'); 
			return redirect('website/my-event')->withInput();
		}
	}

	

	public function emailSubscribe(Request $request) {
		// return "1";
		$errors = [
			'email.required'	=>  'Please enter email.',
		];     
		$this->validate($request,[
			'email' => 'required|email|max:50',
		],$errors);	

		$check_email = DB::table('subscribes')->where('email',$request->email)->first();
		if(empty($check_email)) {
			$save_data['email']=$request->email;
			$save_data['created_at'] = Date('Y-m-d H:i:s');
			$save_data['updated_at'] = Date('Y-m-d H:i:s');
			$check_guest=DB::table('subscribes')->insertGetId($save_data);
			$email = $request->email;
		    // $user = User::where($email);

			try{
				Mail::send('subscribe-email',['user_data' => $save_data], function ($m) use ($save_data,$email) {
					$m->from(env('MAIL_FROM'), 'MoTiv');
					$m->to($email,'App User');
					$m->subject('Subscribe Email');
				});
			}catch(\Exception $ex){
				return $ex->getMessage();
				Session::flash('message', 'Oops Something wrong.');
				return back()->withInput();
			}


			Session::flash('email_message', 'Email subscribes successfully.'); 
			return response()->json([
				'message' => 'Email has been subscribed successfully.'
			]);
		} else {
			Session::flash('email_danger', 'You are already subscribes.'); 
			return response()->json([
				'message' => 'You are already subscribed.' 
			]);
		}
	}

	public function printTickets(Request $request) {
		$event_id = $request->event_id;
		$bought_tickets = DB::table('bought_tickets')
		->Join('tickets','tickets.id','bought_tickets.ticket_id')
		->where('bought_tickets.event_id',$event_id)->get();
		return view('website.print-preview',compact('bought_tickets','event_id'));
	}

	public function printGuestList(Request $request) {
		$event_id = $request->event_id;
		$guest_list_name=DB::table('guest_list_name')->where(['event_id'=>$event_id])->get();
		return view('website.guest-print',compact('guest_list_name','event_id'));
	}

	public function ticketQr(Request $request) {
		$ticket = DB::table('bought_tickets')
		->where(['id'=>$request->id])
		->first();
		$event = DB::table('event_list')->where('id',$ticket->event_id)->first();
		//p rint_r($ticket); die;
		return view('website.qr-code',compact('ticket','event'));
	}

	public function eventDashboard(Request $request) {
		$user = auth()->guard('web')->user();
		if($user) {
			if($user->blockStatus == 1){
                Auth::logout();
                Session::flash('danger', 'You are blocked by admin.');
                return redirect('website/login')->withInput();
            }
		}
		
		$event_id = $request->event_id;
		$bought_tickets = DB::table('bought_tickets')->where(['event_id'=>$event_id]);
		$total_amount = $bought_tickets->sum('amount');
		$total_buy = $bought_tickets->sum('quantity');
		$total_tickets = DB::table('tickets')->where(['event_id'=>$event_id])->sum('ticket_quantity');

		$tickets = DB::table('tickets')->where(['event_id'=>$event_id])->get();

		$ticketss=$tickets->count();

		$guest_event_user=abs($total_buy-$ticketss);

		
		foreach ($tickets as $ticket) {
			$ticket->ticket_sold = DB::table('bought_tickets')->where(['ticket_id'=>$ticket->id])->sum('quantity');
		}


		$min = "2018";
		$max = Carbon::parse()->format('Y');
		$role = "revenue";

		return view('website.dashboard',compact('total_amount','total_tickets','total_buy','tickets','min', 'max', 'role','event_id','guest_event_user'));
	}


	public function analytics(Request $request)
	{
		$type = $request->type;
		$year = $request->year;
		$role = "revenue";
		$event_id = $request->event_id;

		if($type == 'daily'){
			$result['daily_data'] = $this->dailyAnalytics($year, $role, $event_id);
			$result['daily_data_footer'] = $year;
		}else if($type == 'weekly'){
			$result['week_data'] = $this->weekAnalytics($year, $role, $event_id);
			$result['week_data_footer'] = $year;
		}else if($type == 'monthly') {
			$result['month_data'] = $this->monthAnalytics($year, $role, $event_id);
			$result['month_data_footer'] = $year;
		} else if ($type == 'yearly') {
			$result['year_data'] = $this->yearlyAnalytics($role, $event_id);
		}
		print_r(json_encode($result));
	}

#####################################################################################################
	public function dailyAnalytics($year, $role, $event_id)
	{
		$get_daily_data_temp = array();
		for($i = 0; $i <= 365; $i++){
			$dt = Carbon::create($year,1,1,0,0,0);
			$fromDate = $dt->startOfDay()->addDay($i)->toDateString();
			$tillDate = $dt->endOfDay()->toDateString();
			if ($role == "revenue") {
				$data_daily = DB::table('bought_tickets')->where('event_id',$event_id)->whereDate('created_at',$tillDate)
				->sum('amount');
              	// echo $fromDate." ".$tillDate."\n"; 
			}
			if(!empty($data_daily)){
				$get_daily_data_temp[$i] = (int)$data_daily;
			}else {
				$get_daily_data_temp[$i] = 0;
			}
		}
		return $get_daily_data_temp;
       // return 'hllo1';

	}
//-----------------------------------------------//
	public function weekAnalytics($year, $role, $event_id)
	{
        // Week data according Week 1 to week 52
		$get_week_data_temp = array();
		for($i = 0; $i <= 52; $i++){
			$dt = Carbon::create($year,1,1,0,0,0);
			$fromDate = $dt->startOfWeek()->addWeek($i)->toDateString();
			$tillDate = $dt->endOfWeek()->toDateString();
			if ($role == "revenue") {
				$data_week = DB::table('bought_tickets')->where('event_id',$event_id)->whereRaw('unix_timestamp(created_at) between  unix_timestamp("'.$fromDate.'") and unix_timestamp("'.$tillDate.'")')
				->sum('amount');
			}
			if(!empty($data_week)){
				$get_week_data_temp[$i] = (int)$data_week;
			}else {
				$get_week_data_temp[$i] = 0;
			}
		}
		return $get_week_data_temp;
	}

#####################################################################################################

	public function monthAnalytics($year, $role, $event_id)
	{

        // Year data
		$get_year_data_temp = array();
		$dt = Carbon::create($year,1,1,0,0,0);
		for($i = 0; $i < 12; $i++){
			$fromDate = $dt->startOfYear()->addMonth($i)->toDateString();
			$tillDate = $dt->startOfYear()->addMonth($i)->endOfMonth()->toDateString();

			if ($role == "revenue") { 

				$data_year = DB::table('bought_tickets')->where('event_id',$event_id)->whereRaw('unix_timestamp(created_at) between  unix_timestamp("'.$fromDate.'") and unix_timestamp("'.$tillDate.'")')
				->sum('amount');
			}

			$get_year_data_temp[$i]['month'] = $dt->startOfYear()->addMonth($i)->month - 1;
			$get_year_data_temp[$i]['year'] = $dt->startOfYear()->addMonth($i)->year;
			if(!empty($data_year)){
				$get_year_data_temp[$i]['value'] = (int)$data_year;
			}else{
				$get_year_data_temp[$i]['value'] = 0;
			}
		}
		return $get_year_data_temp;
	}

#####################################################################################################

	public function yearlyAnalytics($role, $event_id)
	{
		$data = array();
		if ($role == "revenue") {
			$min_year = 2018;
			$max_year = DB::table('bought_tickets')->select(DB::raw('max(created_at) as max'))->first();
		}


		$min = 2018;
		$max = Carbon::parse($max_year->max)->format('Y');

		$diff = $max - $min;
		$dt = Carbon::create($min - 1, 1, 1, 0, 0, 0);
		for ($i=0; $i<=$diff; $i++) {
			$start_date = $dt->startOfYear()->addYear(1)->toDateString();
			$end_date = $dt->endOfYear()->toDateString();
			if ($role == "revenue") {
				$data_year = DB::table('bought_tickets')->where('event_id',$event_id)->whereRaw('unix_timestamp(created_at) between  unix_timestamp("'.$start_date.'") and unix_timestamp("'.$end_date.'")')
				->sum('amount');
			}
			$data[$i]['year'] = date('Y', strtotime($start_date));
			$data[$i]['value'] = !empty($data_year) ? (int)$data_year : 0;
		}
		return $data;
	}


	public function checkBlock($id) {
		$user = User::find($id);
		if($user->blockStatus == 1){
			Auth::logout();

			Session::flash('danger', 'You are blocked by admin.');
			return redirect(url('website/login'))->withInput();
		}
	}

	public function sendlink(Request $request) {
		$errors = [
			'email.required'	=>  'Please enter email.',
		];     
		$this->validate($request,[
			'email' => 'required|email|max:50',
		],$errors);	
		$email = $request->email;
		$save_data['email'] = $request->email; 
		try{
			Mail::send('link-email',['user_data' => $save_data], function ($m) use ($save_data,$email) {
				$m->from(env('MAIL_FROM'), 'MoTiv');
				$m->to($email,'App User');
				$m->subject('Application link');
			});
		}catch(\Exception $ex){
			// return $ex->getMessage();
			Session::flash('message', 'Oops Something wrong.');
			return back()->withInput();
		}

		// return "1";
		Session::flash('email_message', 'Link sent successfully.'); 
		return response()->json([
			'message' => 'Link sent successfully.'
		]);


	}



	public function event_details(Request $request){

		$event_id = $request->event_id;
		$check_event_id = DB::table('event_list')->where('id',$event_id)->first();
		if(empty($check_event_id)){
			return view('pageNotFound404');
		}
		$get_event = EventList::where(['id'=>$event_id])
		->get();
		return view('website.get_event',compact('get_event'));
	}

	public function event_share_url(Request $request){

			$event_id=$request->event_share_id;
			$check_event_id = DB::table('share_url')->where('event_id',$event_id)->first();
			if(empty($check_event_id)){
				return view('pageNotFound404');
			}
			$get_event = EventList::where(['id'=>$request->event_share_id])
			->get();
			
			return view('event_share_url',compact('get_event'));			

	}



}
