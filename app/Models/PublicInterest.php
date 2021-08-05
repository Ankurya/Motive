<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;

class PublicInterest extends Model
{
    protected $table = 'public_interest';
	protected $primaryKey = 'id';
	
	public function UserPublicInterest(){
		return $this->belongTo('App\Models\UserPublicInterest');
	}
	
	// public function UserPublicInterest(){
		// return $this->belongTo('App\Models\UserPublicInterest');
	// }
}
