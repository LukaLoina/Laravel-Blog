<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLikesTable extends Migration
{
    /* 
       Metoda up definira korak u migraciji baze podataka.
    */
    public function up()
    {
        /*
          Metoda create definira da se stvara nova tablica u bazi podataka.
          Anonimnom funkcijom definiramo kako ta nova tablica treba izgledati.
         */
        Schema::create('likes', function (Blueprint $table) {
            $table->increments('id'); /* Tablica treba sadržavati stupac id (tipa unsignedInteger) koji se uvečava kod svakog ubacivanja u tablicu. */
            $table->timestamps(); /* Tablica treba sadržavati polja created_at i updated_at u koje se bilježi vrijeme stvaranja i zadnje promjene redka tablice.  */
            $table->unsignedInteger('user_id'); /* Tablica treba sadržavati stupac u kojem se bilježi tko je postavio like. */
            $table->foreign('user_id')->references('id')->on('users'); /* Treba se stvoriti vanjski ključ koji povezuje stupac user_id ove tablice i id tablice users. */
            $table->unsignedInteger('post_id'); /* Tablica treba sadržavati stupac u kojem se bilježi na kojem postu je like postavljen. */
            $table->foreign('post_id')->references('id')->on('posts'); /* Treba se stvoriti vanjski ključ koji povezuje stupac post_id ove tablice i id tablice posts. */
            $table->unique(['user_id', 'post_id']); /* Korisnik treba moći postaviti samo jedan like na post, stoga se postavlja ogranjčenje da kombinacija korisnika i posta mora biti jedinstvena. */
        });
    }

    /* 
       Metoda down treba definirati operacije kojima se poništava ono što je napravljeno u metodi up.
       Pomoću metode down bazu podataka možemo vratiti u neko prijašnje stanje.
     */
    public function down()
    {
        Schema::dropIfExists('likes'); /* Naredba kojom se, ukoliko postoji, briše tablica likes. */
    }
}
