<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Comment;

class CommentsController extends Controller
{
    public function __construct()
    {
        /* Middleware auth omogučava pristup samo prijavljenim ('logged-in') korisnicima. */
        /* Middleware je funkcija koja se izvršava prije izvršenja zahtjeva korisnika. Takva funkcija može prekinuti izvršenje, napraviti izmjene na zahtjevu, zabilježiti neko svojstvo zahtjeva, i sl.
        */
        $this->middleware('auth'); /* Samo prijavljeni korisnici mogu postaviti komentar. */
    }


    /*
      U /routes/web.php je definirana POST ruta "comment/{id}" preko koje se poziva metoda comment.
      POST zahtjev u sebi može prenjeti podatke, stoga Laravel ovoj metodi predaje argument $request koji sadrži te vrijednosti.
     */
    public function comment(Request $request, $id) /* Metoda koja dodaje komentar u bazu podataka. */
    {
        /* Provjerava je li korisnik unio tekst komentara.
           Ukoliko tekst nije unesen izvršavanje metode se prekida.
        */
        $validatedData = $request->validate([
            'comment_text' => 'required'
        ]);

        /* Pomoću metode create se stvara novi objekt modela Comment sa definiranim vrijednostima.  */
        /* Auth::id() je metoda koja vraća User Id korisnika koji šalje upit na web server, tj. korisnika koji postavlja komentar.*/
        /* Iz $request-a se pomoću metode input vadi vrijednost polja 'comment_text' */
        $comment = Comment::create([
            'user_id' => Auth::id(),
            'post_id' => $id,
            'comment_text' => $request->input('comment_text')
        ]);

        $comment->save(); /* Pozivom metode save se vrijednosti Comment modela pohranjuju u bazu podataka u tablici comments. */
        return redirect()->route('read', ['id' => $id]); /* Korisnik se preusmjerava na stranicu blog posta na koji je postavio komentar, tj. korisnik se preusmjerava na rutu read sa postavljenim parametrom id. */
    }
}
