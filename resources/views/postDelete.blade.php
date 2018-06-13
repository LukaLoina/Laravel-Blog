@extends('layouts.app')
{{-- @extends('layouts.app') označava da ovaj view koristi datoteku /resources/views/layouts/app.blade.php kao predložak. --}}
{{-- @section('content') i @endsection označavaju da se na mjesto označeno sa @yield('content') u datoteci predloška ubacuje tekst koji se nalazi između njih. --}}
{{-- Ovaj view se poziva samo iz metode deleteForm FormController-a. --}}
{{-- kartice su jedan od elemenata Bootstrap frameworka, https://getbootstrap.com/docs/4.0/components/card/ --}}
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
	    {{-- kartica sa upitom za korisnika --}}
            <div class="card">
                <div class="card-header">Delete blog post</div>
                <div class="card-body">
		    {{-- forma u kojoj korisnik mora potvrditi da želi obrisati blog post --}}
                    <form method="POST" action="{{ route('delete', ['id' => $id]) }}">
			@csrf
			<h5 class="card-title">{{ $title }}</h5>
			<div class="form-check">
			    <input class="form-check-input" type="checkbox" value="delete" id="conformationCheckbox" name="conformationCheckbox">
			    <label class="form-check-label" for="conformationCheckbox">
				Delete this post.
			    </label>
			</div>
			<button type="submit" class="btn btn-primary mb-2">Post</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
