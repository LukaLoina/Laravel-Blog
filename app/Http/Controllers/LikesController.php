<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Like;

class LikesController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function like($id)
    {
        $like = Like::create([
            'user_id' => Auth::id(),
            'post_id' => $id
        ]);
        $like->save();
        return redirect()->route('read', ['id' => $id]);
    }

    public function unlike($id)
    {
        $like = Like::where([['user_id', Auth::id()], ['post_id', $id]])->firstOrFail();
        $like->delete();
        return redirect()->route('read', ['id' => $id]);
    }
}
