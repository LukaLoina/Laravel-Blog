<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Post;
use App\User;
//use App\Tag;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['filter', 'sort']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!Auth::check())
        {
            abort(403, "Unauthorized action.");
        }
        $user = User::find(Auth::id());
        $posts = Post::where('user_id', Auth::id())->withCount(['likes', 'comments'])->get();
        return view('home', ['user' => $user, 'posts' => $posts->sortByDesc('updated_at')]);
    }

    public function filter(Request $request, $order_by="date")
    {
        $authors = $request->query('authors', null);
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
                $tag_array[] = $rq_tag;
            }
            return redirect()->route('sort', ['order_by' => $order_by, 'tags'=> implode(',', $tags), 'authors' => $authors]);
        }
        return redirect()->route('sort', ['order_by' => $order_by, 'tags'=> null, 'authors' => $authors]);
    }

    public function sort(Request $request, $order_by="date")
    {
        // order_by: "date"|"user"|"likes"
        $posts = Post::with(['user', 'tags'])->withCount(['likes', 'comments']);
        $tags = $request->query('tags');
        $authors = $request->query('authors');
        $ignored_authors = null;
        $authors_array = array();
        
        if($authors)
        {
            $authors_exploded = explode(',', $authors);
            foreach($authors_exploded as $author)
            {
                if($author === "")
                {
                    continue;
                }
                $authors_array[] = $author;
            }
            $ignored_authors = User::whereIn('id', $authors_array)->get();
            $posts = $posts->whereNotIn('user_id', $authors_array);
        }
        
        if($tags)
        {
            $tags_exploded = explode(',', $tags);
            foreach($tags_exploded as $rq_tag)
            {
                $query_tag = trim($rq_tag);
                if($rq_tag === "")
                {
                    continue;
                }

                $posts = $posts->whereExists(function ($query) use ($query_tag) {
                    $query->select(DB::raw(1))
                        ->from('post_tag')
                        ->join('tags', 'tags.id', '=', 'post_tag.tag_id')
                        ->whereRaw('post_tag.post_id = posts.id')
                        ->where('tags.name', $query_tag);
                });
            }
        }

        $posts = $posts->get();
              
        if($order_by === "date")
        {
            $posts = $posts->sortByDesc('created_at');
        }
        else if($order_by === "user")
        {
            $posts = $posts->sortByDesc('user.name');
        }
        else if($order_by === "likes")
        {
            $posts = $posts->sortByDesc('likes_count');
        }
        else
        {
            abort(404, 'Action not found.');
        }
        
        return view('welcome', ['posts' => $posts, 'order_by' => $order_by, 'authors' => $authors, 'authors_array' => $authors_array, 'tags' => $tags, 'ignored_authors'=>$ignored_authors, ]);
    }
}
