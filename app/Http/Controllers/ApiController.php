<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;

use App\Models\Comment;
use App\Models\Friend;
use App\Models\User;
use App\Models\PostLike;
use App\Models\Post;

use Log;

class ApiController extends Controller
{
    /* ---------------------- Comment ------------------------- */

    /**
     * @OA\Post(
     * path="/comment/save",
     * summary="Save comment",
     * description="Save new comment",
     * tags={"Comment"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass comment parameters",
     *    @OA\JsonContent(
     *       required={"id","content"},
     *       @OA\Property(property="id", type="num", format="num", example="1"),
     *       @OA\Property(property="content", type="string", format="string", example="New comment"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="id", type="num", example="1")
     *     )
     * ),
     * @OA\Response(
     *    response=401,
     *    description="Unauthorized",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthenticated.")
     *     )
     * ),
     * @OA\Response(
     *    response=422,
     *    description="Missing parameters response",
     *    @OA\JsonContent(
    *          @OA\Property(property="message", type="string", default="Error explanation"),
    *        @OA\Property(property="errors", type="object", 
    *)
     *  )
     * ),
     * security={{"sanctum": {}}},

     * )
     */

    public function saveCommentApi(Request $request){
        $request->validate([
            'id' => ['required'],
            'content' => ['required', 'max:255'],
        ]);

        $comment = new Comment();
        $comment->user_id = $request->user()->id;
        $comment->post_id = $request->id;
        $comment->content = $request->content;
        $comment->save();

        return response()->json([
            "id" => $comment->id
        ]);
    }

    /**
     * @OA\Post(
     * path="/comment/delete",
     * summary="Delete comment",
     * description="Delete comment by comment id",
     * tags={"Comment"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass comment parameters",
     *    @OA\JsonContent(
     *       required={"id"},
     *       @OA\Property(property="id", type="num", format="num", example="1"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="num", example="200")
     *     )
     * ),
     * @OA\Response(
     *    response=401,
     *    description="Unauthorized",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthenticated.")
     *     )
     * ),
     * @OA\Response(
     *    response=422,
     *    description="Missing parameters response",
     *    @OA\JsonContent(
    *          @OA\Property(property="message", type="string", default="Error explanation"),
    *        @OA\Property(property="errors", type="object", 
    *)
     *  )
     * ),
     * security={{"sanctum": {}}},
     * )
     */

    public function deleteCommentApi(Request $request){
        $request->validate([
            'id' => ['required'],
        ]);

        $comment = Comment::find($request->id);
        if($comment){
            $comment->delete();
        }

        return response()->json(["status" => 200]);
    }


    /* ---------------------- Friend ------------------------- */

    /**
     * @OA\Post(
     * path="/follow",
     * summary="Follow user",
     * description="Start following user by given user id",
     * tags={"User"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass follow parameters",
     *    @OA\JsonContent(
     *       required={"id"},
     *       @OA\Property(property="id", type="num", format="num", example="1"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="num", example="200")
     *     )
     * ),
     * @OA\Response(
     *    response=401,
     *    description="Unauthorized",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthenticated.")
     *     )
     * ),
     * @OA\Response(
     *    response=422,
     *    description="Missing parameters response",
     *    @OA\JsonContent(
    *          @OA\Property(property="message", type="string", default="Error explanation"),
    *        @OA\Property(property="errors", type="object", 
    *)
     *  )
     * ),
     * security={{"sanctum": {}}},
     * )
     */
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

    /**
     * @OA\Post(
     * path="/unfollow",
     * summary="Unfollow user",
     * description="Stop following user by given user id",
     * tags={"User"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass unfollow parameters",
     *    @OA\JsonContent(
     *       required={"id"},
     *       @OA\Property(property="id", type="num", format="num", example="1"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="num", example="200")
     *     )
     * ),
     * @OA\Response(
     *    response=401,
     *    description="Unauthorized",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthenticated.")
     *     )
     * ),
     * @OA\Response(
     *    response=422,
     *    description="Missing parameters response",
     *    @OA\JsonContent(
    *          @OA\Property(property="message", type="string", default="Error explanation"),
    *        @OA\Property(property="errors", type="object", 
    *)
     *  )
     * ),
     * security={{"sanctum": {}}},
     * )
     */

