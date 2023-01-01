<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ApiController;

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
    Route::post('/post/save', [ApiController::class, 'savePostApi']);

    // update post 
    // body info (id, title, content)
    Route::post('/post/update', [ApiController::class, 'updatePostApi']);

    // delete post 
    // body info (id)
    Route::post('/post/delete', [ApiController::class, 'deletePostApi']);

    // report post 
    // body info (id)
    Route::post('/post/report', [ApiController::class, 'reportPostApi']);


    // Comment
    // save new comment
    // body info (id - post, content)
    Route::post('/comment/save', [ApiController::class, 'saveCommentApi']);

    // delete comment
    // body info (id - comment)
    Route::post('/comment/delete', [ApiController::class, 'deleteCommentApi']);


    // Follow
    // body info (id - user)
    Route::post('/follow', [ApiController::class, 'followUserApi']);

    // body info (id - user)
    Route::post('/unfollow', [ApiController::class, 'unfollowUserApi']);


    // Votes
    // upvote post
    // body info (id - post, like (boolean))
    Route::post('/post/upvote', [ApiController::class, 'upvotePostApi']);

    // downvote post
    // body info (id - post)
    Route::post('/post/downvote', [ApiController::class, 'downvotePostApi']);

    
    // User
     // get user profile
    Route::get('/profile', [ApiController::class, 'getProfileApi']);

    // get user profile
    // body (username, password)
    Route::post('/user/save', [ApiController::class, 'saveUpdatedUserApi']);
});

// header-info (Accept: application/json, Content-Type: application/json)

// Login
// body info (email, pass)
Route::post('/login', [ApiController::class, 'loginApi']);

// Register
// body info (username, email, pass)
Route::post('/register', [ApiController::class, 'registerApi']);


// Post
// all posts
Route::get('/posts', [ApiController::class, 'getAllPostsApi']);

// single post by id (ex: /post/1)
Route::get('/post/{id}', [ApiController::class, 'getPostApi']);

// user posts 
// body info (id - user)
Route::get('/post/user/{id}', [ApiController::class, 'getUserPostsApi']);

// get all post comments
// body info (id - post)
Route::get('/post/comments/{id}', [ApiController::class, 'getAllPostCommentsApi']);

// get all post votes
// body info (id - post)
Route::get('/post/votes/{id}', [ApiController::class, 'getAllPostVotesApi']);

// User
 // get user info
 Route::get('/user/{id}', [ApiController::class, 'getUserInfoApi']);
