@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ $post->user->name }}</div>
                <div class="card-body">
		    <h5 class="title">{{ $post->title }}</h5>
		    {{ $post->content }}
		</div>
		<div class="card-footer text-muted">
		    <div>{{ $post->comments_count }} <img class="like-icon" src="/comment.svg" alt="commentss"></div>
		    <div>{{ $post->likes_count }} <img class="like-icon" src="/thumbsup.svg" alt="likes"></div>
		    @auth
		    @if($user_liked === null)
		    <form method="POST" action="{{ route('like', ['id' => $post->id]) }}">
			@csrf
			<button type="submit" class="btn btn-primary mb-2">Like</button>
		    </form>
		    @else
		    <form method="POST" action="{{ route('unlike', ['id' => $post->id]) }}">
			@csrf
			<button type="submit" class="btn btn-primary mb-2">Unlike</button>
		    </form>
		    @endif
		    @endauth
		    tags:
		    @foreach($post->tags as $tag)
			<span class="badge badge-primary"> {{ $tag->name }} </span>
		    @endforeach
		    <div>posted at {{ $post->created_at->format("H:i d/m/y") }}</div>
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
		    <form method="POST" action="{{ route('comment', ['id' => $post->id]) }}">
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
    @if(count($post->comments) === 0)
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
	@foreach($post->comments as $comment)
	    <div class="row justify-content-center">
		<div class="col-md-8">
		    <div class="card">
			<div class="card-body">
    			    <h5 class="card-title">{{ $comment->user->name }}</h5>
			    <h6 class="card-subtitle mb-2 text-muted">posted at {{ $comment->created_at->format("H:i d/m/y") }}</h6>
			    {{ $comment->comment_text }}
			</div>
		    </div>
		</div>
	    </div>
	@endforeach
    @endif
</div>
@endsection
