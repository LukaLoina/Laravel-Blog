<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Like;

class LikesController extends Controller
{
    //

    public function __construct()
    {
        /* Omogučava pristup samo prijavljenim ('logged-in') korisnicima. */
        /* Middleware je funkcija koja se izvršava prije izvršenja zahtjeva korisnika. 
           Takva funkcija može prekinuti izvršenje, napraviti izmjene na zahtjevu, zabilježiti neko svojstvo zahtjeva, i sl.
        */
        $this->middleware('auth'); /* Samo prijavljeni korisnici imaju pravo staviti i skinuti like sa posta. */
    }

    /* 
       U /routes/web.php je definirana POST ruta 'like/{id}' preko koje se poziva metoda like.
     */
    public function like($id) /* Metoda koja postavlja like na blog post. */
    {
        /*
          Pomoću metode create se stvara novi objekt modela Like sa definiranim vrijednostima.
          Auth::id() je metoda koja vraća User Id korisnika koji šalje upit na server, tj. koji postavlja like na post.
         */
        $like = Like::create([
            'user_id' => Auth::id(),
            'post_id' => $id
        ]);
        $like->save(); /* Vrijednosti iz modela se pohranjuju u bazu podataka. */
        return redirect()->route('read', ['id' => $id]); /* Korisnik se preusmjerava na stranicu blog posta na koji je stavio like. */
    }

   /*
     U /routes/web.php je definirana POST ruta 'unlike/{id}' preko koje se poziva metoda unlinke.
    */
    public function unlike($id) /* Metoda koja uklanja like sa blog posta.  */
    {
        /* 
           Iz baze podataka se dohvaća like koji je trenutni korisnik postavio na trenutni blog post.
           Metodom where se postavljaju ograničenja na redove koje želimo dohvatiti iz baze podataka.
           Zbog ograničenja postavljenih na bazi podataka, u datoteci /database/migrations/2018_05_08_162743_create_likes_table.php, znamo da postoji jedan ili niti jedan takav like.
           Također, nema smisla brisati nepostojeći like.
           Iz tih razloga, umjesto da dohvačamo listu sa jednim ili nijednim elementom, kod pojednostavljujemo korištenjem funkcije firstOrFail koja ili dohvaća jedan red (ukoliko postoji više redova koji zadovoljavaju dane uvjete vraća se prvi red koji zadovoljava uvjete) iz baze podataka ili prekida rad metode.
         */
        /* Auth::id() je metoda koja vraća User Id korisnika koji šalje upit na web server, tj. korisnika koji pokušava obrisati like.*/
        $like = Like::where([['user_id', Auth::id()], ['post_id', $id]])->firstOrFail();
        $like->delete(); /* Like se briše iz baze podataka pomoću metode delete. */
        return redirect()->route('read', ['id' => $id]); /* Korisnik se preusmjerava natrag na stranicu sa koje je uklonio like. */
    }
}
