<?php

namespace App\Http\Controllers\Websitessss;

header('Cache-Control: no-store, private, no-cache, must-revalidate');
header('Cache-Control: pre-check=0, post-check=0, max-age=0, max-stale = 0', false);

use DB;
use Session;
use Auth;
use App\User;
use App\Models\Like;
use App\Models\Friend;
use App\Models\CommentList;
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
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\ResponseController;
use Carbon\Carbon;
date_default_timezone_set('Asia/Kolkata');

class WebsiteControllerssss extends ResponseController
{
	public function events(Request $request,$date = null) {
		$user = auth()->guard('web')->user();
		
		if($user) {
			$id=$request->input('user_id');
			if(empty($id)){
				// $user_id = $user->id;
				if($user->blockStatus == 1){
					Auth::logout();
			        $request->session()->flush();
			        $request->session()->regenerate();

			        Session::flash('danger', 'You are blocked by admin.');
            		return redirect(url('website/login'))->withInput();

				}
			}
		}

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
		
		if($user) {
			$id = $user->id;
			// $UserPublicInterest = UserPublicInterest::where('user_id',$user->id)->get();
			$favorite_event_id = FavouriteEvent::where(['user_id'=> $user->id,'is_favorite' => 1])->pluck('event_id')->toarray();
			if($public_interest) {
				foreach ($public_interest as $public) {
					$data[$public->name] = DB::table("public_interest as P")
											->select("P.name as interest_name","E.event_name","event_video_url","E.event_image_url","event_video_url2","E.event_image_url2","main","E.event_media_type","event_schedule.id","E.ticket_amount",DB::raw("TIME_FORMAT(event_schedule.event_time, '%h:%i %p') as event_time"))
											->join("event_public_interest_list as I","P.id","=","I.public_interest_id")
											->join("event_list as E","E.id","=","I.event_id")
											// ->where(["E.event_date" => $current_date])
											->where("P.id",$public->id)
											->join("event_schedule","event_schedule.event_id","=","E.id")
											->where(function($query) use($match_date){
												$query->where(['E.status'=>2]);
												$query->where('event_schedule.event_start_date_time','<=',$match_date);
												$query->where('event_schedule.event_end_date_time','>=',$match_date);
											})
											->orderBy("P.id","DESC")
											->get();

					
						
				}
				// return $data;	
			}

			if($music_interest) {
				foreach ($music_interest as $music) {
					$music[$music->name] = DB::table("music_interest as M")
											// ->select("P.name as interest_name","E.event_name","E.event_image_url","E.id","E.ticket_amount",DB::raw("TIME_FORMAT(E.event_time, '%h:%i %p') as event_time"))
											->join("event_music_interest_list as I","M.id","=","I.music_interest_id")
											->join("event_list as E","E.id","=","I.event_id")
											->where(["E.event_date" => $current_date])
											->where("M.id",$music->id)
											->join("event_schedule","event_schedule.event_id","=","E.id")
											->where(function($query) use($match_date){
												$query->where(['E.submit_by'=>2,'E.status'=>2]);
												$query->where('event_schedule.event_start_date_time','<',$match_date);
												$query->where('event_schedule.event_end_date_time','>=',$match_date);
											})
											->orderBy("M.id","DESC")
											->get();
					
				}
				// return $music;	
			}

			// For upcomming Event
			if($public_interest) {
				foreach ($public_interest as $public) {
					$upcoming[$public->name] = DB::table("public_interest as P")
											->select("P.name as interest_name","E.event_name","event_video_url","E.event_image_url","event_video_url2","E.event_image_url2","main","E.event_media_type","event_schedule.id","E.ticket_amount",DB::raw("TIME_FORMAT(event_schedule.event_time, '%h:%i %p') as event_time"))
											->join("event_public_interest_list as I","P.id","=","I.public_interest_id")
											->join("event_list as E","E.id","=","I.event_id")
											->where("P.id",$public->id)
											->join("event_schedule","event_schedule.event_id","=","E.id")
											->where(function($query) use($match_date,$match_date_range){
												$query->where(['E.submit_by'=>2]);
												$query->where('event_schedule.event_start_date_time','>',$match_date);
												$query->whereBetween('event_schedule.event_start_date_time',[$match_date,$match_date_range]);
											})
											// ->where("E.status", 2)
											->orderBy('event_schedule.event_start_date_time','ASC')
											->get();
					
				}
				 // return $upcoming;	

			}

			

			if($public_interest) {
				foreach ($public_interest as $public) {
					$favourites= FavouriteEvent::where(['user_id'=>$user->id, 'is_favorite' => 1])->select('sub_event_id')
							 ->distinct('sub_event_id')
							 ->pluck('sub_event_id');	
					$favorite[$public->name] = DB::table("public_interest as P")
											->select("P.name as interest_name","E.event_name","event_video_url","E.event_media_type","event_video_url2","E.event_image_url2","main","E.event_image_url","event_schedule.id","E.ticket_amount",DB::raw("TIME_FORMAT(event_schedule.event_time, '%h:%i %p') as event_time"))
											->join("event_public_interest_list as I","P.id","=","I.public_interest_id")
											->join("event_list as E","E.id","=","I.event_id")
											// ->where("E.event_date",'>=', $current_date)
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

			if($music_interest) {
				foreach ($music_interest as $music) {
					$music[$music->name] = DB::table("music_interest as M")
											// ->select("P.name as interest_name","E.event_name","E.event_image_url","E.id","E.ticket_amount",DB::raw("TIME_FORMAT(E.event_time, '%h:%i %p') as event_time"))
											->join("event_music_interest_list as I","M.id","=","I.music_interest_id")
											->join("event_list as E","E.id","=","I.event_id")
											->where(["E.event_date" => $current_date])
											->where("M.id",$music->id)
											->join("event_schedule","event_schedule.event_id","=","E.id")
											->where(function($query) use($match_date){
												$query->where(['E.submit_by'=>2,'E.status'=>2]);
												$query->where('event_schedule.event_start_date_time','<',$match_date);
												$query->where('event_schedule.event_end_date_time','>=',$match_date);
												})
											->orderBy("M.id","DESC")
											->get();
					
				}
				// return $music;	
			}

			$event_interest = DB::table('event_public_interest_list')->pluck('event_id');
			$other_category = DB::table("event_list as E")
							->select("E.event_name","event_video_url","E.event_image_url","event_video_url2","E.event_image_url2","main","E.event_media_type","event_schedule.id","E.ticket_amount",DB::raw("TIME_FORMAT(event_schedule.event_time, '%h:%i %p') as event_time"))
							->whereNotIn("E.id",$event_interest)
							->join("event_schedule","event_schedule.event_id","=","E.id")
							->where(function($query) use($match_date){
								$query->where(['E.status'=>2]);
								$query->where('event_schedule.event_start_date_time','<',$match_date);
								$query->where('event_schedule.event_end_date_time','>=',$match_date);
							})
							->orderBy("event_schedule.event_end_date_time","DESC")
							->get();

			$upcoming_category = DB::table("event_list as E")
							->select("E.event_name","event_video_url","E.event_image_url","event_video_url2","E.event_image_url2","main","E.event_media_type","event_schedule.id","E.ticket_amount","event_schedule.event_start_date_time",DB::raw("TIME_FORMAT(event_schedule.event_time, '%h:%i %p') as event_time"))
							->whereNotIn("E.id",$event_interest)
							->join("event_schedule","event_schedule.event_id","=","E.id")
							->where(function($query) use($match_date,$match_date_range){
												$query->where(['E.submit_by'=>2]);
												$query->where('event_schedule.event_start_date_time','>',$match_date);
												$query->whereBetween('event_schedule.event_start_date_time',[$match_date,$match_date_range]);
											})
							->orderBy("event_schedule.event_end_date_time","DESC")
							->get();
		// return $favorite;

			return view('website.events',["data" => $data, "upcoming" => $upcoming, "favorite" => $favorite,"now_category" => $other_category,"upcoming_category" => $upcoming_category ]);
			
		} else {
			if($public_interest) {
			foreach ($public_interest as $public) {
				$data[$public->name] = DB::table("public_interest as P")
											->select("P.name as interest_name","E.event_name","event_video_url","E.event_media_type","event_video_url2","E.event_image_url2","main","E.event_image_url","event_schedule.id","E.ticket_amount",DB::raw("TIME_FORMAT(event_schedule.event_time, '%h:%i %p') as event_time"))
											->join("event_public_interest_list as I","P.id","=","I.public_interest_id")
											->join("event_list as E","E.id","=","I.event_id")
											// ->where(["E.event_date" => $current_date])
											->where("P.id",$public->id)
											->join("event_schedule","event_schedule.event_id","=","E.id")
											->where(function($query) use($match_date){
												$query->where(['E.status'=>2]);
												$query->where('event_schedule.event_start_date_time','<',$match_date);
												$query->where('event_schedule.event_end_date_time','>=',$match_date);
											})
											->orderBy("P.id","DESC")
											->get();
				
			}	
		// return $data;
		}

		// For upcomming Event
		if($public_interest) {
			foreach ($public_interest as $public) {
				$upcoming[$public->name] = DB::table("public_interest as P")
											->select("P.name as interest_name","E.event_name","E.event_media_type","event_video_url","event_video_url2","E.event_image_url2","main","E.event_image_url","event_schedule.id","E.ticket_amount",DB::raw("TIME_FORMAT(E.event_time, '%h:%i %p') as event_time"))
											->join("event_public_interest_list as I","P.id","=","I.public_interest_id")
											->join("event_list as E","E.id","=","I.event_id")
											->where("P.id",$public->id)
											->join("event_schedule","event_schedule.event_id","=","E.id")
											->where(function($query) use($match_date,$match_date_range){
												$query->where(['E.submit_by'=>2]);
												$query->where('event_schedule.event_start_date_time','>',$match_date);
												$query->whereBetween('event_schedule.event_start_date_time',[$match_date,$match_date_range]);
											})
											// ->where("E.status", 2)
											->orderBy('event_schedule.event_start_date_time','ASC')
											->get();
					
				}
			// return $upcoming;
		}

		
		
		$event_interest = DB::table('event_public_interest_list')->pluck('event_id');
		$other_category = DB::table("event_list as E")
						->select("E.event_name","event_video_url","E.event_image_url","event_video_url2","E.event_image_url2","main","E.event_media_type","event_schedule.id","E.ticket_amount",DB::raw("TIME_FORMAT(event_schedule.event_time, '%h:%i %p') as event_time"))
						->whereNotIn("E.id",$event_interest)
						->join("event_schedule","event_schedule.event_id","=","E.id")
						->where(function($query) use($match_date){
							$query->where(['E.status'=>2]);
							$query->where('event_schedule.event_start_date_time','<',$match_date);
							$query->where('event_schedule.event_end_date_time','>=',$match_date);
						})
						->orderBy("event_schedule.event_end_date_time","DESC")
						->get();

		$upcoming_category = DB::table("event_list as E")
						->select("E.event_name","event_video_url","E.event_image_url","event_video_url2","E.event_image_url2","main","E.event_media_type","event_schedule.id","E.ticket_amount","event_schedule.event_start_date_time",DB::raw("TIME_FORMAT(event_schedule.event_time, '%h:%i %p') as event_time"))
						->whereNotIn("E.id",$event_interest)
						->join("event_schedule","event_schedule.event_id","=","E.id")
						->where(function($query) use($match_date,$match_date_range){
											$query->where(['E.submit_by'=>2]);
											$query->where('event_schedule.event_start_date_time','>',$match_date);
											$query->whereBetween('event_schedule.event_start_date_time',[$match_date,$match_date_range]);
										})
						->orderBy("event_schedule.event_end_date_time","DESC")
						->get();

		return view('website.events',["data" => $data, "upcoming" => $upcoming,"now_category" => $other_category,"upcoming_category" => $upcoming_category]);
		}
	}


	Public function currentEvent(Request $request,$date = null) {
		$user = auth()->guard('web')->user();
		
		if($user) {
			$id=$request->input('user_id');
			if(empty($id)){
				// $user_id = $user->id;
				if($user->blockStatus == 1){
					Auth::logout();
			        $request->session()->flush();
			        $request->session()->regenerate();

			        Session::flash('danger', 'You are blocked by admin.');
            		return redirect(url('website/login'))->withInput();

				}
			}
		}

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
		
		// $id = $user->id;
		// $UserPublicInterest = UserPublicInterest::where('user_id',$user->id)->get();
		// $favorite_event_id = FavouriteEvent::where(['user_id'=> $user->id,'is_favorite' => 1])->pluck('event_id')->toarray();
		if($public_interest) {
			foreach ($public_interest as $public) {
				$data[$public->name] = DB::table("public_interest as P")
										->select("P.name as interest_name","E.event_name","event_video_url","E.event_image_url","event_video_url2","E.event_image_url2","main","E.event_media_type","event_schedule.id","E.ticket_amount",DB::raw("TIME_FORMAT(event_schedule.event_time, '%h:%i %p') as event_time"))
										->join("event_public_interest_list as I","P.id","=","I.public_interest_id")
										->join("event_list as E","E.id","=","I.event_id")
										// ->where(["E.event_date" => $current_date])
										->where("P.id",$public->id)
										->join("event_schedule","event_schedule.event_id","=","E.id")
										->where(function($query) use($match_date){
											$query->where(['E.status'=>2]);
											$query->where('event_schedule.event_start_date_time','<=',$match_date);
											$query->where('event_schedule.event_end_date_time','>=',$match_date);
										})
										->orderBy("P.id","DESC")
										->get();		
						
				
				// return $data;	
			}
			$event_interest = DB::table('event_public_interest_list')->pluck('event_id');
			$other_category = DB::table("event_list as E")
							->select("E.event_name","event_video_url","E.event_image_url","event_video_url2","E.event_image_url2","main","E.event_media_type","event_schedule.id","E.ticket_amount",DB::raw("TIME_FORMAT(event_schedule.event_time, '%h:%i %p') as event_time"))
							->whereNotIn("E.id",$event_interest)
							->join("event_schedule","event_schedule.event_id","=","E.id")
							->where(function($query) use($match_date){
								$query->where(['E.status'=>2]);
								$query->where('event_schedule.event_start_date_time','<',$match_date);
								$query->where('event_schedule.event_end_date_time','>=',$match_date);
							})
							->orderBy("event_schedule.event_end_date_time","DESC")
							->get();
			return view('website.current_events',["data" => $data,"now_category" => $other_category]);
		}
	}


	public function upcomingEvents(Request $request,$date = null) {
		$user = auth()->guard('web')->user();
		
		if($user) {
			$id=$request->input('user_id');
			if(empty($id)){
				// $user_id = $user->id;
				if($user->blockStatus == 1){
					Auth::logout();
			        $request->session()->flush();
			        $request->session()->regenerate();

			        Session::flash('danger', 'You are blocked by admin.');
            		return redirect(url('website/login'))->withInput();

				}
			}
		}

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

		if($public_interest) {
			foreach ($public_interest as $public) {
				$upcoming[$public->name] = DB::table("public_interest as P")
										->select("P.name as interest_name","E.event_name","event_video_url","E.event_image_url","event_video_url2","E.event_image_url2","main","E.event_media_type","event_schedule.id","E.ticket_amount",DB::raw("TIME_FORMAT(event_schedule.event_time, '%h:%i %p') as event_time"))
										->join("event_public_interest_list as I","P.id","=","I.public_interest_id")
										->join("event_list as E","E.id","=","I.event_id")
										->where("P.id",$public->id)
										->join("event_schedule","event_schedule.event_id","=","E.id")
										->where(function($query) use($match_date,$match_date_range){
											$query->where(['E.submit_by'=>2]);
											$query->where('event_schedule.event_start_date_time','>',$match_date);
											$query->whereBetween('event_schedule.event_start_date_time',[$match_date,$match_date_range]);
										})
										// ->where("E.status", 2)
										->orderBy('event_schedule.event_start_date_time','ASC')
										->get();
				}
				 // return $upcoming;	
			}
		$event_interest = DB::table('event_public_interest_list')->pluck('event_id');
		$upcoming_category = DB::table("event_list as E")
						->select("E.event_name","event_video_url","E.event_image_url","event_video_url2","E.event_image_url2","main","E.event_media_type","event_schedule.id","E.ticket_amount","event_schedule.event_start_date_time",DB::raw("TIME_FORMAT(event_schedule.event_time, '%h:%i %p') as event_time"))
						->whereNotIn("E.id",$event_interest)
						->join("event_schedule","event_schedule.event_id","=","E.id")
						->where(function($query) use($match_date,$match_date_range){
											$query->where(['E.submit_by'=>2]);
											$query->where('event_schedule.event_start_date_time','>',$match_date);
											$query->whereBetween('event_schedule.event_start_date_time',[$match_date,$match_date_range]);
										})
						->orderBy("event_schedule.event_end_date_time","DESC")
						->get();
		return view('website.upcoming_events',["upcoming" => $upcoming,"upcoming_category" => $upcoming_category]);
	}

	public function favoriteEvents(Request $request,$date = null) {
		$user = auth()->guard('web')->user();
		
		if($user) {
			$id=$request->input('user_id');
			if(empty($id)){
				// $user_id = $user->id;
				if($user->blockStatus == 1){
					Auth::logout();
			        $request->session()->flush();
			        $request->session()->regenerate();

			        Session::flash('danger', 'You are blocked by admin.');
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

		if($public_interest) {
			foreach ($public_interest as $public) {
				$favourites= FavouriteEvent::where(['user_id'=>$user->id, 'is_favorite' => 1])->select('sub_event_id')
						 ->distinct('sub_event_id')
						 ->pluck('sub_event_id');	
				$favorite[$public->name] = DB::table("public_interest as P")
										->select("P.name as interest_name","E.event_name","event_video_url","E.event_media_type","event_video_url2","E.event_image_url2","main","E.event_image_url","event_schedule.id","E.ticket_amount",DB::raw("TIME_FORMAT(event_schedule.event_time, '%h:%i %p') as event_time"))
										->join("event_public_interest_list as I","P.id","=","I.public_interest_id")
										->join("event_list as E","E.id","=","I.event_id")
										// ->where("E.event_date",'>=', $current_date)
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
		return view('website.favourite_events',["favorite" => $favorite]);
	}

	public function pastEvent(Request $request,$date = null) {
		$user = auth()->guard('web')->user();
		
		if($user) {
			$id=$request->input('user_id');
			if(empty($id)){
				// $user_id = $user->id;
				if($user->blockStatus == 1){
					Auth::logout();
			        $request->session()->flush();
			        $request->session()->regenerate();

			        Session::flash('danger', 'You are blocked by admin.');
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
		// $user = auth()->guard('web')->user();
		
		if($users) {
			$id=$request->input('user_id');
			if(empty($id)){
				// $user_id = $users->id;
				if($users->blockStatus == 1){
					Auth::logout();
			        $request->session()->flush();
			        $request->session()->regenerate();

			        Session::flash('danger', 'You are blocked by admin.');
            		return redirect(url('website/login'))->withInput();

				}
			}
		}
		if($users) {
			$id=$request->input('user_id');
			if(empty($id)){
				$user_id = $users->id;
				if($users->blockStatus == 1){
					Auth::logout();
			        $request->session()->flush();
			        $request->session()->regenerate();

			        Session::flash('danger', 'You are blocked by admin.');
            		return redirect(url('website/login'))->withInput();

				}
			}
		}
		// return $request->event_id;
		$get_event_id=DB::table('event_schedule')->where(['id'=>$request->event_id])->first();
		// if(!empty($get_event_id)){
		// 	$check_event_id=DB::table('event_list')->where(['id'=>$get_event_id->event_id,'status'=>2])->first();
		// 	if(empty($check_event_id)){
		// 	  $this->responseWithError('This event has been blocked/deleted by admin');
		// 	}
		// }else{
		// 	$this->responseWithError('This event has been blocked/deleted by admin');
		// }
		$eachEvent= EventList::
			leftJoin('event_schedule', 'event_schedule.event_id', '=', 'event_list.id')
			->where(['event_schedule.id' =>$request->event_id])->first();
		$carbon = Carbon::parse($eachEvent->event_time);
		$eachEvent->event_time = $carbon->format('g:i A');
		$total_posts =  DB::table('post_list')->where(['event_id'=>$eachEvent->event_id,'status'=>1])->count();
		// $event_data = $eachEvent;
		// $event_data->post_list_count = $eachEvent->postList()->count();
		// $event_data->post_list = $eachEvent->postList()->take(10)->get();
		
		
        
       		
		
		
		// $eachEvent->favourite_status=$this->favourite_status($id,$eachEvent->id);
		// $eachEvent->ticket_available_count=$this->get_tickets($eachEvent->event_id,'ticket_count');
		// $eachEvent->ticket_amount=$this->get_tickets($eachEvent->event_id,'price');
	 	// $event_views=EventView::where(['sub_event_id'=>$request->input('event_id')])->count();
		// $eachEvent->event_views=$this->event_views($id,$request->input('event_id'));
		// if(!empty($event_data)){
		// 	$this->responseOk('Each Event List',$event_data);
		// }else{
		// 	$this->responseWithError('Event list not found');
		// }
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
		$FavouriteEvent = FavouriteEvent::where(['user_id'=> $id,'event_id'=>$event_id])->first();
		return view('website.website-view_2',compact('eachEvent','total_posts','FavouriteEvent','public_interest_id','music_interest_id'));
	}

	public function createMotive(Request $request) {
		if($request->isMethod('get')) {
			$user = auth()->guard('web')->user();
			if($user) {
			// $user = auth()->guard('web')->user();
	
				$id=$request->input('user_id');
				if(empty($id)){
					// $user_id = $users->id;
					if($user->blockStatus == 1){
						Auth::logout();
				        $request->session()->flush();
				        $request->session()->regenerate();

				        Session::flash('danger', 'You are blocked by admin.');
	            		return redirect(url('website/login'))->withInput();

				}
					
				}
				$user_id = $user->id; 
				$music_interest = MusicInterest::all();
		        $public_interest = PublicInterest::all();
		       
		        $sender_id = Friend::where(['sender_id'=> $user_id,'request_status'=>1])->pluck('receiver_id')->toArray();
		        $receiver_id = Friend::where(['receiver_id'=> $user_id,'request_status'=>1])->pluck('sender_id')->toArray();
	            // $chatss = $chat->distinct("users")->get();
		        $friends = array_merge($sender_id,$receiver_id);
		        // print_r($friends); die;

		        $users = User::whereIn('id',$friends)->distinct('id')->select('id','name','image_url')->get();
				return view('website.create-motiv',compact('music_interest','public_interest','users'));			
			} else {
				return redirect(url('website/login'));
			}
		}
		if($request->isMethod('post')) {
			$user = auth()->guard('web')->user();
			$id = $user->id; 
			// return $request->input('event_media_type');
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
                // 'event_name' => 'required|regex:/^[\pL\s\-]+$/u|max:50',
           		'event_name' => 'required|max:50',
                'location'  => 'required',
                'start_date'  => 'required',
                'end_date'  => 'required',
                'repeat_interval'  => 'required',
                'website_url'  => 'nullable|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
                'phone_number' => 'nullable|regex:/^[+0-9]+$/|max:19|min:10',
                'descriptions' => 'required|max:500',
                // 'descriptions' => 'required',
            ],$errors);
		}
		// return $request->all();
		// return $request->input('main');
		// return $request->file('event_image_url2');

		// if($request->input('start_date') > $request->input('end_date')) {
		// 		Session::flash('danger', 'End date and time may not grater than start date.'); 
		// 		return back()->withInput();
		// }

		// if($request->input('start_date') > ) {
		// 		Session::flash('danger', 'End date and time is may grater than start date.'); 
		// 		return back()->withInput();
		// }

		// if(!empty($request->file('event_video_url')) || !empty($request->file('event_image_url'))) {

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
			

				// $request->file('event_image_url');
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
				$insert_array['age_restrictions'] = !empty($request->input('age_restrictions')) ? $request->input('age_restrictions') : '0';
				$insert_array['id_Required'] = !empty($request->input('id_Required')) ? $request->input('id_Required') : '';
				$insert_array['url'] = !empty($request->input('url')) ? $request->input('url') : '';
				$insert_array['music_int_id']=$request->input('music_interest');
				$insert_array['public_int_id']=$request->input('public_interest');
				$insert_array['post_type'] =2;
			
			
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

				$guests = DB::table('invitations')->insert(['event_id'=>$insert_id,'sender_id'=>0,'receiver_id'=>$id,'request_status'=>1,'created_at'=>date("Y-m-d h:i:s"),'timestamp'=>round(microtime(true) * 1000)]);
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
					if(!empty($music_interest) > 0){
						foreach($music_interest as $eachPublic){
							$check = MusicInterest::find($eachPublic);
							if(!empty($check)){
								EventMusicInterestList::insert(['event_id'=>$insert_id,'music_interest_id'=>$eachPublic, 'updated_at'=>Date('Y-m-d H:i:s'),'created_at' => Date('Y-m-d H:i:s')]);
							}
						}
					}
				// }

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
				// return $friend_ids = explode(',',$request->input('users'));
				if(!empty($userss)){      
					foreach($userss as $friend_id ){
						// $check_friend_id=DB::table('users')->where(['id'=>$friend_id])->first();
						// if(empty($check_friend_id)){
						// 	$this->responseWithError('You have entered wrong friend id');
						// }				
						$get_friend_name=DB::table('users')->where(['id'=>$friend_id])->first();
	             		$get_friend = DB::table('invitations')->insert(['sender_id'=>$id,'receiver_id'=>$friend_id,'event_id'=>$insert_id,'sub_event_id'=>$get_lat_id->id,'created_at'=>date("Y-m-d h:i:s"),'timestamp'=>round(microtime(true) * 1000)]);
						#save invitation notification
						DB::table('notification_list')->insert(['user_id'=>$friend_id,'other_user_id'=>$id,'message'=>'you have received event invitation','notification_type'=>1,'timestamp'=>round(microtime(true) * 1000),'created_at' =>Date('Y-m-d H:i:s'),'updated_at' =>Date('Y-m-d H:i:s')]);
						#***************************
						$notify_count=$this->notification_count($friend_id);
						if($get_friend_name->device_type == 'I' && $get_friend_name->notification_status == 1){
								$message = array('sound' =>1,'message'=>'you have received event invitation',
								'notifykey'=>'invitation','data'=>'Mo-Tiv','title'=>'Mo-Tiv',
								'event_date'=>$get_event->event_date,'event_time'=>$get_event->event_time,'notify_count'=>$notify_count);
								$device_token=$get_friend_name->device_token;
								$dd=$this->send_iphone_notification2($device_token,'you have received event invitation','invitation',$message);
						}   
						if($get_friend_name->device_type == 'A' && $get_friend_name->notification_status == 1){
							$message = array('sound' =>1,'message'=>'you have received event invitation',
							'notifykey'=>'invitation','data'=>'hello','title'=>'Mo-Tiv',
							'event_date'=>$get_event->event_date,'event_time'=>$get_event->event_time,'notify_count'=>$notify_count);
							$device_token=$get_friend_name->device_token;
							$this->send_android_notification($device_token,'you have received event invitation','invitation',$message);
						}  
					}   
				} 
			
			Session::flash('message', 'Event created successfully.'); 
			return redirect('website/events')->withInput();
		
	}

	public function filter(Request $request) {
		$user = auth()->guard('web')->user();
		
		if($user) {
			$id=$request->input('user_id');
			if(empty($id)){
				// $user_id = $users->id;
				if($user->blockStatus == 1){
					Auth::logout();
			        $request->session()->flush();
			        $request->session()->regenerate();

			        Session::flash('danger', 'You are blocked by admin.');
            		return redirect(url('website/login'))->withInput();

				}
			}
		}
		$public_interest = PublicInterest::all();
		$music_interest = MusicInterest::all();
		return view('website.filter',compact('public_interest','music_interest'));
	}


	public function notification() {
		return view('website.notification');
	}

	public function websiteView(Request $request) {
		$users = auth()->guard('web')->user();
		if($users) {
			$id=$request->input('user_id');
			if(empty($id)){
				// $user_id = $users->id;
				if($users->blockStatus == 1){
					Auth::logout();
			        $request->session()->flush();
			        $request->session()->regenerate();

			        Session::flash('danger', 'You are blocked by admin.');
            		return redirect(url('website/login'))->withInput();

				}
			}
		}
		// return $request->event_id;
		$get_event_id=DB::table('event_schedule')->where(['id'=>$request->event_id])->first();
		// if(!empty($get_event_id)){
		// 	$check_event_id=DB::table('event_list')->where(['id'=>$get_event_id->event_id,'status'=>2])->first();
		// 	if(empty($check_event_id)){
		// 	  $this->responseWithError('This event has been blocked/deleted by admin');
		// 	}
		// }else{
		// 	$this->responseWithError('This event has been blocked/deleted by admin');
		// }
		$eachEvent= EventList::
			leftJoin('event_schedule', 'event_schedule.event_id', '=', 'event_list.id')
			->where(['event_schedule.id' =>$request->event_id])->first();
		
		$carbon = Carbon::parse($eachEvent->event_time);
		$eachEvent->event_time = $carbon->format('g:i A');
		
		$total_posts =  DB::table('post_list')->where(['event_id'=>$eachEvent->event_id,'status'=>1])->count();
		// $event_data = $eachEvent;
		// $event_data->post_list_count = $eachEvent->postList()->count();
		// $event_data->post_list = $eachEvent->postList()->take(10)->get();
		
		
        
       		
		
		
		// $eachEvent->favourite_status=$this->favourite_status($id,$eachEvent->id);
		// $eachEvent->ticket_available_count=$this->get_tickets($eachEvent->event_id,'ticket_count');
		// $eachEvent->ticket_amount=$this->get_tickets($eachEvent->event_id,'price');
	 	// $event_views=EventView::where(['sub_event_id'=>$request->input('event_id')])->count();
		// $eachEvent->event_views=$this->event_views($id,$request->input('event_id'));
		// if(!empty($event_data)){
		// 	$this->responseOk('Each Event List',$event_data);
		// }else{
		// 	$this->responseWithError('Event list not found');
		// }
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
		// print_r($FavouriteEvent);die;
		return view('website.website-view',compact('eachEvent','total_posts','FavouriteEvent','public_interest_id','music_interest_id'));
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

	public function bookNow() {
		return view('website.book-now');
	}

	public function buyNow() {
		return view('website.buy-now');
	}

	public function changePassword() {
		return view('website.change-password');
	}


	public function likePost(Request $request) {
		$user = auth()->guard('web')->user(); 
		$post_id = $request->post_id;
		$likes = Like::where(['user_id'=> $user->id,'post_id'=>$post_id])->first();
	 	// print_r($likes);die;
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
			// $fav_event->timestamp = round(microtime(true) * 1000);
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
			// $user = auth()->guard('web')->user();
		
			if($user) {
				$id=$request->input('user_id');
				if(empty($id)){
					// $user_id = $users->id;
					if($user->blockStatus == 1){
						Auth::logout();
				        $request->session()->flush();
				        $request->session()->regenerate();

				        Session::flash('danger', 'You are blocked by admin.');
	            		return redirect(url('website/login'))->withInput();

					}
				}
			}
			$post = DB::table('post_list')->where('id',$request->post_id)->first();
			$comments = DB::table('comments')->where('post_id',$post->id)->get();
			foreach ($comments as $comment) {
				$comment->users = User::where('id',$comment->user_id)->select('id','name','image_url')->first();
				
			}
			// return $comments;
			// print_r($post->comments);die;
			$likes = DB::table('likes')->where(['post_id'=>$post->id,'like_status'=>1])->count();
			$like = DB::table('likes')->where(['post_id'=>$post->id,'user_id'=>$user->id])->first();
			$comment = DB::table('comments')->where('post_id',$post->id)->count();
			// $comment_status = DB::table('comments')->where(['post_id'=>$post->id,'user_id'=>$user->id])->first();
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
           	// return "1";
           	$comment = new CommentList();
            $comment->user_id = $user->id;
            $comment->post_id = $request->post_id;
            $comment->comment = $request->comment;
            $comment->timestamp = round(microtime(true) * 1000);
		    if($comment->save()) {
		    	$get_post=DB::table('post_list')->where(['id'=>$request->post_id])->first();
				$get_event=DB::table('event_schedule')->where(['event_id'=>$get_post->event_id])->first();		
				$get_friend_name=DB::table('users')->where(['id'=>$get_post->user_id])->first();
				// print_r($get_friend_name); die;
				if($get_post->user_id !=$id && !empty($get_friend_name)){
					$get_reciever_name=DB::table('users')->where(['id'=>$id])->first();	
					/*save notification*/			
				    DB::table('notification_list')->insert(['user_id'=>$get_post->user_id,'other_user_id'=>$id,'message'=>$get_reciever_name->name.' '.'Commented on your post','notification_type'=>2,'timestamp'=>round(microtime(true) * 1000),'created_at' =>Date('Y-m-d H:i:s'),'updated_at' =>Date('Y-m-d H:i:s')]);		
					/************/
					$notify_count=$this->notification_count($get_post->user_id);
					
					if($get_friend_name->device_type == 'I' && $get_friend_name->notification_status == 1){
							$message = array('sound' =>1,'message'=>$get_reciever_name->name.' '.'Commented on your post',
							'notifykey'=>'comment','data'=>'Mo-Tiv','title'=>$get_reciever_name->name,'post_id'=>$get_post->id,
							'event_date'=>$get_event->event_date,'event_time'=>$get_event->event_time,'notify_count'=>$notify_count);
							$device_token=$get_friend_name->device_token;
							$dd=$this->send_iphone_notification($device_token,$get_reciever_name->name.' '.'Commented on your post','comment',$message);
					}   
					if($get_friend_name->device_type == 'A' && $get_friend_name->notification_status == 1){
						$message = array('sound' =>1,'message'=>$get_reciever_name->name.' '.'Commented on your post',
						'notifykey'=>'comment','data'=>'hello','title'=>$get_reciever_name->name,'post_id'=>$get_post->id,
						'event_date'=>$get_event->event_date,'event_time'=>$get_event->event_time,'notify_count'=>$notify_count);
						$device_token=$get_friend_name->device_token;
						$this->send_android_notification($device_token,$get_reciever_name->name.' '.'Commented on your post','comment',$message);
					}
					
				}			
				// $this->responseOk('Comment added successfully','');	
		    	Session::flash('danger', 'Comment added successfully.'); 
				return back()->withInput();
			}

		}

		
	}


	// public function addComment() {
	// 	return "1";
	// }

	public function settings(Request $request) {
		$user = auth()->guard('web')->user();

		if($user) {
			$id=$request->input('user_id');
			if(empty($id)){
				// $user_id = $users->id;
				if($user->blockStatus == 1){
					Auth::logout();
			        $request->session()->flush();
			        $request->session()->regenerate();

			        Session::flash('danger', 'You are blocked by admin.');
            		return redirect(url('website/login'))->withInput();

				}
			}
		}
		return view('website.settings',compact('user'));
	}


	public function ticketView(Request $request) {
		$user = auth()->guard()->user();
		$id=$user->id;

		
		if($user) {
			$id=$request->input('user_id');
			if(empty($id)){
				// $user_id = $users->id;
				if($user->blockStatus == 1){
					Auth::logout();
			        $request->session()->flush();
			        $request->session()->regenerate();

			        Session::flash('danger', 'You are blocked by admin.');
            		return redirect(url('website/login'))->withInput();

				}
			}
		}
		$event_id = $request->event_id;
		
        // $bought_tickets=  DB::table('bought_tickets')
        // 		->where(['user_id'=>$id])
        // 		->distinct('sub_event_id')
        // 		->pluck('sub_event_id');
    			
		$get_events= EventList::
				leftJoin('event_schedule', 'event_schedule.event_id', '=', 'event_list.id')
				->where(['status' =>2])
				->where('event_schedule.id',$event_id)
				->orderBy('event_schedule.event_start_date_time','ASC')
			    ->first(); 
		// print_r($get_events); die;
		if(!empty($get_events)) {	 	
				$get_events->total_tickets = DB::table('bought_tickets')
				        	->where(['event_id'=>$get_events->event_id,'sub_event_id'=>$event_id,'user_id'=>$user->id])
				        	->count();
		}	

		$tickets = DB::table('tickets')
		        	->where(['event_id'=>$get_events->event_id])
		        	->get();

		       	foreach ($tickets as $ticket) {
		       		if(!empty($ticket)) {
		       			$ticket->ticket_quantity = DB::table('bought_tickets')
				        	->where(['ticket_id'=>$ticket->id,'user_id'=>$id])
				        	->count();
		       		}
		       	}    
		// return $tickets;
		return view('website.ticket-view',compact('tickets','get_events'));
	}

	public function tickets(Request $request) {
		$user = auth()->guard()->user();
		if($user) {

			$id=$request->input('user_id');
			if(empty($id)){
				// $user_id = $users->id;
				if($user->blockStatus == 1){
					Auth::logout();
			        $request->session()->flush();
			        $request->session()->regenerate();

			        Session::flash('danger', 'You are blocked by admin.');
            		return redirect(url('website/login'))->withInput();

				}
			}

			$id=$user->id;
			
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
					        	->where(['bought_tickets.sub_event_id'=>$get_event->id,
					        			'bought_tickets.user_id'=>$id
					        			])
					        	->sum('quantity');
			         
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
			$id=$request->input('user_id');
			if(empty($id)){
				// $user_id = $users->id;
				if($user->blockStatus == 1){
					Auth::logout();
			        $request->session()->flush();
			        $request->session()->regenerate();

			        Session::flash('danger', 'You are blocked by admin.');
            		return redirect(url('website/login'))->withInput();

				}
			}
		}
		$posts = DB::table('post_list')->where(['event_id'=>$event_id,'status'=>1])->orderBy('id','DESC')->get();
		foreach ($posts as $post) {
			$post->comments = DB::table('comments')->where('post_id',$post->id)->count();
			$post->likes = DB::table('likes')->where('post_id',$post->id)->count();
			$post->like_status = Like::where(['user_id'=> $user->id,'post_id'=>$post->id])->first();
		}
		// return $posts;
		return view('website.posts',compact('posts','event_id'));
	}

	public function addPost(Request $request) {
		if($request->isMethod('get')) {
			$user = auth()->guard('web')->user();
			if($user) {
				$id=$request->input('user_id');
				if(empty($id)){
					// $user_id = $users->id;
					if($user->blockStatus == 1){
						Auth::logout();
				        $request->session()->flush();
				        $request->session()->regenerate();

				        Session::flash('danger', 'You are blocked by admin.');
	            		return redirect(url('website/login'))->withInput();

					}
				}
			}
			return view('website.add-post');
		}


		if($request->isMethod('post')) {
			// return $request->all();
			$user = auth()->guard('web')->user();
			$id = $user->id;
			$event_id = $request->event_id;
			$errors = [
                'upload_type.required'	=>  'Please select a file type.',
                'description.required'	=>  'Please enter description.',
                'description.max'	    =>  'Descriptions may not be greater than 500 characters.',
             ];     

           	$this->validate($request,[
                'upload_type' => 'required', 
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
				$id=$request->input('user_id');
				if(empty($id)){
					// $user_id = $users->id;
					if($user->blockStatus == 1){
						Auth::logout();
				        $request->session()->flush();
				        $request->session()->regenerate();

				        Session::flash('danger', 'You are blocked by admin.');
	            		return redirect(url('website/login'))->withInput();

					}
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
						 // ->whereRaw('UNIX_TIMESTAMP(event_schedule.event_start_date_time)','>=',$current_date)
						 ->first(); 
				$invitation->user_detail=DB::table('users')->where(['id'=>$invitation->sender_id])->select('name','email','image_url')->first(); 
			}
		// return $invitations;
        return view('website.invitation',compact('invitations'));
    }

    public function invitationView(Request $request) {
    	$user = auth()->guard('web')->user();
    	if($user) {
				$id=$request->input('user_id');
				if(empty($id)){
					// $user_id = $users->id;
					if($user->blockStatus == 1){
						Auth::logout();
				        $request->session()->flush();
				        $request->session()->regenerate();

				        Session::flash('danger', 'You are blocked by admin.');
	            		return redirect(url('website/login'))->withInput();

					}
				}
			}
		$id = $user->id;
		$invitation_id = $request->invitation_id;
		$current_date=date('Y-m-d H:i:s');
		$invitations = DB::table('invitations')
						->where(['request_status'=>2,'id' => $invitation_id])
						->where('sub_event_id','!=',0)
						->where('receiver_id',$id)->first();
						// dd($invitations);die;
		$event	= DB::table('event_list')
				 ->leftJoin('event_schedule', 'event_schedule.event_id', '=', 'event_list.id')
				 ->where('event_schedule.id',$invitations->sub_event_id)
				 // ->where('event_schedule.event_start_date_time','>=',$current_date)
				 ->first(); 
		// dd($event);die;
		// $invitation->user_detail=DB::table('users')->where(['id'=>$invitation->sender_id])->select('name','email','image_url')->first(); 

		// $event_music_interest_list=DB::table('event_music_interest_list')->where(['event_id'=>$event->event_id])->first();
		


		
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
		// $this->responseOk('Invitation accepted successfully','');

    }


    public function rejectInvitation(Request $request) {
    	$invitation_id = $request->invitation_id;
    	// $update_data =DB::table('invitations')->where('id',$invitation_id)->delete();
    	$check_id=DB::table('invitations')->where(['id'=>$invitation_id])->first();

    	$get_friend_name=DB::table('users')->where(['id'=>$check_id->sender_id])->first();
		$get_reciever_name=DB::table('users')->where(['id'=>$check_id->receiver_id])->first();
		$get_event=DB::table('event_schedule')->where(['id'=>$check_id->sub_event_id])->first();

		DB::table('notification_list')->insert(['user_id'=>$check_id->sender_id,'other_user_id'=>$check_id->receiver_id,'message'=>$get_reciever_name->name.' '.'rejected your event invitation','notification_type'=>1,'timestamp'=>round(microtime(true) * 1000),'created_at' =>Date('Y-m-d H:i:s'),'updated_at' =>Date('Y-m-d H:i:s')]);			
		$notify_count=$this->notification_count($check_id->sender_id);
			
		if($get_friend_name->device_type == 'I' && $get_friend_name->notification_status == 1){
				$message = array('sound' =>1,'message'=>$get_reciever_name->name.' '.'rejected your event invitation',
				'notifykey'=>'reject_invitation','data'=>'Mo-Tiv','title'=>$get_reciever_name->name,'event_id'=>$check_id->sub_event_id,
				'event_date'=>$get_event->event_date,'event_time'=>$get_event->event_time,'notify_count'=>$notify_count);
				$device_token=$get_friend_name->device_token;
				$dd=$this->send_iphone_notification2($device_token,$get_reciever_name->name.' '.'rejected your event invitation','reject_invitation',$message);
		}   
		if($get_friend_name->device_type == 'A' && $get_friend_name->notification_status == 1){
			$message = array('sound' =>1,'message'=>$get_reciever_name->name.' '.'rejected your event invitation',
			'notifykey'=>'reject_invitation','data'=>'hello','title'=>$get_reciever_name->name,'event_id'=>$check_id->sub_event_id,
			'event_date'=>$get_event->event_date,'event_time'=>$get_event->event_time,'notify_count'=>$notify_count);
			$device_token=$get_friend_name->device_token;
			$this->send_android_notification($device_token,$get_reciever_name->name.' '.'rejected your event invitation','reject_invitation',$message);
		}
		$check_id=DB::table('invitations')->where(['id'=>$invitation_id])->delete();
		Session::flash('message', 'Invitation declined successfully.'); 
		return redirect(url('website/invitation'))->withInput();	
		
		// $this->responseOk('Invitation declined successfully','');
		
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
}