    public function unfollowUserApi(Request $request){
        $request->validate([
            'id' => ['required'],
        ]);

        $user = User::find($request->id);
        $friend = Friend::where('user_id', $request->user()->id)->where('friend_id', $request->id)->first();
        if($friend){
            $friend->delete();
        }
        
        return response()->json(["status" => 200]);
    } 

    /* --------------- Like -------------- */

    /**
     * @OA\Post(
     * path="/post/upvote",
     * summary="Upvote post",
     * description="Upvote post by given post id",
     * tags={"Post"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass upvote parameters",
     *    @OA\JsonContent(
     *       required={"id", "like"},
     *       @OA\Property(property="id", type="num", format="num", example="1"),
     *       @OA\Property(property="like", type="num", format="num", example="1"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="num", example="200"),
     *       @OA\Property(property="data", type="num", example="4")

     *     )
     * ),
     * @OA\Response(
     *    response=401,
     *    description="Unauthorized",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthenticated.")
     *     )
     * ),
     * @OA\Response(
     *    response=422,
     *    description="Missing parameters response",
     *    @OA\JsonContent(
    *          @OA\Property(property="message", type="string", default="Error explanation"),
    *        @OA\Property(property="errors", type="object", 
    *)
     *  )
     * ),
     * security={{"sanctum": {}}},
     * )
    */

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

    /**
     * @OA\Post(
     * path="/post/downvote",
     * summary="Downvote post",
     * description="Downvote post by given post id",
     * tags={"Post"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass downvote parameters",
     *    @OA\JsonContent(
     *       required={"id", "like"},
     *       @OA\Property(property="id", type="num", format="num", example="1"),
     *       @OA\Property(property="like", type="num", format="num", example="0"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="num", example="200"),
     *       @OA\Property(property="data", type="num", example="4")

     *     )
     * ),
     * @OA\Response(
     *    response=401,
     *    description="Unauthorized",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthenticated.")
     *     )
     * ),
     * @OA\Response(
     *    response=422,
     *    description="Missing parameters response",
     *    @OA\JsonContent(
    *          @OA\Property(property="message", type="string", default="Error explanation"),
    *        @OA\Property(property="errors", type="object", 
    *)
     *  )
     * ),
     * security={{"sanctum": {}}},
     * )
    */

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

    /**
     * @OA\GET(
     * path="/posts",
     * summary="All Posts",
     * description="Get all posts",
     * tags={"Post"},
     * 
     * @OA\Response(
     *    response=200,
     *    description="Success",
      *    @OA\JsonContent(
        *   type="array",
        *   @OA\Items(
 *        @OA\Property(property="id", type="num", default="1"),
 *        @OA\Property(property="title", type="string", default="Testna objava"),     
 *        @OA\Property(property="content", type="string", default="Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum."),     
 *        @OA\Property(property="user_id", type="num", default="1"),     
 *        @OA\Property(property="deleted", type="boolean", default="0"),     
 *        @OA\Property(property="deleted_at", type="string", default=null),     
 *        @OA\Property(property="reported", type="boolean", default="0"),     
 *        @OA\Property(property="created_at", type="string", default="2022-11-28T19:17:57.000000Z"),     
 *        @OA\Property(property="updated_at", type="string", default="2022-11-28T19:17:57.000000Z"),     

            *)
 *        )
     *    )
     * )
     */

    public function getAllPostsApi(){
        $post = Post::where('deleted', 'false')->orderBy('created_at', 'desc')->get();
        return response()->json($post);
    }

