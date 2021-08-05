<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Like extends Model
{
    protected $table = 'likes';
	
	/* public function friend(){
		return $this->belongsTo('App\Models\Friends');
	} */
	
	public function userInfo(){
		 return $this->belongsTo('App\User','user_id')->select(['id','name','image_url','email']);
	}
	
	
}
