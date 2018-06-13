@extends('layouts.app')
{{-- @extends('layouts.app') označava da ovaj view koristi datoteku /resources/views/layouts/app.blade.php kao predložak. --}}
{{-- @section('content') i @endsection označavaju da se na mjesto označeno sa @yield('content') u datoteci predloška ubacuje tekst koji se nalazi između njih. --}}
{{-- Ovaj view se poziva samo iz metode createForm FormController-a.  --}}
{{-- kartice su jedan od elemenata Bootstrap frameworka, https://getbootstrap.com/docs/4.0/components/card/ --}}
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
	    {{-- prikaz forme se vrši u obliku Bootstrap kartice --}}
            <div class="card">
                <div class="card-header">New blog post</div>
                <div class="card-body">
		    {{-- forma  za unos novog blog posta --}}
                    <form method="POST" action="{{ route('create') }}">
			{{-- na mjesto označeno sa @csrf Laravel ubacuje csrf token --}}
			@csrf 
			<div class="form-group">
			    <label for="title">Title</label>
			    <input type="text" class="form-control" id="title" name="title">
			</div>
			<div class="form-group">
			    <label for="content">Content</label>
			    <textarea class="form-control" id="content" rows="5" name="content"></textarea>
			</div>
			<div class="form-group">
			    <label for="title">Tags</label>
			    <input type="text" class="form-control" id="tags" name="tags" placeholder="first tag, second tag, third tag">
			</div>
			<button type="submit" class="btn btn-primary mb-2">Post</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
