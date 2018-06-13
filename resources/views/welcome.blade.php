@extends('layouts.app')
{{-- @extends('layouts.app') označava da ovaj view koristi datoteku /resources/views/layouts/app.blade.php kao predložak. --}}
{{-- @section('content') i @endsection označavaju da se na mjesto označeno sa @yield('content') u datoteci predloška ubacuje tekst koji se nalazi između njih. --}}
{{-- Ovaj view se poziva samo iz metode sort HomeController-a. --}}
{{-- kartice su jedan od elemenata Bootstrap frameworka, https://getbootstrap.com/docs/4.0/components/card/ --}}
@section('content')
    <div class="container">
	<div class="row justify-content-center">
	    <div class="col-md-8">
		{{-- kartica sa formom za filtriranje blog postova --}}
		<div class="card">
		    <div class="card-header">
			options
		    </div>
		    <div class="card-body">
			{{-- forma za filtriranje postova prema tagovima --}}
			<form method="POST" action="{{ route('filter', ['order_by' => $order_by, 'authors' => $authors]) }}">
			    {{-- na mjesto označeno sa @csrf Laravel ubacuje csrf token --}}
			    @csrf
			    <div class="form-group">
				<label for="tags">Filter by tags</label>
				<input type="text" class="form-control" id="tags" name="tags" value="{{ $tags }}" placeholder="first tag, second tag, third tag">
			    </div>
			    <button type="submit" class="btn btn-primary mb-2">Filter posts</button>
			</form>
		    </div>
		    {{-- kartica sa poveznicama za odabir parametra sortiranja blog postova --}}
		    <div class="card-body">
			Order by: <a class="btn btn-primary" href="{{ route('sort', ['order_by' => "date",  'tags' => $tags, 'authors' => $authors] ) }}">Date</a> {{-- sortiranje prema datumu, od najnovijih prema starijim postovima --}}
			<a class="btn btn-primary" href="{{ route('sort', ['order_by' => "user", 'tags' => $tags, 'authors' => $authors] ) }}">Author name</a> {{-- sortiranje prema imenu autora, abecedno od a do z --}}
			<a class="btn btn-primary" href="{{ route('sort', ['order_by' => "likes", 'tags' => $tags, 'authors' => $authors] ) }}">Likes</a> {{-- sortiranje prema broju likeova, od postova sa više prema postovima sa manje likeova --}}
		    </div>
		    {{-- provjera se postoji li barem jedan autor čije postove korisnik ne želi vidjeti --}}
		    @if($ignored_authors != null)
			{{-- ukoliko postoji barem jedan takav autor prikazuje se kartica sa poveznicama za uklanjanje autora sa liste autora čije postove korisnik ne želi vidjeti --}}
		    <div class="card-body">
			Unhide user:
			{{-- pomoću petlje se sa svakog autora na listi prikazuje poveznica pomoću koje ga se može maknuti sa liste --}}
			@foreach($ignored_authors as $author)
			    <div><a class="badge badge-pill badge-primary" href="{{ route('sort', ['order_by' => $order_by, 'tags' => $tags, 'authors' => implode(',', array_diff($authors_array, [$author->id]))] ) }}">{{  $author->name }}</a></div>
			@endforeach
		    </div>
		    @endif
		</div>
	    </div>
	</div>
	{{-- petlja koja prolazi kroz sve postove i ispisuje karticu za svaki --}}
	{{-- postovi su filtrirani prema postavkama korisnika unutar controller-a --}}
	@foreach($posts as $post)
        <br>
	    <div class="row justify-content-center">
		<div class="col-md-8">
		    {{-- kartica koja sadrži informacije o blog postu --}}
		    <div class="card">
			<div class="card-header">
			    {{ $post->user->name }} {{-- prikazuje se korisničko ime korisnika koji je objavio post --}}
			</div>
			<div class="card-body">
			    <a href="{{ route('read', ['id' => $post->id]) }}"><h5 class="title">{{ $post->title }}</h5></a> {{-- prikaz naslova blog posta koji je ujedino i poveznica na stranicu za čitanje tog blog posta --}}
			    {{-- provjerava se ima li sadržaj blog posta manje od 100 znakova. --}}
			    @if(strlen($post->content) < 100)
				{{-- ukoliko je sadržaj blog posta manji od 100 znakova on se u cjelosti ispisuje --}}
				{!! nl2br($post->content) !!} {{-- funkcija nl2br pretvara znakove za novi red teksta,'\n', u HTML tagove za novi red, <br /> --}}
			    @else
				{{-- ukoliko je sadržaj blog posta 100 znakova ili više ispisuje se prvih 97 znakova te se dodaju tri točke kako bi se čitatelju signaliziralo da ima još sadržaja. --}}
				{!!  nl2br(substr($post->content, 0, 97)."...") !!} {{-- funkcija nl2br pretvara znakove za novi red teksta,'\n', u HTML tagove za novi red, <br /> --}}
			    @endif
			</div>
			<div class="card-footer">
			    <div>{{ $post->likes_count }} <img class="like-icon" src="/thumbsup.svg" alt="likes"></div> {{-- ispisuje se broj likeova blog posta --}}
			    <div>{{ $post->comments_count }} <img class="like-icon" src="/comment.svg" alt="commentss"></div> {{-- ispisuje se broj komentara blog posta --}}
			    {{-- pomoću petlje se ispisuju svi tagovi blog posta --}}
			    @foreach($post->tags as $tag)
				<span class="badge badge-primary"> {{ $tag->name }} </span> {{-- ispisuje ime taga u HTML tagu koji mu daje neko oblikovanje --}}
			    @endforeach
			    <div>posted at {{ $post->created_at->format("H:i d/m/y") }}</div> {{-- ispisuje se datum kada je blog post stvoren --}}
			    {{-- ispisuje se poveznica putem koje korisnik može dodati autora posta na listu autora čije postove ne želi vijdeti --}}
			    <a class="btn btn-outline-primary btn-sm" style="line-height: 1;" href="{{ route('sort', ['order_by' => $order_by, 'tags' => $tags, 'authors' => implode(',',  array_merge($authors_array, [$post->user->id]))])}}">
				Hide this user
			    </a>
			</div>
		    </div>
		</div>
	    </div>
	@endforeach
    </div>
@endsection
