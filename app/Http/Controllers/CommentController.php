<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Comment;
use App\Models\Post;

class CommentController extends Controller
{
    public function saveNewComment(Request $request){
        $request->validate([
            'content' => ['required', 'max:255'],
        ]);

        $comment = new Comment();
        $comment->user_id = Auth::id();
        $comment->post_id = $request->post_id;
        $comment->content = $request->content;
        $comment->save();

        return back();
    }

    public function deleteComment(Request $request){
        $comment = Comment::find($request->id);
        $comment->delete();
        
        return back();
    }
}
