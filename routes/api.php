<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FriendController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->group(function () {
    // Bearer token

    // Post
    // save new post 
    // body info (title, content)
    Route::post('/post/save', [PostController::class, 'savePostApi']);

    // update post 
    // body info (id, title, content)
    Route::post('/post/update', [PostController::class, 'updatePostApi']);

    // delete post 
    // body info (id)
    Route::post('/post/delete', [PostController::class, 'deletePostApi']);

    // report post 
    // body info (id)
    Route::post('/post/report', [PostController::class, 'reportPostApi']);


    // Comment
    // save new comment
    // body info (id - post, content)
    Route::post('/comment/save', [CommentController::class, 'saveCommentApi']);

    // delete comment
    // body info (id - comment)
    Route::post('/comment/delete', [CommentController::class, 'deleteCommentApi']);


    // Follow
    // body info (id - user)
    Route::post('/follow', [FriendController::class, 'followUserApi']);

    // body info (id - user)
    Route::post('/unfollow', [FriendController::class, 'unfollowUserApi']);


    // Votes
    // upvote post
    // body info (id - post, like (boolean))
    Route::post('/post/upvote', [LikeController::class, 'upvotePostApi']);

    // downvote post
    // body info (id - post)
    Route::post('/post/downvote', [LikeController::class, 'downvotePostApi']);

    
    // User
     // get user profile
    Route::get('/profile', [UserController::class, 'getProfileApi']);

    // get user profile
    // body (username, password)
    Route::post('/user/save', [UserController::class, 'saveUpdatedUserApi']);
});

// header-info (Accept: application/json, Content-Type: application/json)

// Login
// body info (email, pass)
Route::post('/login', [UserController::class, 'loginApi']);

// Register
// body info (username, email, pass)
Route::post('/register', [UserController::class, 'registerApi']);


// Post
// all posts
Route::get('/posts', [PostController::class, 'getAllPostsApi']);

// single post by id (ex: /post/1)
Route::get('/post/{id}', [PostController::class, 'getPostApi']);

// user posts 
// body info (id - user)
Route::get('/post/user/{id}', [PostController::class, 'getUserPostsApi']);

// get all post comments
// body info (id - post)
Route::get('/post/comments/{id}', [PostController::class, 'getAllPostCommentsApi']);

// get all post votes
// body info (id - post)
Route::get('/post/votes/{id}', [PostController::class, 'getAllPostVotesApi']);

// User
 // get user info
 Route::get('/user/{id}', [UserController::class, 'getUserInfoApi']);
