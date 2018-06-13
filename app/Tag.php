<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
     /*
      Objekt Tag predstavlja jedan red u tablici tags baze podataka.
      Laravel u ovaj objekt automatski dodaje varijable sa imenima stupaca tablice tags.
     */
    protected $fillable = ['name']; /* pomoću varijable $fillable definira se kojim varijablama se mogu pridružiti vrijdnosti kada pozivamo metodu create. */

    public function posts()
    {
        /* Pomoću metode belongsToMany dohvaćaju se svi objekti Post za koje je u tablici baze podataka post_tag navedeno da pripadaju ovom Tagu. U tablici post_tag su navedeni stupci post_id i tag_id koji bilježe koji tag je pridjeljen kojem postu. */
        return $this->belongsToMany('App\Post');
    }
}
