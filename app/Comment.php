<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    //
    protected $fillable = ['user_id', 'post_id', 'comment_text'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
