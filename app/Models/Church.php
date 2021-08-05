<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Church extends Model
{
    protected $table = 'church_list';
	
	public function users(){
		 return $this->hasMany('App\User');
	}
}


?>
