<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FriendController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/*
Route::get('/', function () {
    return view('welcome');
});
*/

// Home page
Route::get('/', [HomeController::class, 'showHome']);
Route::get('/home', [HomeController::class, 'showHome'])->name('home');

// Post
Route::get('/post/show/{id}', [PostController::class, 'showPost']);
Route::get('/user/{id}', [UserController::class, 'showUserInfo']);

Route::middleware(['auth'])->group(function () {
    // Post
    Route::get('/post/view', [PostController::class, 'viewUserPosts'])->name('post.view');
    Route::get('/post/add', [PostController::class, 'addNewPost'])->name('post.add');

    Route::post('/post/save', [PostController::class, 'saveNewPost']);
    Route::get('/post/edit/{id}', [PostController::class, 'editPost']);
    Route::post('/post/save/{id}', [PostController::class, 'saveUpdatedPost']);
    //Route::get('/post/{id}', [PostController::class, 'showPost']);
    Route::get('/post/delete/{id}', [PostController::class, 'deletePost']);
    Route::post('/post/report', [PostController::class, 'reportPost'])->name('post.report');

    // Post likes
    Route::post('/post/like', [LikeController::class, 'upvotePost'])->name('post.like');
    Route::post('/post/dislike', [LikeController::class, 'downvotePost'])->name('post.dislike');

    // User
    Route::get('/profile', [UserController::class, 'showProfile']);

    Route::post('/user/save', [UserController::class, 'saveUpdatedUser']);
    
    Route::post('/user/{id}/follow', [FriendController::class, 'followUser']);
    Route::post('/user/{id}/unfollow', [FriendController::class, 'unfollowUser']);

    // Comment
    Route::post('/comment/save', [CommentController::class, 'saveNewComment']);
    Route::get('/comment/delete/{id}', [CommentController::class, 'deleteComment']);
});

Route::middleware(['auth', 'moderator'])->group(function (){
    Route::get('/admin/post/{type}', [PostController::class, 'showPosts'])->name('admin.post');
    Route::get('/admin/post', [PostController::class, 'fetchPosts'])->name('post.fetch');
});

Route::middleware(['auth', 'admin'])->group(function (){
    Route::get('/admin/user/{type}', [UserController::class, 'showUsers'])->name('admin.user');
    Route::get('/admin/user', [UserController::class, 'fetchUsers'])->name('user.fetch');

    Route::get('/admin/user/block/{id}', [UserController::class, 'blockUser']);
    Route::get('/admin/user/unblock/{id}', [UserController::class, 'unblockUser']);
});



require __DIR__.'/auth.php';
