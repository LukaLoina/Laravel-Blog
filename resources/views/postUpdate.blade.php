@extends('layouts.app')
{{-- @extends('layouts.app') označava da ovaj view koristi datoteku /resources/views/layouts/app.blade.php kao predložak. --}}
{{-- @section('content') i @endsection označavaju da se na mjesto označeno sa @yield('content') u datoteci predloška ubacuje tekst koji se nalazi između njih. --}}
{{-- Ovaj view se poziva samo iz metode updateForm PostsController-a.  --}}
{{-- kartice su jedan od elemenata Bootstrap frameworka, https://getbootstrap.com/docs/4.0/components/card/ --}}
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
	    {{-- kartica sa formom za izmjenu blog posta --}}
            <div class="card">
                <div class="card-header">Update blog post</div>
                <div class="card-body">
		    {{-- forma za izmjenu blog posta --}}
                    <form method="POST" action="{{ route('update', ['id' => $post->id]) }}">
			{{-- na mjesto označeno sa @csrf Laravel ubacuje csrf token --}}
			@csrf
			<div class="form-group">
			    <label for="title">Title</label>
			    <input type="text" class="form-control" id="title" name="title" value="{{ $post->title }}"> {{-- početna vrijednost polja za unos naslova je trenuni naslov blog posta --}}
			</div>
			<div class="form-group">
			    <label for="content">Content</label>
			    <textarea class="form-control" id="content" rows="5" name="content">{{ $post->content }}</textarea> {{-- početna vrijednost polja za unos teksta blog posta je trenuni sadržaj posta --}}
			</div>
			<div class="form-group">
			    <label for="tags">Tags</label>
			    <input type="text" class="form-control" id="tags" name="tags" value="{{ $tags }}" placeholder="first tag, second tag, third tag"> {{-- početna vrijednost polja za unos tagova je tekst koji sadrži trenutne tagove --}}
			</div>
			<button type="submit" class="btn btn-primary mb-2">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
