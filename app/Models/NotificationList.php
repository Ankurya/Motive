<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationList extends Model
{
    protected $table = 'notification_list';
	
	public function user(){
		 return $this->belongsTo('App\User');
	}
}
