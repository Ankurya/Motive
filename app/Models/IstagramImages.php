<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IstagramImages extends Model
{
    public function users(){
		 return $this->belongsTo('App\Users');
	}
}
