<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Comment;
use App\Models\Friend;
use App\Models\User;
use App\Models\PostLike;
use App\Models\Post;

class ApiController extends Controller
{
    /* ---------------------- Comment ------------------------- */

    public function saveCommentApi(Request $request){
        $request->validate([
            'id' => ['required'],
            'content' => ['required', 'max:255'],
        ]);

        $comment = new Comment();
        $comment->user_id = 1;
        $comment->post_id = $request->id;
        $comment->content = $request->content;
        $comment->save();

        return response()->json([
            "status" => 200, 
            "id" => $comment->id
        ]);
    }

    public function deleteCommentApi(Request $request){
        $request->validate([
            'id' => ['required'],
        ]);

        $comment = Comment::find($request->id);
        $comment->delete();

        return response()->json(["status" => 200]);
    }


    /* ---------------------- Friend ------------------------- */

    public function followUserApi(Request $request){
        $request->validate([
            'id' => ['required'],
        ]);

        $user = User::find($request->id);
        $friend = new Friend();
        $friend->friend_id = $request->id;
        $friend->user_id = $request->user()->id;
        $friend->save();

        return response()->json([
            "status" => 200
        ]);
    }

    public function unfollowUserApi(Request $request){
        $request->validate([
            'id' => ['required'],
        ]);

        $user = User::find($request->id);
        $friend = Friend::where('user_id', $request->user()->id)->where('friend_id', $request->id)->first();
        $friend->delete();
        
        return response()->json(["status" => 200]);
    } 

    /* --------------- Like -------------- */

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

    /* --------------- Post ----------------- */

    public function getAllPostsApi(){
        $post = Post::where('deleted', 'false')->orderBy('created_at', 'desc')->get();
        return response()->json(["status" => 200, 'data' => $post]);
    }

    public function getPostApi(Request $request){
        $post = Post::find($request->id);
        return response()->json(["status" => 200, 'data' => $post]);
    }

    public function savePostApi(Request $request){
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'min:25', 'max:3000'],
        ]);

        $post = new Post();
        $post->title = $request->title;
        $post->content = $request->content;
        $post->user_id = $request->user()->id;
        $post->save();

        $like = new PostLike();
        $like->user_id = $request->user()->id;
        $like->post_id = $post->id;
        $like->like = true;
        $like->save();

        return response()->json([
            'status' => 200, 
            "id" => $post->id
        ]);
    }

    public function updatePostApi(Request $request){
        $request->validate([
            'id' => ['required'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'min:25', 'max:3000'],
        ]);

        $post = Post::find($request->id);
        $post->title = $request->title;
        $post->content = $request->content;
        $post->save();

        return response()->json([
            'status' => 200
        ]);
    }

    public function deletePostApi(Request $request){
        $request->validate([
            'id' => ['required'],
        ]);

        $post = Post::find($request->id);
        $post->deleted = true;
        $post->deleted_at = date('Y-m-d h:i:s');
        $post->save();

        return response()->json([
            'status' => 200
        ]);
    }

    public function reportPostApi(Request $request){
        $request->validate([
            'id' => ['required'],
        ]);

        $post = Post::find($request->id);
        $post->reported = true;
        $post->save();

        return response()->json([
            'status' => 200
        ]);
    }

    public function getUserPostsApi(Request $request){
        $posts = Post::where('user_id', $request->id)->where('deleted', false)->get();
        return response()->json([
            'status' => 200, 
            'data' => $posts
        ]);
    }

    public function getAllPostCommentsApi(Request $request){
        $comments = Comment::where('post_id', $request->id)->get();
        return response()->json([
            'status' => 200, 
            'data' => $comments
        ]);
    }

    public function getAllPostVotesApi(Request $request){
        $votes = PostLike::where('post_id', $request->id)->get();
        return response()->json([
            'status' => 200, 
            'data' => $votes
        ]);   
    }

    /** --------------------------- User ------------------ */

    public function registerApi(Request $request){
        $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 200,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function loginApi(Request $request){
        if(!Auth::attempt($request->only('email', 'password'))){
            return response()->json([
                'message' => 'Invalid login details'
            ], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function getProfileApi(Request $request){
        return response()->json([
            'status' => 200, 
            'data' => $request->user()
        ]);        
    }

    public function getUserInfoApi(Request $request){
        $user = User::find($request->id);
        return response()->json(["status" => 200, "data" => $user]);
    }

    public function saveUpdatedUserApi(Request $request){
        $user = $request->user();

        if($request->password != null || $request->password_confirmation != null){
            $request->validate([
                'password' => ['required', Rules\Password::defaults()],
            ]);

            $user->password = Hash::make($request->password);
        }
        
        if($request->username != Auth::user()->username){
            $request->validate([
                'username' => ['required', 'string', 'max:255', 'unique:users'],
            ]);
            $user->username = $request->username;
        }
        $user->save();

        return response()->json(['status' => 200]);
    }
}
