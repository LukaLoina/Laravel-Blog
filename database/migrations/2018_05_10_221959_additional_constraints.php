<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdditionalConstraints extends Migration
{
     /* 
       Metoda up definira korak u migraciji baze podataka.
    */
    public function up()
    {
        /*  
            U aplikaciji se pojavio bug. Nije bilo moguće izbrisati blog post zato što je blog post bio povezan sa drugim tablicama pomoću vanjskih ključeva.
            Kako bi se taj bug popravio potrebno je na vanjskim ključevima navesti da se kod brisanja reda iz tablice na koju vanjski ključ pokazuje briše i red iz tablice koja sadrži vanjski ključ.
            U Laravel-u nije moguće mjenjati vanjske ključeve, stoga se oni moraju ukloniti, te se moraju ponovno stvoriti vanjski ključevi koji sadrže onDelete uvjet.
            Ovaj postupak se provodi kod tablica post_tag, likes i comments.
        */
         Schema::table('post_tag', function (Blueprint $table) {
             $table->dropForeign(['tag_id']);
             $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
             $table->dropForeign(['post_id']);
             $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
        });

         Schema::table('likes', function (Blueprint $table) {
             $table->dropForeign(['post_id']);
             $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
             $table->dropForeign(['user_id']);
             $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

         Schema::table('comments', function (Blueprint $table) {
             $table->dropForeign(['post_id']);
             $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
             $table->dropForeign(['user_id']);
             $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /* 
       Metoda down treba definirati operacije kojima se poništava ono što je napravljeno u metodi up.
       Pomoću metode down bazu podataka možemo vratiti u neko prijašnje stanje.
     */
    public function down()
    {
        /*
          Kako bi vratili stanje na stanje prije primjene up metode potrebno je ukloniti nove vanjske ključeve i stvoriti vanjske ključeve bez onDelete uvjeta.
         */
        Schema::table('post_tag', function (Blueprint $table) {
            $table->dropForeign(['tag_id']);
            $table->foreign('tag_id')->references('id')->on('tags');
            $table->dropForeign(['post_id']);
            $table->foreign('post_id')->references('id')->on('posts');
        });

        Schema::table('likes', function (Blueprint $table) {
             $table->dropForeign(['post_id']);
             $table->foreign('post_id')->references('id')->on('posts');
             $table->dropForeign(['user_id']);
             $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('comments', function (Blueprint $table) {
             $table->dropForeign(['post_id']);
             $table->foreign('post_id')->references('id')->on('posts');
             $table->dropForeign(['user_id']);
             $table->foreign('user_id')->references('id')->on('users');
        });
    }
}
