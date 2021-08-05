<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class UserCard extends Model
{
    protected $table = 'user_cards';
    protected $fillable = ['user_id', 'card_number', 'stripe_card_id'];
}


?>