<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsFeedTagUser extends Model
{

    protected $table = 'news_feeds_tag_users';

    public function user() {
        return $this->hasOne('App\User', 'id','tagUserId');
    }
}
