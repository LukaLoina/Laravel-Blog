@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Delete blog post</div>
                <div class="card-body">
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
