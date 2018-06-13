<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Post;
use App\User;

class HomeController extends Controller
{

    public function __construct()
    {
        /* Middleware auth omogučava pristup samo prijavljenim ('logged-in') korisnicima. */
        /* Middleware je funkcija koja se izvršava prije izvršenja zahtjeva korisnika. Takva funkcija može prekinuti izvršenje, napraviti izmjene na zahtjevu, zabilježiti neko svojstvo zahtjeva, i sl.
         */
        $this->middleware('auth')->except(['filter', 'sort']); /* Samo prijavljeni korisnici mogu vidjeti stranicu o sebi. Postove pretraživati mogu svi. */
    }

     /* U /routes/web.php je definirana GET ruta '/home' preko koje se poziva metoda index. */
    public function index() /* Vraća stranicu o korisniku. */
    {
        if(!Auth::check()) /* provjerava je li korisnik prijavljen */
        {
            abort(403, "Unauthorized action."); /* Ukoliko korisnik nije prijavljen onda se vraća progreška koja govori korisniku da nema pravo pristupa. */
        }
        $user = User::find(Auth::id()); /* Pomoću modela User se dohvaća trenutni korisnik. Auth::id() je metoda koja daje User Id trenutno prijavljenog korisnika koji vrši upit na web server. Metoda find User modela dohvaća korisnika iz baze podataka prema User Id-u. */
        $posts = Post::where('user_id', Auth::id())->withCount(['likes', 'comments'])->get(); /* Pomoću modela Post se dohvaćaju svi postovi trenutnog korisnika. Metodom where ograničava dohvaćene postove na one koji imaju id trenutnog korisnika u stupcu 'user_id', tj. ograničava dohvačanje postova na postove trenutnog korisnika. Metodom withCount dohvaća se broj like-ova i komentara koji su povezani sa postom. Metodom get se izvršava dohvat iz baze uz prije navedene uvjete.  */
        return view('home', ['user' => $user, 'posts' => $posts->sortByDesc('updated_at')]); /* Funkcijom view se vrši  */ 
    }

     /*
      U /routes/web.php je definirana POST ruta '/sort/{order_by}' preko koje se poziva metoda filter.
      POST zahtjev u sebi može prenjeti podatke, stoga Laravel ovoj metodi predaje argument $request koji sadrži te vrijednosti.
     */
    public function filter(Request $request, $order_by="date")
    {
        /* Pomoću metode query se iz URL-a dohvaća popis autora blog postova čije postove korisnik ne želi vidjeti. */
        $authors = $request->query('authors', null);
        
        /* Pomoću metode has se provjerava je li korisnik unio listu tagova prema kojima želi filtrirati blog postove. */
        /* Blog postovi koji se prikazuju korisniku moraju sadržavati sve navedene tagove. */
        if($request->has('tags'))
        {
            $tags = explode(',', $request->input('tags')); /* funkcijom explode se string u kojem su tagovi odvojeni zarezima pretvara u listu tagova*/
            $tag_array = array(); /* varijabla koja sadrži listu koja služi za praćenje unesenih tagova */
            foreach($tags as $rq_tag)
            {
                $rq_tag = trim($rq_tag); /* pomoću funkcije trim se sa početka i kraja imena taga uklanjaju razmaci */
                if($rq_tag === "") /* niti jedan blog post ne može imati prazan tag, "", kao svoj tag, stoga se taj slučaj detektira i takav tag se preskače */
                {
                    continue;
                }
                $tag_array[] = $rq_tag; /* tag se dodaje na kraj liste */
            }
            /* Tagovi se iz liste tagova pretvaraju u string u kojem su tagovi odvojeni zarezom pomoću funkcije implode. */
            return redirect()->route('sort', ['order_by' => $order_by, 'tags'=> implode(',', $tags), 'authors' => $authors]); /* korisnik se preusmjerava na rutu sort sa parametrima rute postacljenim na pravilan način.   */
        }
        return redirect()->route('sort', ['order_by' => $order_by, 'tags'=> null, 'authors' => $authors]); /* korisnik se preusmjerava na rutu sort uz izostavljanje parametra tags */
    }

