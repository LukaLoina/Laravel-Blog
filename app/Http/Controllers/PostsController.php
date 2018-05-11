<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Post;
use App\Like;
use App\Tag;

class PostsController extends Controller
{

    /*
      Only authenticated users can create, update and delete posts.
      Everyone can read posts.
     */
    public function __construct()
    {
        $this->middleware('auth')->except('read');
    }

    /*
      Return form for creating post to user.
     */
    public function createForm()
    {
        return view('postCreate');
    }

    /*
      Create new blog post.
     */
    public function create(Request $request)
    {
        
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required'
        ]);

        $post = Post::create([
            'user_id' => Auth::id(),
            'title' => $request->input('title'),
            'content' => $request->input('content')
        ]);
        $post->save();

        if($request->has('tags'))
        {
            $tags = explode(',', $request->input('tags'));
            $tag_array = array();
            foreach($tags as $rq_tag)
            {
                $rq_tag = trim($rq_tag);
                if($rq_tag === "")
                {
                    continue;
                }
                $db_tag = Tag::firstOrCreate(['name' => $rq_tag]);
                $tag_array[] = $db_tag->id;
            }
            $post->tags()->sync($tag_array);
        }
        return redirect()->route('read', ['id' => $post->id]);
    }

    /*
      Display blog post.
     */
    public function read($id)
    {
        $post = Post::with(['comments', 'comments.user', 'tags', 'user'])->withCount(['likes', 'comments'])->findOrFail($id);
        $like = null;
        if(Auth::check())
        {
            $like = Like::where([['user_id', Auth::id()], ['post_id', $id]])->first();
        }
        return view('postRead', ['post' => $post, 'user_liked' => $like]);
    }

    /*
      Return form for updating post to user.
     */
    public function updateForm($id)
    {
        $post = Post::with(['tags'])->findOrFail($id);
        if($post->user_id === Auth::id())
        {
            $imploded_tags = $post->tags->implode('name', ', ');
            return view('postUpdate', ['post' => $post, 'tags' => $imploded_tags]);
        }
        else
        {
            abort(403, 'Unauthorized action.');
        }
    }

    /*
      Update blog post with new values that user provided.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required'
        ]);
        
        $post = Post::findOrFail($id);
        if($post->user_id === Auth::id())
        {
            $post->title = $request->input('title');
            $post->content = $request->input('content');
            $post->save();

            if($request->has('tags'))
            {
                $tags = explode(',', $request->input('tags'));
                $tag_array = array();
                foreach($tags as $rq_tag)
                {
                    $rq_tag = trim($rq_tag);
                    if($rq_tag === "")
                    {
                        continue;
                    }
                    $db_tag = Tag::firstOrCreate(['name' => $rq_tag]);
                    $tag_array[] = $db_tag->id;
                }
                $post->tags()->sync($tag_array);
            }
            
            return redirect()->route('read', ['id' => $post->id]);
        }
        else
        {
            abort(403, 'Unauthorized action.');
        }
    }

    /*
      Prompt user about deleting post before deleting it.
     */
    public function deleteForm($id)
    {
        $post = Post::findOrFail($id);
        if($post->user_id === Auth::id())
        {
            return view('postDelete', ['id' => $id, 'title' => $post->title]);
        }
        else
        {
            abort(403, 'Unauthorized action.');
        } 
    }

    /*
      Delete post.
     */
    public function delete(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        if($post->user_id === Auth::id())
        {
            if($request->input('conformationCheckbox'))
            {
                $post->delete();
                return redirect()->route('home');
            }
            else
            {
                return redirect()->route('read', ['id' => $post->id]);
            }
        }
        else
        {
            abort(403, 'Unauthorized action.');
        } 
    }
}
