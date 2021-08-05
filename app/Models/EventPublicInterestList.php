<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventPublicInterestList extends Model
{
    protected $table = 'event_public_interest_list';
	
	public function eventList(){
		return $this->belongsTo('App\Models\EventList');
	}
	
	public function publicInterest(){
		return $this->belongsTo('App\Models\PublicInterest');
	}
}
