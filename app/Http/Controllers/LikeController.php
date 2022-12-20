<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Post;
use App\Models\PostLike;

use Log;

class LikeController extends Controller
{
    public function upvotePost(Request $request){
        $request->validate([
            'id' => ['required', 'min:1'],
            'like' => ['required'],
        ]);

        $check_if_already_liked = PostLike::where('user_id', Auth::id())->where('post_id', $request->id)->first();
        if($check_if_already_liked == null){
            $like = new PostLike();
            $like->post_id = $request->id;
            $like->user_id = Auth::id(); 
            $like->like = $request->like;
            $like->save();
        }else{
            if($check_if_already_liked->like == false){
                $like = PostLike::find($check_if_already_liked->id);
                $like->like = true;
                $like->save();
            }else{
                $check_if_already_liked->delete();
            }
        }     
        
        $post = Post::find($request->id);
        return json_encode($post->getLikes());
    }

    public function downvotePost(Request $request){
        $request->validate([
            'id' => ['required', 'min:1'],
            'like' => ['required'],
        ]);

        $check_if_already_disliked = PostLike::where('user_id', Auth::id())->where('post_id', $request->id)->first();
        if($check_if_already_disliked == null){
            $like = new PostLike();
            $like->post_id = $request->id;
            $like->user_id = Auth::id(); 
            $like->like = $request->like;
            $like->save();
        }else{
            if($check_if_already_disliked->like == true){
                $like = PostLike::find($check_if_already_disliked->id);
                $like->like = false;
                $like->save();
            }else{
                $check_if_already_disliked->delete();
            }
        }     
        
        $post = Post::find($request->id);
        return json_encode($post->getLikes());
    }

    /* --------------- Api -------------- */

    public function upvotePostApi(Request $request){
        $request->validate([
            'id' => ['required'],
            'like' => ['required'],
        ]);

        $check_if_already_liked = PostLike::where('user_id', $request->user()->id)->where('post_id', $request->id)->first();
        if($check_if_already_liked == null){
            $like = new PostLike();
            $like->post_id = $request->id;
            $like->user_id = $request->user()->id; 
            $like->like = $request->like;
            $like->save();
        }else{
            if($check_if_already_liked->like == false){
                $like = PostLike::find($check_if_already_liked->id);
                $like->like = true;
                $like->save();
            }else{
                $check_if_already_liked->delete();
            }
        }     
        
        $post = Post::find($request->id);
        return response()->json([
            "status" => 200, 
            'data' => $post->getLikes()
        ]);
    }

    public function downvotePostApi(Request $request){
        $request->validate([
            'id' => ['required', 'min:1'],
            'like' => ['required'],
        ]);

        $check_if_already_disliked = PostLike::where('user_id', $request->user()->id)->where('post_id', $request->id)->first();
        if($check_if_already_disliked == null){
            $like = new PostLike();
            $like->post_id = $request->id;
            $like->user_id = $request->user()->id; 
            $like->like = $request->like;
            $like->save();
        }else{
            if($check_if_already_disliked->like == true){
                $like = PostLike::find($check_if_already_disliked->id);
                $like->like = false;
                $like->save();
            }else{
                $check_if_already_disliked->delete();
            }
        }     
        
        $post = Post::find($request->id);
        return response()->json([
            "status" => 200,
            'data' => $post->getLikes()
        ]);
    }
}
