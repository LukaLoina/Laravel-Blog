<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Post;
use App\Like;
use App\Tag;

class PostsController extends Controller
{
    public function __construct()
    {
        /* Middleware auth omogučava pristup samo prijavljenim ('logged-in') korisnicima. */
        /* Middleware je funkcija koja se izvršava prije izvršenja zahtjeva korisnika. Takva funkcija može prekinuti izvršenje, napraviti izmjene na zahtjevu, zabilježiti neko svojstvo zahtjeva, i sl.
        */
        $this->middleware('auth')->except('read'); /* Samo prijavljeni korisnici mogu napraviti novi blog post, promjeniti postoječi blog post i obrisati blog post. Čitati blog postove mogu svi. */
    }

    
    /* U /routes/web.php je definirana GET ruta '/create' preko koje se poziva metoda createForm. */
    public function createForm() /* Ova metoda vraća korisniku formu za stvaranje novog blog posta. */
    {
        return view('postCreate'); /* Vraća korisniku view iz datoteke /resources/views/postCreate.blade.php */
    }

  
    /*
      U /routes/web.php je definirana POST ruta '/create' preko koje se poziva metoda create.
      POST zahtjev u sebi može prenjeti podatke, stoga Laravel ovoj metodi predaje argument $request koji sadrži te vrijednosti.
     */
    /* U normalnom radu korisnik ovu metodu poziva kada šalje podatke iz forme koju vraća createForm metoda. */
    public function create(Request $request)  /* Ova metoda pohranjuje novi blog post u bazu podataka. */
    {

        /* 
           Metodom validate se provjerava je li korisnik unio podatke koji zadovoljavaju uvjete.
           Ukoliko podaci ne zadovoljavaju uvjete prekida se izvršenje metode.
         */
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required'
        ]);

        /* Metodom create se stvara novi Post model sa zadanim vrijednostima. */
        $post = Post::create([
            'user_id' => Auth::id(), /* Auth::id() daje id korisnika koji vrši upit na web server, tj. koji stvara novi blog post. */
            'title' => $request->input('title'), /* pomoću metode input se iz $request-a vadi vrijednost polja title iz forme */
            'content' => $request->input('content') /* pomoću metode input se iz $request-a vadi vrijednost vrijednost polja content iz forme */
        ]);
        $post->save(); /* pomoću metode save se model Post sprema u bazu podataka */

        if($request->has('tags')) /* pomoću metode has se provjerava je li korisnik unio što u polje za tagove, ukoliko nije postupak se preskače */
        {
            $tags = explode(',', $request->input('tags')); /* funkcija explode pretvara string koji sadrži tagove razdvojene zarezom u listu tagova */
            $tag_array = array();  /* stvara se varijabla pomoću koje će se pratiti Tag Id-evi unesenih tagova */
            foreach($tags as $rq_tag) /* petlja koja se izvršava za svaki element u listi tagova, element za koji se petlja trenutno izvodi pohranjen je u varijabli $rq_tag */
            {
                $rq_tag = trim($rq_tag); /* funkcija trim uklanja razmake sa početka i kraja imena taga */
                if($rq_tag === "") /* ukoliko nakon ulanjanja razmaka ne ostane ništa to znaći da trenutni element ne predstavlja nikakav tag, stoga se prelazi na sljedeći element */
                {
                    continue;
                }
                $db_tag = Tag::firstOrCreate(['name' => $rq_tag]); /* metodom firstOrCreate se dohvača tag iz baze podataka sa imenom koje je korisnik unio ili ukoliko takav tag ne postoji u bazi podataka stvara novi tag koji ima to ime, te se vraća taj novi tag */
                $tag_array[] = $db_tag->id; /* Tag Id taga se sprema na kraj liste tagova koje je korisnik naveo. */
            }
            $post->tags()->sync($tag_array);  /* Metoda tags vraća objekt koji predstavlja vezu između modela Post i Tag.
                                                 Metoda sync postavlja veze između samo onih tagova navedenih u $tag_array i blog posta.
                                                 Ukoliko je blog post bio povezan sa nekim tagom koji sada nije naveden u $tag_array ta veza se briše.
                                              */
        }
        return redirect()->route('read', ['id' => $post->id]); /* Preusmjerava korisnika na rutu read sa parametrom id postavljenim na id posta koji je upravo stvoren, tj. peusmjerava korisnika na stranicu za čitanje blog posta kojeg je upravo stvorio. */
    }

    /*
       U /routes/web.php je definirana GET ruta '/read/{id}' preko koje se poziva metoda read.
    */
    public function read($id) /* Ova metoda vraća korisniku stranicu na kojoj može čitati blog post i komentare na taj blog post, te postaviti novi komentar ili staviti/skinuti like sa posta. */
    {
        /* Pomoću metode with se navodi da se uz dohvaćanje blog posta vrši i dohvaćanje drugh modela koji su povezani sa blog postom.  */
        /* Korištenje metode with nije nužno za ispravan rad koda no onda bi se povezani modeli iz baze podataka dohvaćali jedan po jedan kada postanu potrebni zbog ćega bi imali mnoštvo upita na bazu podataka što bi usporilo izvođenje koda.*/
        /* Metodom withCount se navodi da se uz dohvaćanje blog posta dohvaća i  broj likeova i komentara koji su povezani sa blog postom kojeg dohvaćamo. */
        /* Metodom findOrFail se vrši dohvaćanje blog posta iz baze podataka prema Blog Idu. */
        /* Ukoliko dohvaćanje ne uspije onda se prekida izvođenje metode. */
        $post = Post::with(['comments', 'comments.user', 'tags', 'user'])->withCount(['likes', 'comments'])->findOrFail($id);
        $like = null; /* Stvara se varijabla like sa definiranom vrijdnošču koja predstavlja da korisnik nije postavio like. */
        if(Auth::check()) /* pomoću Auth::check() se provjerava je li korisnik koji vrši upit na web server prijavljen */
        {
            /* Metodom where se postavljaju ograničenja na likeove koji će biti dohvaćeni. */
            /* U ovom slučaju ograničava se na like koji je posatvio trenutno prijavljen korisnik i post koji se trenutno čita.*/
            /* Auth::id() daje id korisnika koji vrši upit na web server, tj. koji dohvaća stranicu za čitanje blog posta. */
            $like = Like::where([['user_id', Auth::id()], ['post_id', $id]])->first();
        }
        return view('postRead', ['post' => $post, 'user_liked' => $like]); /* vraća korisniku view /resources/views/postRead.blade.php, a u view predaje argumente 'post' i 'user_liked' koji će se moći koristiti pri generiranju teksta. */
    }

    /* 
      U /routes/web.php je definirana GET ruta '/update/{id}' preko koje se poziva metoda updateForm.
    */
    public function updateForm($id) /* Ova metoda vraća stranicu sa formom za izmjene blog posta. */
    {
        /* Metodom with se navodi da se uz dohvaćanje blog posta vrši i dohvaćanje tagova.*/
        /* Metodom findOrFail se vrši dohvačanje blog posta prema Post Idu.*/
        /* Ukoliko dohvaćanje blog posta ne uspije metoda se prekida. */
        $post = Post::with(['tags'])->findOrFail($id);
        if($post->user_id === Auth::id()) /* provjerava se je li korisnik koji postavio blog post trenutni korisnik koji želi mjenjati blog post */
        {
            $imploded_tags = $post->tags->implode('name', ', '); /* Tagovi se iz baze dohvačaju u listi, npr. ["tag1", "tag2", "tag3"], a u web pretraživaču se trebaju prikazati odvojeni zarezom. Metodom implode se tagovi spajaju u string odvojen zarezima, npr. "tag1, tag2, tag3".*/
            return view('postUpdate', ['post' => $post, 'tags' => $imploded_tags]); /* vraća korisniku view iz /resources/views/postUpdate.blade.php, a u pogled predaje argumente 'post' i 'tags' koji će se koristiti pri generiranju teksta */
        }
        else
        {
            /* Ukoliko je korisnik pokušao mjenjati blog post koji pripada drugom korisniku prikazuje se greška. */
            abort(403, 'Unauthorized action.');
        }
    }

    /*
      U /routes/web.php je definirana POST ruta '/update/{id}' preko koje se poziva metoda update.
      POST zahtjev u sebi može prenjeti podatke, stoga Laravel ovoj metodi predaje argument $request koji sadrži te vrijednosti.
     */
     /* U normalnom radu korisnik ovu metodu poziva kada šalje podatke iz forme koju vraća updateForm metoda. */
    public function update(Request $request, $id) /* Ova metoda sprema vrijdnosti iz forme u bazu podataka. */
    {
        /* 
           Metodom validate se provjerava je li korisnik unio podatke koji zadovoljavaju uvjete.
           Ukoliko podaci ne zadovoljavaju uvjete prekida se izvršenje metode.
        */
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required'
        ]);

        /*
          Metodom findOrFail se prema Post Idu iz baze podataka dohvaća blog post.
          Ukoliko dohvaćanje ne uspije izvođenje metode se prekida.
         */
        $post = Post::findOrFail($id);

        /* Korisnik može uređivati samo svoje postove, stoga se ovdje provjerava je li trenutni korisnik isti korinik koji je objavio blog post. */
        /* Auth::id() daje id korisnika koji vrši upit na web server, tj. koji pokušava izmjeniti blog post. */
        if($post->user_id === Auth::id())
        {
            $post->title = $request->input('title'); /* naslov blog posta se postavlja na vrijdnost koju je korisnik unio u formi, tekst iz forme se dohvaća metodom input */
            $post->content = $request->input('content');  /* sadržaj blog posta se postavlja na vrijdnost koju je korisnik unio u formi, tekst iz forme se dohvaća metodom input */
            $post->save(); /* izmjene se spremaju u bazu podataka */

            if($request->has('tags')) /* ukoliko tagovi nisu navedeni u formi preskaće se sljedeči postupak */
            {
                $tags = explode(',', $request->input('tags'));  /* funkcija explode pretvara string koji sadrži tagove razdvojene zarezom u listu tagova */
                $tag_array = array(); /* stvara se varijabla pomoću koje će se pratiti Tag Id-evi unesenih tagova */
                foreach($tags as $rq_tag) /* petlja koja se izvršava za svaki element u listi tagova, element za koji se petlja trenutno izvodi pohranjen je u varijabli $rq_tag */
                {
                    $rq_tag = trim($rq_tag); /* funkcija trim uklanja razmake sa početka i kraja imena taga */
                    if($rq_tag === "") /* Ukoliko ne ostane ništa od taga nakon micanja razmaka onda se prelazi na sljedeći korak petlje. Npr. ako korisnik unese "tag1, , tag2" onda će lista tagova nakon primjene funkcije trim nad svim elementima biti ["tag1", "", "tag2"].  */
                    {
                        continue;
                    }
                    $db_tag = Tag::firstOrCreate(['name' => $rq_tag]);  /* metodom firstOrCreate se dohvača tag iz baze podataka sa imenom koje je korisnik unio ili ukoliko takav tag ne postoji u bazi podataka stvara novi tag koji ima to ime, te se vraća taj novi tag */
                    $tag_array[] = $db_tag->id; /* Tag Id taga se sprema na kraj liste tagova koji su navedeni u formi. */
                }
                $post->tags()->sync($tag_array); /* Metoda tags vraća objekt koji predstavlja vezu između modela Post i Tag.
                                                 Metoda sync postavlja veze između samo onih tagova navedenih u $tag_array i blog posta.
                                                 Ukoliko je blog post bio povezan sa nekim tagom koji sada nije naveden u $tag_array ta veza se briše.
                                                 */
            }
            
            return redirect()->route('read', ['id' => $post->id]); /* Preusmjerava korisnika na rutu read sa parametrom id postavljenim na id posta koji je upravo izmjenjen, tj. peusmjerava korisnika na stranicu za čitanje blog posta kojeg je upravo izmjenio. */
        }
        else
        {
            /* Ukoliko korisnik pokušava mjenjati blog post koji mu ne pripada javlja se greška. */
            abort(403, 'Unauthorized action.');
        }
    }

    /*
      U /routes/web.php je definirana GET ruta '/delete/{id}' preko koje se poziva metoda deleteForm.
     */
    public function deleteForm($id) /* Ova metoda vraća formu na kojoj korisnik mora potvrditi da želi obrisati blog post. */
    {
        /*
          Metodom findOrFail se prema Post Idu iz baze podataka dohvaća blog post.
          Ukoliko dohvaćanje ne uspije izvođenje metode se prekida.
        */
        $post = Post::findOrFail($id);
        /* Korisnik može birsati samo svoje postove, stoga se ovdije provjerava je li trenutin korisnik isti korisnik koji je objavio blog post. */
        /* Auth::id() daje id korisnika koji vrši upit na web server, tj. koji pokušava obrisati blog post. */
        if($post->user_id === Auth::id())
        {
            /* Ukoliko korisnik ima pravo obrisati post prikazuje mu se stranica na kojoj mora potvrditi da želi obrisati post kako do birsanja nebi došlo slučajnim pritiskom na poveznicu za brisanje. */
            return view('postDelete', ['id' => $id, 'title' => $post->title]); /* vraća korisniku view iz datoteke /resources/views/postDelete.blade.php, a u pogled predaje argumente 'id' i 'title' */
        }
        else
        {
            /* Ukoliko korisnik pokušava obrisati blog post koji mu ne pripada javlja se greška. */
            abort(403, 'Unauthorized action.');
        } 
    }

    /*
      U /routes/web.php je definirana POST ruta '/delete/{id}' preko koje se poziva metoda delete.
      POST zahtjev u sebi može prenjeti podatke, stoga Laravel ovoj metodi predaje argument $request koji sadrži te vrijednosti.
     */
    /* U normalnom radu korisnik ovu metodu poziva kada šalje podatke iz forme koju vraća deleteForm metoda. */
    public function delete(Request $request, $id) /* Metoda briše blog post. */
    {
        /*
          Metodom findOrFail se prema Post Idu iz baze podataka dohvaća blog post.
          Ukoliko dohvaćanje ne uspije izvođenje metode se prekida.
        */
        $post = Post::findOrFail($id);
        
        /* Korisnik može birsati samo svoje postove, stoga se ovdije provjerava je li trenutin korisnik isti korisnik koji je objavio blog post. */
        /* Auth::id() daje id korisnika koji vrši upit na web server, tj. koji pokušava obrisati blog post. */
        if($post->user_id === Auth::id())
        {
            if($request->input('conformationCheckbox')) /* Provjerava je li korisnik u formi za brisanje označio da želi obrisati blog post. */
            {
                $post->delete(); /* Blog post se briše iz baze podataka. */
                return redirect()->route('home'); /* Korisnik se preusmjerava na stranicu sa svojim podacima i blog postovima. */
            }
            else
            {
                /* 
                   Ukoliko korisnik nije odabrao da želi obrisati blog post preusmjerava se na stranicu za čitanje blog posta.
                 */
                return redirect()->route('read', ['id' => $post->id]);
            }
        }
        else
        {
            /* Ukoliko korisnik pokušava obrisati blog post koji mu ne pripada javlja se greška. */
            abort(403, 'Unauthorized action.');
        } 
    }
}