    /* U /routes/web.php je definirana GET ruta '/sort/{order_by}' preko koje se poziva metoda sort. */
    public function sort(Request $request, $order_by="date") /* Ova metoda filtrira postove prema autoru posta i prema tagovima, te ih nakon toga sortira i na kraju prikazuje korisniku stranicu sa tim blog postovima. */
    {
        // order_by: "date"|"user"|"likes"
        /* U varijabli $posts će se tjekom izvođenja ove metode skupljati različite postavke dohvaćanja i uvjeti koje postovi moraju zadovoljiti kako bi bili prikazani. */
        /* Pomoću metode with navodimo da osim modela Post pri dohvatu iz baze treba dohvatiti i sve modele User i Tag koji su povezani sa Post modelima koji se dohvačaju.*/
        /* Pomoću metode withCount navodimo da se pri dohvatu Post modela iz baze treba dohvatiti i broj likeova i komentara povezanih sa pojedinim postom. */
        $posts = Post::with(['user', 'tags'])->withCount(['likes', 'comments']);
        $tags = $request->query('tags'); /* pomoću metode query se iz URL-a vadi parametar tags */
        $authors = $request->query('authors'); /* pomoću metode query se iz URL-a vadi parametar authors */
        $ignored_authors = null; /* varijabla $ignored_authors će sadržavati listu User modela autora čije blog postove ne želimo vidjeti, ova se varijabla na kraju predaje view-u */
        $authors_array = array(); /* varijabla $authors_array će sadržavati listu User Ida autora čije blog postove korisnik ne želi vidjeti  */
        
        if($authors) /* ukoliko je korisnik naveo authore čije blog postove ne želi vidjeti izvršava se sljedeći kod */
        {
            $authors_exploded = explode(',', $authors); /* string u kojem su User Idevi autora odvojeni zarezom pretvaramo u listu User Id-a */
            foreach($authors_exploded as $author) /* petlja koja se izvrši jednom za svaki User Id u listi */
            {
                if($author === "") /* ukoliko je korisnik predao prazan Id taj Id se preskače */
                {
                    continue;
                }
                $authors_array[] = $author; /* User Id authora se pohranjuje na kraj liste User Id-a autora čije blog postove korisnik ne želi vidjeti. */
            }
            $ignored_authors = User::whereIn('id', $authors_array)->get(); /* dohvačaju se User modeli svih autora koje korisnik ne želi vidjeti, oni sadrže sve informacije o tim autorima, te su potrebni za prikaz podataka u view-u */
            $posts = $posts->whereNotIn('user_id', $authors_array); /* Pomoću metode whereNotIn dodajemo uvjet koji govori da modeli Post koje dohvačamo ne smiju biti postovi koje su napisali autori čije postove korisnik koji šalje upit na web server ne želi vidjeti. Uvjet se provjerava tek pri dohvaćanju iz baze podataka.*/
        }
        
        if($tags) /* ukoliko je korisnik naveo da želi vijdeti samo postove koji imaju određene tagove onda se izvršava sljedeći kod */
        {
            $tags_exploded = explode(',', $tags); /* funkcijom explode se string u kojem su tagovi odvojeni zarezima pretvara u listu tagova*/
            foreach($tags_exploded as $rq_tag) /* petlja koja se izvrši jednom za svaki tag u listi tagova */
            {
                $query_tag = trim($rq_tag); /* funkcijom trim se uklanjaju razmaci na početku i kraju imena svakog Taga */
                if($rq_tag === "") /* ukoliko nakon uklanjanja razmaka dobijemo prazan tag to znači da taga niti nema te se prelazi na sljedeći tag */
                {
                    continue;
                }

                /* Ukoliko tag postoji, pomoću metode whereExists, dodajemo uvjet koji kaže da se dohvačaju samo blog postovi koji sadrže sve tagove. */
                /* Uvjeti se gomilaju u varijabli $posts. Uvjeti se izvršavaju tek pri dohvaćanju blog postova iz baze podataka.*/
                $posts = $posts->whereExists(function ($query) use ($query_tag) { /* Pomoću anonimne funkcije uvjet se dodaje kao podupit na bazu podataka. */
                    $query->select(DB::raw(1)) /* ako postoji red koji zadovoljava uvjete onda vraća vrijednost 1 */
                        ->from('post_tag') /* govori da se upit vrši nad tablicom post_tag u bazi podataka koja sadrži poveznice između tablica posts i tags */
                        ->join('tags', 'tags.id', '=', 'post_tag.tag_id') /* virši se povezivanje tablica post_tags i tags prema primarnom ključu tablice tags kako bi kasnije mogli filtrirati tagove prema imenu*/
                        ->whereRaw('post_tag.post_id = posts.id') /* postavlja uvjet podupita koji kaže da like čije postojanje provjeravamo mora biti povezan sa postom za koji gledamo */
                        ->where('tags.name', $query_tag); /* postavlja uvjet podupita koji kaže da se podupit gleda samo za trenutni tag */
                });
            }
        }

        $posts = $posts->get(); /* Dohvaća postove iz baze podataka uz uvjete koji su nagomilani tjekom izvođenja metode. */

        /* provjerava vrijdnost argumenta $order_by metode i ovisno o njegovoj vrijdnosti sortira dobivene postove */
        if($order_by === "date") /* sortiranje prema datumu objave */
        {
            /* Postovi se sortiraju od najnovijeg prema starijima. */
            $posts = $posts->sortByDesc('created_at');
        }
        else if($order_by === "user") /* Postovi se sortiraju prema imenu korisnika koji je objavio post. */
        {
            /* Postovi se sortiraju abecednim redom imena korisnika koji je post objavio.*/
            $posts = $posts->sortByDesc('user.name');
        }
        else if($order_by === "likes") /* Postovi se sortiraju prema broju likeova. */
        {
            /* Postovi se sortiraju od onih sa više prema onima sa manje likeova. */
            $posts = $posts->sortByDesc('likes_count');
        }
        else
        {
            /* Ukoliko je zatraženo nešto osim poznatih sortiranja korisniku se vraća pogreška. */
            abort(404, 'Action not found.');
        }
        
        return view('welcome', ['posts' => $posts, 'order_by' => $order_by, 'authors' => $authors, 'authors_array' => $authors_array, 'tags' => $tags, 'ignored_authors'=>$ignored_authors, ]); /* iz metode se vraća view /resources/views/welcome.blade.php, te mu se predaju argumenti koje će koristiti pri generiranju teksta */
    }
}
