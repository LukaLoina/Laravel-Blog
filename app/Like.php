<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    /*
      Objekt Like predstavlja jedan red u tablici likes baze podataka.
      Laravel u ovaj objekt automatski dodaje varijable sa imenima stupaca tablice likes.
    */
    protected $fillable = ['user_id', 'post_id']; /* pomoću varijable $fillable definira se kojim varijablama se mogu pridružiti vrijdnosti kada pozivamo metodu create. */
}
