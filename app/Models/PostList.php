<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class PostList extends Model
{
    protected $table = 'post_list';
	
	public function event(){
		return $this->belongsTo('App\Models\EventList');
	}
	
	public function user(){
		 return $this->belongsTo('App\User');
	}
	
	
}
