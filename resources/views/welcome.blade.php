@extends('layouts.app')

@section('content')
    <div class="container">
	<div class="row justify-content-center">
	    <div class="col-md-8">
		<div class="card">
		    <div class="card-header">
			options
		    </div>
		    <div class="card-body">
			<form method="POST" action="{{ route('filter', ['order_by' => $order_by, 'authors' => $authors]) }}">
			    @csrf
			    <div class="form-group">
				<label for="tags">Filter by tags</label>
				<input type="text" class="form-control" id="tags" name="tags" value="{{ $tags }}" placeholder="first tag, second tag, third tag">
			    </div>
			    <button type="submit" class="btn btn-primary mb-2">Filter posts</button>
			</form>
		    </div>
		    <div class="card-body">
			Order by: <a class="btn btn-primary" href="{{ route('sort', ['order_by' => "date",  'tags' => $tags, 'authors' => $authors] ) }}">Date</a>
			<a class="btn btn-primary" href="{{ route('sort', ['order_by' => "user", 'tags' => $tags, 'authors' => $authors] ) }}">Author name</a>
			<a class="btn btn-primary" href="{{ route('sort', ['order_by' => "likes", 'tags' => $tags, 'authors' => $authors] ) }}">Likes</a>
		    </div>
		    @if($ignored_authors != null)
		    <div class="card-body">
			Unhide user:
			@foreach($ignored_authors as $author)
			    <div><a class="badge badge-pill badge-primary" href="{{ route('sort', ['order_by' => $order_by, 'tags' => $tags, 'authors' => implode(',', array_diff($authors_array, [$author->id]))] ) }}">{{  $author->name }}</a></div>
			@endforeach
		    </div>
		    @endif
		</div>
	    </div>
	</div>
	@foreach($posts as $post)
        <br>
	    <div class="row justify-content-center">
		<div class="col-md-8">
		    <div class="card">
			<div class="card-header">
			    {{ $post->user->name }}
			</div>
			<div class="card-body">
			    <a href="{{ route('read', ['id' => $post->id]) }}"><h5 class="title">{{ $post->title }}</h5></a>
			    @if(strlen($post->content) < 100)
				{!! nl2br($post->content) !!}
			    @else
				{!!  nl2br(substr($post->content, 0, 97)."...") !!}
			    @endif
			</div>
			<div class="card-footer">
			    <div>{{ $post->likes_count }} <img class="like-icon" src="/thumbsup.svg" alt="likes"></div>
			    <div>{{ $post->comments_count }} <img class="like-icon" src="/comment.svg" alt="commentss"></div>
			    @foreach($post->tags as $tag)
				<span class="badge badge-primary"> {{ $tag->name }} </span>
			    @endforeach
			    <div>posted at {{ $post->created_at->format("H:i d/m/y") }}</div>
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
