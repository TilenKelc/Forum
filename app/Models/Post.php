<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\PostLike;
use App\Models\Comment;

class Post extends Model
{
    use HasFactory;

    public function getUser(){
        $user = User::find($this->user_id);
        return $user;
    }

    public function getLikes(){
        $likes = PostLike::where('post_id', $this->id)->get();
        if($likes->isEmpty()){
            return 0;
        }

        $num = 0;
        foreach($likes as $like){
            if($like->like){
                $num += 1;
            }else{
                $num -= 1;
            }
        }
        return $num;
    }

    public function isUpvoted(){
        return PostLike::where('post_id', $this->id)->where('user_id',  Auth::id())->where('like', true)->exists();
    }

    public function isDownvoted(){
        return PostLike::where('post_id', $this->id)->where('user_id',  Auth::id())->where('like', false)->exists();
    }

    public function getComments(){
        return Comment::where('post_id', $this->id)->get();
    }
}
