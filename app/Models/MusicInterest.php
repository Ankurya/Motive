<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;

class MusicInterest extends Model
{
    protected $table = 'music_interest';
	protected $primaryKey = 'id';
	
	public function UserMusicInterest(){
		return $this->hasOne('App\Models\UserMusicInterest');
	}
}
