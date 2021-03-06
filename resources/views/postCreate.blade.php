@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">New blog post</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('create') }}">
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
