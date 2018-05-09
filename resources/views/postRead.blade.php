@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ $title }}</div>
                <div class="card-body">
		    {{ $content }}
		</div>
		<div class="card-footer text-muted">
		    <div>{{ $likes_count }}</div>
		    @auth
		    @if($user_liked === null)
		    <form method="POST" action="{{ route('like', ['id' => $id]) }}">
			@csrf
			<button type="submit" class="btn btn-primary mb-2">Like</button>
		    </form>
		    @else
		    <form method="POST" action="{{ route('unlike', ['id' => $id]) }}">
			@csrf
			<button type="submit" class="btn btn-primary mb-2">Unlike</button>
		    </form>
		    @endif
		    @endauth
		    @foreach($tags as $tag)
			<div>{{ $tag->name }}</div>
		    @endforeach
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="row justify-content-center">
	<div class="col-md-8">
	    <div class="card">
		<div class="card-body">
		    @guest
		    <h5 class="card-title text-center"> You need to log in to comment this blog post.</h5>
		    @else
		    <h5 class="card-title">Add comment</h5>
		    <form method="POST" action="{{ route('comment', ['id' => $id]) }}">
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
    @if(count($comments) === 0)
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
	@foreach($comments as $comment)
	    <div class="row justify-content-center">
		<div class="col-md-8">
		    <div class="card">
			<div class="card-body">
    			    <h5 class="card-title">{{ $comment->user->name }}</h5>
			    {{ $comment->comment_text }}
			</div>
		    </div>
		</div>
	    </div>
	@endforeach
    @endif
</div>
@endsection
