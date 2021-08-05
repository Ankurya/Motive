<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class FavouriteEvent extends Model
{
    protected $table = 'favourite_events';
    protected $fillable = ['user_id', 'event_id', 'sub_event_id'];
    

}


?>