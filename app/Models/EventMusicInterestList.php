<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventMusicInterestList extends Model
{
    protected $table = 'event_music_interest_list';
	
	public function eventList(){
		return $this->belongsTo('App\Models\EventList');
	}
	
	public function musicInterest(){
		return $this->belongsTo('App\Models\MusicInterest');
	}
	
	public function user(){
		 return $this->belongsTo('App\User');
	}
}
