@extends('layouts.app')
{{-- @extends('layouts.app') označava da ovaj view koristi datoteku /resources/views/layouts/app.blade.php kao predložak. --}}
{{-- @section('content') i @endsection označavaju da se na mjesto označeno sa @yield('content') u datoteci predloška ubacuje tekst koji se nalazi između njih. --}}
{{-- Iz controller-a se u ovaj view predaju varijable $user i $posts --}}
{{-- Ovaj view se poziva samo iz metode index HomeController-a.  --}}
{{-- kartice su jedan od elemenata Bootstrap frameworka, https://getbootstrap.com/docs/4.0/components/card/ --}}
@section('content')
    <div class="container">
	<div class="row justify-content-center">
            <div class="col-md-8">
		{{-- prikaz kartice sa nekim osvnovnim informacijama o korisniku --}}
		<div class="card">
                    <div class="card-header">Your information.</div>
                    <div class="card-body">
			<div>Name: {{ $user->name }}</div> {{-- ispisuje se korisničko ime korisnika --}}
			<div>Email: {{ $user->email }}</div> {{-- ispisuje se email korisnika --}}
			<div>Joined at: {{ $user->created_at->format("H:i d/m/y") }}</div> {{-- ispisuje se vrijeme kada je korisnik napravio račun, metodom format se postavlja način ispisa vremena --}}
                    </div>
		</div>
            </div>
	</div>
	<br>
	<div class="row justify-content-center">
            <div class="col-md-8">
		{{-- prikaz kartice sa postovima korisnika --}}
		<div class="card">
		    <div class="card-header">Your posts</div>
		    @if(count($posts) === 0)
			{{-- ukoliko korisnik nije objavio niti jedan post prikazuje se tekst koji korisniku treba dati do znanja da će se na tom mjestu pojaviti postovi kada ih napravi --}}
			<div class="card-body">
			    <h5 class="card-title text-center">You have no posts.</h5>
			</div>
		    @else
			{{-- petlja koja prolazi kroz sve blog postove koje je korisnik objavio --}}
			{{-- tijelo petlje se ispisuje onoliko puta koliko se puta petlja ponovila --}}
			@foreach($posts as $post)
			    {{-- postovi se prikazuju kao liste unutar tijela kartice --}}
			    <ul class="list-group list-group-flush">
				<li class="list-group-item">
				    <h5 class="title"><a href="{{ route('read', ['id' => $post->id]) }}">{{ $post->title }}</a></h5> {{-- prikazuje se naslov posta koji je ujedino i poveznica na stranicu za čitanje posta --}}
				    <div>{{ $post->likes_count }} <img class="like-icon" src="/thumbsup.svg" alt="likes"></div> {{-- prikazuje se broj likeova posta --}}
                                    <div>{{ $post->comments_count }} <img class="like-icon" src="/comment.svg" alt="commentss"></div> {{-- prikazuje se broj komentara na postu --}}
				    {{-- petlja koja prolazi kroz sve tagove trenutnog posta --}}
				    @foreach($post->tags as $tag)
					<span class="badge badge-primary"> {{ $tag->name }} </span> {{-- ispisuje se naziv svakoga taga sa određenim oblikovanjima --}}
				    @endforeach
				    <div>
					created at {{ $post->created_at->format("H:i d/m/y") }} {{-- ispisuje se kada je post objavljen --}}
					@if($post->created_at != $post->updated_at)
					    {{-- ako je korisnik naknadno uređivao blog post onda se prikazuje vrijeme zadnje promjene --}}
					    , last updated at {{ $post->updated_at->format("H:i d/m/y") }}
					@endif
				    </div>
				    {{-- za svaki post se prikazuju poveznice za brisanje i uređivanje tog posta --}}
				    <div> <a class="btn btn-primary btn-sm" href="{{ route('update', ['id' => $post->id]) }}">Update</a> <a class="btn btn-primary btn-sm" href="{{ route('delete', ['id' => $post->id]) }}">Delete</a></div>
				</li>
			    </ul>
			@endforeach
		    @endif
			    
		</div>
	    </div>
	</div>
    </div>
@endsection
