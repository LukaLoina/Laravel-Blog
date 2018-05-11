@extends('layouts.app')

@section('content')
    <div class="container">
	<div class="row justify-content-center">
            <div class="col-md-8">
		<div class="card">
                    <div class="card-header">Your information.</div>
                    <div class="card-body">
			<div>Name: {{ $user->name }}</div>
			<div>Email: {{ $user->email }}</div>
			<div>Joined at: {{ $user->created_at->format("H:i d/m/y") }}</div>
                    </div>
		</div>
            </div>
	</div>
	<br>
	<div class="row justify-content-center">
            <div class="col-md-8">
		<div class="card">
		    <div class="card-header">Your posts</div>
		    @if(count($posts) === 0)
			<div class="card-body">
			    <h5 class="card-title text-center">You have no posts.</h5>
			</div>
		    @else
			@foreach($posts as $post)
			    <ul class="list-group list-group-flush">
				<li class="list-group-item">
				    <h5 class="title"><a href="{{ route('read', ['id' => $post->id]) }}">{{ $post->title }}</a></h5>
				    <div>{{ $post->likes_count }} <img class="like-icon" src="/thumbsup.svg" alt="likes"></div>
                                    <div>{{ $post->comments_count }} <img class="like-icon" src="/comment.svg" alt="commentss"></div>
				    @foreach($post->tags as $tag)
					<span class="badge badge-primary"> {{ $tag->name }} </span>
				    @endforeach
				    <div>
					created at {{ $post->created_at->format("H:i d/m/y") }}
					@if($post->created_at != $post->updated_at)
					    , last updated at {{ $post->updated_at->format("H:i d/m/y") }}
					@endif
				    </div>
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
