<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class NewsFeed extends Model
{
    protected $table = 'news_feeds';
	
	 // public function event(){
		 // return $this->belongsTo('App\Models\EventList');
	 // }
	
	 public function getCategory() {
		  return $this->hasMany('App\Models\NewsfeedCategory','newsFeedId');
		  
	 }

	 public function getComment() {
		  return $this->hasMany('App\Models\NewsfeedComment','newsFeedId');
		  
	 }
	 
	 public function getUserDetail() {
		 return $this->belongsTo('App\User','userId')->select('id','username','device_type','device_token','message_notification','post_like_notification','comment_notification','follow_notification','birthday_notification');
	 }

    public function user() {
        return $this->belongsTo('App\User','userId');
    }

	public function taggedUsers() {
	     return $this->hasMany('App\Models\NewsFeedTagUser', 'newsFeedId');
    }

    public function category() {
        return $this->hasOne('App\Models\NewsfeedCategory','newsFeedId');
    }

    public function medias() {
	     return $this->hasMany('App\Models\NewsFeedMedia', 'newsFeedId');
    }

    public function tags() {
	     return $this->belongsToMany('App\User', 'news_feeds_tag_users', 'newsFeedId', 'tagUserId');
    }

    public function cats() {
	     return $this->belongsToMany('App\Models\NewsfeedCategory', 'news_feeds_categories', 'newsFeedId', 'categoryID');
    }
	
}
