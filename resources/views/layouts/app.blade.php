{{-- Ova datoteka sadrži predložak čijim proširenjem se stvaraju svi drugi view-i.--}}
{{-- Moguće je imati više ovakvih predložaka, no to u ovoj aplikacij nije bilo potrebno. --}}
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}"> {{-- Iz datoteke /config/app.php se dohvaća postavka 'locale' i ubacuje u atribut lang HTML-a --}}
<head>
    <meta charset="utf-8"> {{-- postavlja se kodiranje teksta na utf-8 kod --}}
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> {{-- postavka koja govori Microsoft-ovom Internet exploreru koji način rada treba koristiti, IE=edge označava da treba koristiti najnoviji način rada koji ima --}}
    <meta name="viewport" content="width=device-width, initial-scale=1"> {{-- postavke veličine ekrana koje se moraju koristiti kako bi se stranica ispravno prikazala na mobilnim uređajima --}}

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}"> {{-- csrf token je nasumičan broj koji je drugačiji kod svakog dohvačanja stranice a postavlja se zbog sigurnosti stranice --}}

    <title>{{ config('app.name', 'Laravel') }}</title> {{-- kao naslov html dokumenta postavlja se tekst varijable name iz /config/app.php dokumenta, ukoliko to ne uspije kao naslov se postavlja zadana vrijednost Laravel --}}

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script> {{-- Atribut src se postavlja kao poveznica na datoteku /public/js/app.js --}}

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css"> {{-- dohvaća se vanjski font Raleway sa google fonts-a --}}

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet"> {{-- kao atribut href se postavlja poveznica na datoteku /public/css/app.css --}}
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}"> {{-- Atributom href se određuje URL na koji poveznica vodi. Ova poveznica vodi na početnu stranicu. --}}
                    {{ config('app.name', 'Laravel') }}
                </a>
		{{-- Ukoliko je veličina ekrana ispod neke granične veličine poveznice izbornika se skrivaju u padajučem izborniku, te se pojavljuje button (koji je u redu ispod ovog komentara) kojim se taj padajuči izbornik otvara ili zatvara. --}}
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
			{{-- pomoću @guest se provjerava je li korisnik prijavljen --}}
                        @guest
			    {{-- Ukoliko korisnik nije prijavljen u izborniku se prikazuju poveznice za prijavu i registraciju. --}}
                            <li><a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a></li>
                            <li><a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a></li>   
                        @else
			    {{-- Ukoliko je korisnik prijavljen u izborniku se prikazuju sljedeće poveznice: --}}
                            <li><a class="nav-link" href="{{ route('sort', ['order_by' => "date"]) }}">Browse posts</a></li> {{-- poveznica na stranicu za pretraživanje blog postova --}}
			    <li><a class="nav-link" href="{{ route('create') }}">Post</a></li> {{-- poveznica na stranicu za objavu novog blog posta --}}
			    <li><a class="nav-link" href="{{ route('home') }}"> {{ Auth::user()->name }}</a></li> {{-- poveznica na stranicu sa informacijama o korisniku, kao tekst ove poveznice koristi se koriničko ime --}}
			    <li><a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li> {{-- Poveznica za odjavu. Ova poveznica u sebi sadrži JavaScript kod zbog kojeg ne vodi na drugu stranicu već aktivira formu, koja se može vidjeti u redu ispod ovoga komentara, koja onda odjavljuje korisnika. --}}
			    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')  {{-- @yield oznaćava i imenuje mjesto na koje viewi koji koriste ovaj predložak mogu ubacivati tekst --}}
        </main>
    </div>
</body>
</html>