    /**
     * @OA\GET(
     * path="/post/1",
     * summary="Get Post",
     * description="Get posts by id",
     * tags={"Post"},
     * 
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *        
     *    @OA\JsonContent(
     *      
    *     type="object",
 *        @OA\Property(property="id", type="num", default="1"),
 *        @OA\Property(property="title", type="string", default="Testna objava"),     
 *        @OA\Property(property="content", type="string", default="Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum."),     
 *        @OA\Property(property="user_id", type="num", default="1"),     
 *        @OA\Property(property="deleted", type="boolean", default="0"),     
 *        @OA\Property(property="deleted_at", type="string", default=null),     
 *        @OA\Property(property="reported", type="boolean", default="0"),     
 *        @OA\Property(property="created_at", type="string", default="2022-11-28T19:17:57.000000Z"),     
 *        @OA\Property(property="updated_at", type="string", default="2022-11-28T19:17:57.000000Z"),     


 *        )
     *     )
     *    )
     * )
     */


    public function getPostApi(Request $request){
        $post = Post::find($request->id);
        return response()->json($post);
    }

    /**
     * @OA\Post(
     * path="/post/save",
     * summary="Save post",
     * description="Save new post",
     * tags={"Post"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass post parameters",
     *    @OA\JsonContent(
     *       required={"title", "content"},
     *       @OA\Property(property="title", type="string", format="string", example="Swagger post"),
     *       @OA\Property(property="content", type="string", format="string", example="The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="id", type="num", example="1"),

     *     )
     * ),
     * @OA\Response(
     *    response=401,
     *    description="Unauthorized",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthenticated.")
     *     )
     * ),
     * @OA\Response(
     *    response=422,
     *    description="Missing parameters response",
     *    @OA\JsonContent(
    *          @OA\Property(property="message", type="string", default="Error explanation"),
    *        @OA\Property(property="errors", type="object", 
    *)
     *  )
     * ),
     * security={{"sanctum": {}}},
     * )
    */

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
            "id" => $post->id
        ]);
    }

    /**
     * @OA\Post(
     * path="/post/update",
     * summary="Update post",
     * description="Update post by given post id",
     * tags={"Post"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass post parameters",
     *    @OA\JsonContent(
     *       required={"id", "title", "content"},
     *       @OA\Property(property="id", type="num", format="string", example="7"),
     *       @OA\Property(property="title", type="string", format="string", example="Swagger update post"),
     *       @OA\Property(property="content", type="string", format="string", example="The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="num", example="200"),

     *     )
     * ),
     * @OA\Response(
     *    response=401,
     *    description="Unauthorized",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthenticated.")
     *     )
     * ),
     * @OA\Response(
     *    response=422,
     *    description="Missing parameters response",
     *    @OA\JsonContent(
    *          @OA\Property(property="message", type="string", default="Error explanation"),
    *        @OA\Property(property="errors", type="object", 
    *)
     *  )
     * ),
     * security={{"sanctum": {}}},
     * )
    */

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

    /**
     * @OA\Post(
     * path="/post/delete",
     * summary="Delete post",
     * description="Delete post by given post id",
     * tags={"Post"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass post parameters",
     *    @OA\JsonContent(
     *       required={"id"},
     *       @OA\Property(property="id", type="num", format="string", example="7"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="num", example="200"),
     *     )
     * ),
     * @OA\Response(
     *    response=401,
     *    description="Unauthorized",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthenticated.")
     *     )
     * ),
     * @OA\Response(
     *    response=422,
     *    description="Missing parameters response",
     *    @OA\JsonContent(
    *          @OA\Property(property="message", type="string", default="Error explanation"),
    *        @OA\Property(property="errors", type="object", 
    *)
     *  )
     * ),
     * security={{"sanctum": {}}},
     * )
    */

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

    /**
     * @OA\Post(
     * path="/post/report",
     * summary="Report post",
     * description="Report post by given post id",
     * tags={"Post"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass post parameters",
     *    @OA\JsonContent(
     *       required={"id"},
     *       @OA\Property(property="id", type="num", format="string", example="7"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="num", example="200"),
     *     )
     * ),
     * @OA\Response(
     *    response=401,
     *    description="Unauthorized",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthenticated.")
     *     )
     * ),
     * @OA\Response(
     *    response=422,
     *    description="Missing parameters response",
     *    @OA\JsonContent(
    *          @OA\Property(property="message", type="string", default="Error explanation"),
    *        @OA\Property(property="errors", type="object", 
    *)
     *  )
     * ),
     * security={{"sanctum": {}}},
     * )
    */

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

    /**
     * @OA\GET(
     * path="/post/user/1",
     * summary="User Posts",
     * description="Get all user posts by post id",
     * tags={"Post"},
     * 
     * @OA\Response(
     *    response=200,
     *    description="Success",
      *    @OA\JsonContent(
        *   type="array",
        *   @OA\Items(
 *        @OA\Property(property="id", type="num", default="1"),
 *        @OA\Property(property="title", type="string", default="Testna objava"),     
 *        @OA\Property(property="content", type="string", default="Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum."),     
 *        @OA\Property(property="user_id", type="num", default="1"),     
 *        @OA\Property(property="deleted", type="boolean", default="0"),     
 *        @OA\Property(property="deleted_at", type="string", default=null),     
 *        @OA\Property(property="reported", type="boolean", default="0"),     
 *        @OA\Property(property="created_at", type="string", default="2022-11-28T19:17:57.000000Z"),     
 *        @OA\Property(property="updated_at", type="string", default="2022-11-28T19:17:57.000000Z"),     

            *)
 *        )
     *    )
     * )
     */

    public function getUserPostsApi(Request $request){
        $posts = Post::where('user_id', $request->id)->where('deleted', false)->get();
        return response()->json($posts);
    }

    /**
     * @OA\GET(
     * path="/post/comments/1",
     * summary="Post comments",
     * description="Get all post comments by post id",
     * tags={"Post"},
     * 
     * @OA\Response(
     *    response=200,
     *    description="Success",
      *    @OA\JsonContent(
        *   type="array",
        *   @OA\Items(
 *        @OA\Property(property="id", type="num", default="1"),
 *        @OA\Property(property="user_id", type="num", default="1"),     
 *        @OA\Property(property="post_id", type="num", default="1"),     
 *        @OA\Property(property="content", type="string", default="Moja prva objava"),     
 *        @OA\Property(property="created_at", type="string", default="2022-11-28T19:17:57.000000Z"),     
 *        @OA\Property(property="updated_at", type="string", default="2022-11-28T19:17:57.000000Z"),     

            *)
 *        )
     *    )
     * )
     */

    public function getAllPostCommentsApi(Request $request){
        $comments = Comment::where('post_id', $request->id)->get();
        return response()->json($comments);
    }

    /**
     * @OA\GET(
     * path="/post/votes/1",
     * summary="Post votes",
     * description="Get all post votes by post id",
     * tags={"Post"},
     * 
     * @OA\Response(
     *    response=200,
     *    description="Success",
      *    @OA\JsonContent(
        *   type="array",
        *   @OA\Items(
 *        @OA\Property(property="id", type="num", default="1"),
 *        @OA\Property(property="like", type="num", default="1"),     
 *        @OA\Property(property="user_id", type="num", default="1"),     
 *        @OA\Property(property="post_id", type="num", default="1"),     
 *        @OA\Property(property="created_at", type="string", default="2022-11-28T19:17:57.000000Z"),     
 *        @OA\Property(property="updated_at", type="string", default="2022-11-28T19:17:57.000000Z"),     

            *)
 *        )
     *    )
     * )
     */

    public function getAllPostVotesApi(Request $request){
        $votes = PostLike::where('post_id', $request->id)->get();
        return response()->json($votes);   
    }

    /** --------------------------- User ------------------ */

    /**
     * @OA\Post(
     * path="/register",
     * summary="Sign up",
     * description="Register new user",
     * tags={"Authentication"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user parameters",
     *    @OA\JsonContent(
     *       required={"username","email","password"},
     *       @OA\Property(property="username", type="string", format="string", example="newUsername"),
     *       @OA\Property(property="email", type="string", format="email", example="newUser1@mail.com"),
     *       @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="access_token", type="string", example="2|Z9A4JDgbpwoMqBUIR4cYMbaiKfmxMNbiCLrTowZN")
     *     )
     * ),
     * @OA\Response(
     *    response=422,
     *    description="Missing parameters response",
     *    @OA\JsonContent(
    *          @OA\Property(property="message", type="string", default="Error explanation"),
    *        @OA\Property(property="errors", type="object", 
    *)
     *  )
     * ))
     */

    public function registerApi(Request $request){
        Log::info("asdad");
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
            'access_token' => $token,
        ]);
    }

    /**
     * @OA\GET(
     * path="/login",
     * summary="Sign in",
     * description="Login by email and password",
     * tags={"Authentication"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"email","password"},
     *       @OA\Property(property="email", type="string", format="email", example="newUser1@mail.com"),
     *       @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="access_token", type="string", example="2|Z9A4JDgbpwoMqBUIR4cYMbaiKfmxMNbiCLrTowZN")
     *     )
     * ),
     * @OA\Response(
     *    response=401,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *      @OA\Property(property="message", type="string", example="Invalid login details")
     *        )
     *     )
     * )
     */

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
        ]);
    }

    /**
     * @OA\GET(
     * path="/profile",
     * summary="Get user profile",
     * description="Get current user profile",
     * tags={"User"},

     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
        *   type="object",
 *        @OA\Property(property="id", type="num", default="6"),
 *        @OA\Property(property="username", type="string", default="newUsername"),     
 *        @OA\Property(property="privileges", type="string", default="user"),     
 *        @OA\Property(property="email", type="string", default="newUser1@gmail.com"),     
 *        @OA\Property(property="deleted", type="num", default="0"),     
 *        @OA\Property(property="deleted_at", type="string", default=null),     

 *        @OA\Property(property="created_at", type="string", default="2022-11-28T19:17:57.000000Z"),     
 *        @OA\Property(property="updated_at", type="string", default="2022-11-28T19:17:57.000000Z"),     

 *        )
     *     )
     * ),
     * @OA\Response(
     *    response=401,
     *    description="Unauthorized",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthenticated.")
     *     )
     * ),
     * ),
     * security={{"sanctum": {}}},
     * )
    */

    public function getProfileApi(Request $request){
        return response()->json($request->user());        
    }

    /**
     * @OA\GET(
     * path="/user/1",
     * summary="User info",
     * description="Get user info from user id",
     * tags={"User"},
     * 
     * @OA\Response(
     *    response=200,
     *    description="Success",
      *    @OA\JsonContent(
        *   type="object",
 *        @OA\Property(property="id", type="num", default="1"),
 *        @OA\Property(property="username", type="string", default="mts"),     
 *        @OA\Property(property="privileges", type="string", default="user"),     
 *        @OA\Property(property="email", type="string", default="user@gmail.com"),     
 *        @OA\Property(property="deleted", type="num", default="0"),     
 *        @OA\Property(property="deleted_at", type="string", default=null),     

 *        @OA\Property(property="created_at", type="string", default="2022-11-28T19:17:57.000000Z"),     
 *        @OA\Property(property="updated_at", type="string", default="2022-11-28T19:17:57.000000Z"),     

 *        )
     *    )
     * )
     */

    public function getUserInfoApi(Request $request){
        $user = User::find($request->id);
        return response()->json($user);
    }

    /**
     * @OA\Post(
     * path="/user/save",
     * summary="Update user",
     * description="Save updated user info",
     * tags={"User"},
     * 
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user parameters",
     *    @OA\JsonContent(
     *       required={"username", "password"},
     *       @OA\Property(property="username", type="string", format="string", example="newUsername"),
     *       @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="200")
     *     )
     * ),
     * @OA\Response(
     *    response=422,
     *    description="Missing parameters response",
     *    @OA\JsonContent(
    *          @OA\Property(property="message", type="string", default="Error explanation"),
    *        @OA\Property(property="errors", type="object", 
    *)
     *  )
     * ))
     */

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
