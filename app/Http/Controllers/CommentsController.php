<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Comment;

class CommentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /*
      Add comment to database.
     */
    public function comment(Request $request, $id)
    {
        $validatedData = $request->validate([
            'comment_text' => 'required'
        ]);

        $comment = Comment::create([
            'user_id' => Auth::id(),
            'post_id' => $id,
            'comment_text' => $request->input('comment_text')
        ]);

        $comment->save();
        return redirect()->route('read', ['id' => $id]);
    }
}
