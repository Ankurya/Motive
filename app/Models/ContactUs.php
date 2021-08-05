<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    protected $table = 'contact_us';
	
	public function user(){
		 return $this->belongsTo('App\User');
	}
}
