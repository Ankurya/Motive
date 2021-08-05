<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class EventView extends Model
{
    protected $table = 'event_views';
    protected $fillable = ['user_id', 'event_id', 'sub_event_id'];
    

}


?>