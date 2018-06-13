<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Post extends Migration
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
        Schema::table('posts', function (Blueprint $table) {
            $table->text('content')->change(); /* tip stupca u kojem se sprema tekst blog posta se mjenja iz string u text zato što tekst dozvoljava pohranu više znakova */
        });
    }

     /* 
       Metoda down treba definirati operacije kojima se poništava ono što je napravljeno u metodi up.
       Pomoću metode down bazu podataka možemo vratiti u neko prijašnje stanje.
     */
    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('content')->change(); /* tip stupca se mjenja natrag u string */
        });
    }
}
