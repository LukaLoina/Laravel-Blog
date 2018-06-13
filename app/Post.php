<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /*
      Objekt Post predstavlja jedan red u tablici posts baze podataka.
      Laravel u ovaj objekt automatski dodaje varijable sa imenima stupaca tablice comments.
    */

    /* pomoću varijable $fillable definira se kojim varijablama se mogu pridružiti vrijdnosti kada pozivamo metodu create. */
    protected $fillable = [
        'title', 'content', 'user_id'
    ];

    public function user()
    {
        /* Pomoću metode belongsTo dohvaća se objekt User čiji Id je pohranjen u varijabli user_id. */
        return $this->belongsTo('App\User');
    }

    public function comments()
    {
        /* Pomoću metode hasMany dohvaćaju se svi objekti Comment koji imaju u sebi pohranjen post_id jednak Id-u ovog objekta. */
        return $this->hasMany('App\Comment');
    }

    public function likes()
    {
        /* Pomoću metode hasMany dohvaćaju se svi objekti Like koji imaju u sebi pohranjen post_id jednak Id-u ovog objekta. */
        return $this->hasMany('App\Like');
    }

    public function tags()
    {
        /* Pomoću metode belongsToMany dohvaćaju se svi objekti Tag za koje je u tablici baze podataka post_tag navedeno da pripadaju ovom Postu. U tablici post_tag su navedeni stupci post_id i tag_id koji bilježe koji tag je pridjeljen kojem postu. */
        return $this->belongsToMany('App\Tag');
    }
}
