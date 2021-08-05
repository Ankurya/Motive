<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class FriendList extends Model
{
    protected $table = 'friends';
	
	/* public function friend(){
		return $this->belongsTo('App\Models\Friends');
	} */
	
	public function senderDetail(){
		 return $this->belongsTo('App\User','sender_id')->select(['id','name','image_url','email']);
	}
	
	
}
