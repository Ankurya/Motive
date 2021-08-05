<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;

class NewsfeedComment extends Model
{
    protected $table = 'news_feeds_comments';
	// protected $primaryKey = 'id';
	
	public function likes() {
		
		return $this->hasMany('App\Models\NewsFeedCommentlike','newsFeedId');
	}
}
