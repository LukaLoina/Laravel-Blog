<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    /*
      Objekt Comment predstavlja jedan red u tablici comments baze podataka.
      Laravel u ovaj objekt automatski dodaje varijable sa imenima stupaca tablice comments.
     */
    protected $fillable = ['user_id', 'post_id', 'comment_text']; /* pomoću varijable $fillable definira se kojim varijablama se mogu pridružiti vrijdnosti kada pozivamo metodu create. */

    public function user()
    {
        /* Pomoću metode belongsTo dohvaća se objekt User čiji Id je pohranjen u varijabli user_id. */
        return $this->belongsTo('App\User');
    }
}
