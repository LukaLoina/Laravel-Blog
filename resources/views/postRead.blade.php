@extends('layouts.app')
{{-- @extends('layouts.app') označava da ovaj view koristi datoteku /resources/views/layouts/app.blade.php kao predložak. --}}
{{-- @section('content') i @endsection označavaju da se na mjesto označeno sa @yield('content') u datoteci predloška ubacuje tekst koji se nalazi između njih. --}}
{{-- Ovaj view se poziva samo iz metode read PostsController-a.  --}}
{{-- kartice su jedan od elemenata Bootstrap frameworka, https://getbootstrap.com/docs/4.0/components/card/ --}}
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
	    {{-- kartica sa tekstom blog posta --}}
            <div class="card">
                <div class="card-header">{{ $post->user->name }}</div>
                <div class="card-body">
		    <h5 class="title">{{ $post->title }}</h5>
		    {!! nl2br($post->content) !!} {{-- funkcija nl2br pretvara znakove za novi red teksta,'\n', u HTML tagove za novi red, <br /> --}}
		</div>
		<div class="card-footer text-muted">
		    <div>{{ $post->comments_count }} <img class="like-icon" src="/comment.svg" alt="commentss"></div> {{-- prikaz broja komentara --}}
		    <div>{{ $post->likes_count }} <img class="like-icon" src="/thumbsup.svg" alt="likes"></div> {{-- prikaz broja likeova --}}
		    {{-- tekst između @auth i @endauth se prikazuje samo kada je korisnik prijavljen --}}
		    @auth
		    {{-- provjerava se je li korisnik več like-ao ovaj blog post --}}
		    @if($user_liked === null)
			{{-- ako korisnik nije like-ao onda se prikazuje forma za postavljanje likea na blog post --}}
			<form method="POST" action="{{ route('like', ['id' => $post->id]) }}">
			    {{-- na mjesto označeno sa @csrf Laravel ubacuje csrf token --}}
			    @csrf
			<button type="submit" class="btn btn-primary mb-2">Like</button>
		    </form>
		    @else
		    {{-- ako je korisnik like-ao onda se prikazuje forma za uklanjanje like-a sa blog posta --}}
		    <form method="POST" action="{{ route('unlike', ['id' => $post->id]) }}">
			{{-- na mjesto označeno sa @csrf Laravel ubacuje csrf token --}}
			@csrf
			<button type="submit" class="btn btn-primary mb-2">Unlike</button>
		    </form>
		    @endif
		    @endauth
		    tags:
		    {{-- pomoću petlje se ispisuju svi tagovi blog posta --}}
		    @foreach($post->tags as $tag)
			<span class="badge badge-primary"> {{ $tag->name }} </span>
		    @endforeach
		    <div>posted at {{ $post->created_at->format("H:i d/m/y") }}</div> {{-- ispisuje se vrijeme kada je blog post napravljen --}}
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="row justify-content-center">
	<div class="col-md-8">
	    {{-- kartica za postavljanje novog komentara --}}
	    <div class="card">
		<div class="card-body">
		    {{-- @guest provjerava je li korisnik ne prijavljen --}}
		    @guest
		    {{-- ukoliko korisnik nije prijavljen prikazuje se tekst koji korisniku govori da samo prijavljeni korisnici mogu postavljati komentare --}}
		    <h5 class="card-title text-center"> You need to log in to comment this blog post.</h5>
		    @else
		    {{-- ukoliko je korisnik prijavljen prikazuje se kartica sa formom za unos novog komentara --}}
		    <h5 class="card-title">Add comment</h5>
		    <form method="POST" action="{{ route('comment', ['id' => $post->id]) }}">
			{{-- na mjesto označeno sa @csrf Laravel ubacuje csrf token --}}
			@csrf
			<div class="form-group">
			    <textarea class="form-control" id="content" rows="5" name="comment_text"></textarea>
			</div>
			<button type="submit" class="btn btn-primary mb-2">Comment</button>
		    </form>
		    @endguest
		</div>
	    </div>
	</div>
    </div>
    <br>
    {{-- provjerava se postoji li koji komentar na blog post --}}
    @if(count($post->comments) === 0)
	{{-- ukoliko ne postoji komentar korisniku se prikazuje se tekst koji korisniku govori da nema komentara na ovaj blog post  --}}
	<div class="row justify-content-center">
	    <div class="col-md-8">
		<div class="card">
		    <div class="card-body">
			<h5 class="card-title text-center">There are no comments for this post.</h5>
		    </div>
		</div>
	    </div>
	</div>
    @else
	{{-- ukoliko postoji barem jedan komentar onda se pomoću petlje komentari ispisuju --}}
	@foreach($post->comments as $comment)
	    <div class="row justify-content-center">
		<div class="col-md-8">
		    {{-- kartica sa komentarom --}}
		    <div class="card">
			<div class="card-body">
    			    <h5 class="card-title">{{ $comment->user->name }}</h5> {{-- ispis imena korisnika koji je komentar postavio --}}
			    <h6 class="card-subtitle mb-2 text-muted">posted at {{ $comment->created_at->format("H:i d/m/y") }}</h6> {{-- ispis vremena kada je korisnik komentar postavio --}}
			    {!!  nl2br($comment->comment_text) !!} {{-- ispis teksta komentara --}} {{-- funkcija nl2br pretvara znakove za novi red teksta,'\n', u HTML tagove za novi red, <br /> --}}
			</div>
		    </div>
		</div>
	    </div>
	@endforeach
    @endif
</div>
@endsection
