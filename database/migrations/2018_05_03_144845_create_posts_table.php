<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
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
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id'); /* Tablica treba sadržavati stupac id (tipa unsignedInteger) koji se uvečava kod svakog ubacivanja u tablicu. */
            $table->timestamps(); /* Tablica treba sadržavati polja created_at i updated_at u koje se bilježi vrijeme stvaranja i zadnjeg uređivanja blog posta.  */
            $table->string('title'); /* Tablica treba sadržavati stupac za naslov u koji se upisuje tekst. */
            $table->string('content'); /* Tablica treba sadržavati stupac za sadržaj blog posta u koji se upisuje tekst. */
            $table->unsignedInteger('user_id'); /* Tablica treba sadržavati stupac u kojem se bilježi tko je napravio blog post. */
            $table->foreign('user_id')->references('id')->on('users'); /* Treba se stvoriti vanjski ključ koji povezuje stupac user_id ove tablice i id tablice users. */
        });
    }

    /* 
       Metoda down treba definirati operacije kojima se poništava ono što je napravljeno u metodi up.
       Pomoću metode down bazu podataka možemo vratiti u neko prijašnje stanje.
     */
    public function down()
    {
        Schema::dropIfExists('posts'); /* Naredba kojom se, ukoliko postoji, briše tablica posts. */
    }
}
