<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Post;
use App\Models\PostLike;

use App\Http\Resources\PostResource;
use DataTables;

use App\Models\Comment;
use Log;

class PostController extends Controller
{
    public function addNewPost(){
        return view('post.add', [
            'post' => null
        ]);
    }

    public function saveNewPost(Request $request){
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'min:25', 'max:3000'],
        ]);

        $post = new Post();
        $post->title = $request->title;
        $post->content = $request->content;
        $post->user_id = Auth::id();
        $post->save();

        $like = new PostLike();
        $like->user_id = Auth::id();
        $like->post_id = $post->id;
        $like->like = true;
        $like->save();

        session(['successMssg' => 'Post successfully published']);

        return redirect('/home');
    }

    public function editPost(Request $request){
        $post = Post::find($request->id);
        return view('post.show', [
            'post' => $post,
            'check' => true
        ]);
    }

    public function saveUpdatedPost(Request $request){
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'min:25', 'max:1000'],
        ]);

        $post = Post::find($request->id);
        $post->title = $request->title;
        $post->content = $request->content;
        $post->save();

        session(['successMssg' => 'Post successfully updated']);
        return redirect("/post/show/$post->id");
    }

    public function deletePost(Request $request){
        $post = Post::find($request->id);
        $post->deleted = true;
        $post->deleted_at = date("Y-m-d h:i:s");
        $post->save();

        session(['successMssg' => 'Post successfully removed']);
        return redirect('/home');
    }

    public function reportPost(Request $request){
        $request->validate([
            'id' => ['required'],
        ]);

        $post = Post::find($request->id);
        $post->reported = true;
        $post->save();

        return json_encode('Post successfully reported. The admins will review it soon.');
    }

    public function showPost(Request $request){
        $post = Post::find($request->id);
        
        return view('post.show', [
            "post" => $post
        ]);
    }

    public function viewUserPosts(){
        $posts = Post::where('user_id', Auth::id())->where('deleted', false)->get();
        return view('post.view', [
            "posts" => $posts
        ]);
    }

    /* Admin functions */
    
    public function showPosts(){
        return view('admin.posts');
    }

    public function fetchPosts(Request $request){
        $post = Post::all();
        return Datatables::of($post)
            ->editColumn('title', function($post){
                return '<th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                    ' . $post->title . '</th>';
            })
            ->editColumn('user_id', function($post){
                return '<td class="px-6 py-4">' . $post->getUser()->username . '</td>';
            })
            ->editColumn('reported', function($post){
                if($post->reported){
                    return '<td class="px-6 py-4">Yes</td>';
                }else{
                    return '<td class="px-6 py-4">No</td>';
                }
            })
            ->editColumn('deleted', function($post){
                if($post->deleted){
                    return '<td class="px-6 py-4">Yes (' . $post->deleted_at . ')</td>';
                }else{
                    return '<td class="px-6 py-4">No</td>';
                }
            })
            ->addColumn('view', function($post){
                return "<a href='" . url("/post/show/$post->id") . "'>View content</a>";
            })
            ->rawColumns(['title', 'user_id', 'deleted', 'reported', 'view'])
            ->make(true);
    }
}
