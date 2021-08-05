<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class EventList extends Model
{
    protected $table = 'event_list';
	
	public function eventMusicInterestList(){
		return $this->hasMany('App\Models\EventMusicInterestList');
	}
	
	public function eventPublicInterestList(){
		return $this->hasMany('App\Models\eventPublicInterestList');
	}
	
	public function postList(){
		return $this->hasMany('App\Models\PostList','event_id')->where('status',1);
	}
	
	public function user(){
		 return $this->belongsTo('App\User');
	}
	
}
