<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagsTable extends Migration
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
        Schema::create('tags', function (Blueprint $table) {
            $table->increments('id'); /* Tablica treba sadržavati stupac id (tipa unsignedInteger) koji se uvečava kod svakog ubacivanja u tablicu. */
            $table->timestamps();  /* Tablica treba sadržavati polja created_at i updated_at u koje se bilježi vrijeme stvaranja i zadnje promjene redka tablice.  */
            $table->string('name'); /* Tablica treba sadržavati supac name u koje se upisuje tekst imena taga. */
        });

        /*
          Metoda create definira da se stvara nova tablica u bazi podataka.
          Anonimnom funkcijom definiramo kako ta nova tablica treba izgledati.
          Postovi i tagovi tvore relaciju više na više. Isti tag može biti pridjeljen više postova, post može imati više tagova. Stoga se stvara posebna tablica, post_tag, u kojoj se bilježi koji postovi i tagovi su međusobno povezani.
         */
        Schema::create('post_tag', function (Blueprint $table) {
            $table->unsignedInteger('tag_id'); /* Tablica treba sadržavati stupac u kojem se bilježi koji tag se pridjeljuje. */
            $table->foreign('tag_id')->references('id')->on('tags'); /* Treba se stvoriti vanjski ključ koji povezuje stupac tag_id ove tablice i id tablice tags. */
            $table->unsignedInteger('post_id'); /* Tablica treba sadržavati stupac u kojem se bilježi na kojem postu je tag postavljen. */
            $table->foreign('post_id')->references('id')->on('posts'); /* Treba se stvoriti vanjski ključ koji povezuje stupac post_id ove tablice i id tablice posts. */
            $table->unique(['tag_id', 'post_id']); /* Treba se postaviti ograničenje na bazi podataka koje jamči jedinstvenost kombinacije u ključeva tag-a i post-a u tablici. Ovo ograničenje štiti od toga da slučajno u kodu više puta isti tag pridružimo istom postu. */
        });
    }

     /* 
       Metoda down treba definirati operacije kojima se poništava ono što je napravljeno u metodi up.
       Pomoću metode down bazu podataka možemo vratiti u neko prijašnje stanje.
     */
    public function down()
    {
        Schema::dropIfExists('tag_post'); /* Naredba kojom se, ukoliko postoji, briše tablica likes. */
        Schema::dropIfExists('tags'); /* Naredba kojom se, ukoliko postoji, briše tablica likes. */
    }
}
