<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;

use App\Models\Friend;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isAdmin(){
        if($this->privileges == 'admin'){
            return true;
        }
        return false;
    }

    public function isModerator(){
        if($this->privileges == 'moderator'){
            return true;
        }
        return false;
    }

    public function isBlocked(){
        if($this->deleted){
            return true;
        }
        return false;
    }

    /*public function getAllFriends(){
        $friend_ids = Friend::where("user_id", $user_id)->get();
        $friends_array = [];
        foreach($friend_ids as $id){
            $user = User::find($id);
            array_push($friends_array, $user);
        }
        return $friends_array;
    }*/

    public function getAllFriendsID(){
        $friends = Friend::where("user_id", Auth::id())->get();
        $friends_array = [];
        foreach($friends as $friend){
            array_push($friends_array, $friend->friend_id);
        }
        return $friends_array;
    }
}
