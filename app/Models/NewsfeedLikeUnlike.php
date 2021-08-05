<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class NewsfeedLikeUnlike extends Model
{
    protected $table = 'news_feeds_like_unlike';
	
	// public function eventMusicInterestList(){
		// return $this->hasMany('App\Models\EventMusicInterestList');
	// }
	
	// public function eventPublicInterestList(){
		// return $this->hasMany('App\Models\eventPublicInterestList');
	// }
	
	// public function postList(){
		// return $this->hasMany('App\Models\PostList','event_id')->where('status',1);
	// }
	
	public function userInfo(){
		 return $this->belongsTo('App\User','userId')->select(['id','username','image_url','email']);
	}
	
}
